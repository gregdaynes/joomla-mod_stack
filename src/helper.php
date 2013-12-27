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

    $query = $db->getQuery(true)
      ->select($db->quoteName(array('title', 'catid', 'introtext', 'images')))
      ->from($db->quoteName('#__content'))
      ->order($db->quoteName('created') . ' ' . 'DESC');

    $db->setQuery($query, 0, $maximum);
    $results = $db->loadObjectList();

    /*
    @todo Image processing
    foreach($results as $index=>$item) {
      $images = json_decode($item->images);

      print_r($images);

      echo '<hr />';
    }
    */

    /*
     * process content length
     */
    foreach($results as $index=>$item) {
      $results[$index]->introtext = modStackHelper::truncate($item->introtext, 100);
    }

    return $results;
  }

  public static function truncate($text, $chars = 25)
  {
    $text = $text." ";
    $text = substr($text,0,$chars);
    $text = substr($text,0,strrpos($text,' '));
    $text = $text."...";

    return $text;
  }
}