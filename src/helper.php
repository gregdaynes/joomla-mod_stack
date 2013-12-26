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
    $maximum  = 999; //$params->get('maximum', 5);

    $query = $db->getQuery(true)
      ->select(
        'title',
        'catid',
        'introtext',
        'images'
      )
      ->from($db->quoteName('#__content'))
      ->order($db->quoteName('created') . ' ' . 'DESC');

    $db->setQuery($query, 0, $maximum);
    $results = $db->loadObjectList();

    print_r($results);
    exit;

    return $results;
  }
}