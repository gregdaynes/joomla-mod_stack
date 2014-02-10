<?php
/**
 * Helper class for Stack
 *
 * @package    jevolve.extensions
 * @subpackage Modules
 * @link       http://jevolve.net
 * @license    GNU/GPL, see LICENSE.php
 * mod_stack is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

class modStackHelper
{

  /**
   * Retrieves a list of content items to display
   *
   * @param array $params An object containing the module parameters
   * @access public
   */
  public static function getList( &$params )
  {
  
  

    $db       = JFactory::getDbo();
    $user     = JFactory::getUser();
    $groups   = implode(',', $user->getAuthorisedViewLevels());

    // Preferences
    $categories = $params->get('categories');
    $tags       = $params->get('tags');
    $featured   = $params->get('featured', 0);
    $maximum    = $params->get('maximum', 5);
    $order      = $params->get('order', 0);
    $feature_first = $params->get('featured_first', 0);
    $direction  = $params->get('direction', 0);
    $template   = $params->get('template', 'Carousel');
    $use_js     = $params->get('use_js', 1);
    $use_css    = $params->get('use_css', 1);
    $truncate   = $params->get('truncate', 140);
    $itemid     = $params->get('itemid', false);
    
    $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
    $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

    // Query

    // published / archived
    $conditions = $db->quoteName('state') . ' >= ' . 1;

    // Multiple categories to search for
    $conditions .= ' AND (';
    foreach($categories as $index=>$category) {
      $conditions_or[] = $db->quoteName('catid') . ' = ' . $category;
    }
    $conditions .= implode($conditions_or, ' OR ');
    $conditions .= ')';
    
    
    $query = 'SELECT '
             . $db->quoteName( 'content.id' )
         .','. $db->quoteName( 'content.alias' )
         .','. $db->quoteName( 'content.title' )
         .','. $db->quoteName( 'content.catid' )
         .','. $db->quoteName( 'content.introtext' )
         .','. $db->quoteName( 'content.fulltext' )
         .','. $db->quoteName( 'content.images' )
         .','. $db->quoteName( 'content.access' )
         .','. $db->quoteName( 'cat.id' )
                 . ' AS category_id'
         .','. $db->quoteName( 'cat.alias' )
                 . ' AS category_alias'
       .' '. 'FROM '
               . $db->quoteName('#__content', 'content')
       .' '. 'RIGHT JOIN '
               . $db->quoteName('#__categories', 'cat')
                 . ' ON '. $db->quoteName('content.catid') . ' = ' . $db->quoteName('cat.id')
       .' '. 'WHERE '
             . $conditions
       .' '. 'ORDER BY '
             . $db->quoteName('content.created') . ' DESC'
       .' '. 'LIMIT 0, ' . $maximum
    ;
    
    // build query and return it
    $db->setQuery($query);    
    

//     var_dump($db->replacePrefix((string) $db->getQuery())); 
//     exit; 

    $results = $db->loadObjectList();

    // findImage
    foreach($results as $index=>$item) {
      $results[$index]->introimage = modStackHelper::findImage($item);
    }

    // remove images from item
    foreach($results as $index=>$item) {
      $results[$index]->introtext = modStackHelper::removeImages($item->introtext);
    }


    /*
     * process content length
     */
    foreach($results as $index=>$item) {
      $results[$index]->introtext = modStackHelper::truncate($item->introtext, $truncate);
    }
    
    
    foreach($results as $index=>$item) {
      $item->slug = $item->id . ':' . $item->alias;
			$item->catslug = $item->category_id . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article

        if ($itemid) {
          $item->link = modStackHelper::getArticleRoute($item->slug, $itemid, $item->catslug);
        } else {
          $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
        }

      }
      else
      {
      	$item->link = JRoute::_('index.php?option=com_users&view=login');
      }
        
    }



    return $results;
  }

  public static function getArticleRoute($id, $itemid = 0, $catid = 0, $language = 0)
  {
    $needles = array(
			'article'  => array((int) $id)
		);
		
		//Create the link
		$link = 'index.php?option=com_content&view=article&id='. $id;
		if ((int) $catid > 1)
		{
			$categories = JCategories::getInstance('Content');
			$category = $categories->get((int) $catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			self::buildLanguageLookup();

			if (isset(self::$lang_lookup[$language]))
			{
				$link .= '&lang=' . self::$lang_lookup[$language];
				$needles['language'] = $language;
			}
		}

		$link .= '&Itemid='.$itemid;

		return $link;
	
  }

  public static function truncate($text, $chars = 25)
  {
    $text = $text." ";
    $text = substr($text,0,$chars);
    $text = substr($text,0,strrpos($text,' '));
    $text = $text."...";

    return $text;
  }

  public static function findImage($item)
  {
    $images = json_decode($item->images);

    /*
    $image_intro
    $image_intro_alt
    $image_intro_caption

    $image_fulltext
    $image_fulltext_alt
    $image_fulltext_caption
    */

    $image_intro = null;

    // catch image field in database being empty completely
    if (!$images) {
      $images = new JObject;
      $images->setProperties(array('image_intro' => null, 'image_fulltext' => null));
    }

    // look for intro image
    if ($images->image_intro != '') {
      $image_intro = array('image' => $images->image_intro,
                           'alt'   => $images->image_intro_alt,
                           'caption' => $images->image_intro_caption);
    }

    // look for fulltext image
    else if ($images->image_fulltext != '') {
      $image_intro = array('image' => $images->image_fulltext,
                           'alt'   => $images->image_fulltext_alt,
                           'caption' => $images->image_fulltext_caption);
    }

    else {

      // find image in intro text
      $src = null;

      // <img[^>]+>
      // explode(' ', $text) // explode on spaces inside tag
      // explode('=', $text) // explode the
      // get src tag
      // trim($value, "'"); // strip quotes from value
      $pattern = '/<img[^>]+>/';
      $matches = null;


      if (!$matches) {
        preg_match($pattern, $item->introtext, $intro_matches);

        if ($intro_matches) {
          $matches = $intro_matches;
        }
      }



      if (!$matches) { // found one
        preg_match($pattern, $item->fulltext, $fulltext_matches);
        if ($fulltext_matches) {
          $matches = $fulltext_matches;
        }
      }

      if (!$matches) {
        return;
      }

      $match = $matches[0]; // only need one

      if (!$matches) {
        return;
      }

      $attributes = explode(' ', $match);
      foreach($attributes as $index=>$attribute) {
        $attributes[$index] = explode('=', $attribute);
      }

      foreach($attributes as $index=>$attribute) {
        if (in_array('src', $attribute)) {
          $src = $attribute[1];
        }
      }

      $image_intro = array('image' => trim($src, "'\""));
    }


    return $image_intro;

  }

  public static function removeImages($item)
  {
    return preg_replace('/<img[^>]+>/', '', $item);
  }
}