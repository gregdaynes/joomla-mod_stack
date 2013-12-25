<?php // no direct acces
defined( '_JEXEC' ) or die( 'Restricted access'); ?>

<?php
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'media/mod_stack/css/mod_stack.css');
?>

<?php echo $message; ?>