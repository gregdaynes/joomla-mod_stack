<?php // no direct acces
defined( '_JEXEC' ) or die( 'Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'media/mod_stack/css/mod_stack.css');

$document->addScript(JURI::base() . 'media/mod_stack/js/picturefill.js');
$template   = $params->get('template', 'Carousel');
$moduleclass_sfx = $params->get('moduleclass_sfx', $template);

$breakpoint = $params->get('breakpoint', 480);
$breakpoint_images = $params->get('breakpoint_images', array(480, 768));
$item_count = count($list);

$style = '
@media (min-width: '.$breakpoint.'px) {
  .stack--carousel {
    position: relative;
  }

  .stack--carousel .stack__viewport {
    overflow-x: hidden;
    width: 100%;
  }

  .stack--carousel .stack__container {
    transition: margin 0.3s;
    display: block;
    margin-left: 0px;
    width: '. 100 * $item_count . '%;
  }

  .stack--carousel .stack__item {
    float: left;
    margin: 0px;
    padding: 0px;
    width: ' . 100 / $item_count . '%;
  }

  .stack--carousel .stack__item figure {
    margin: 0;
  }

  .stack--carousel .stack__caption {
    top: 100%;
    padding: 0 10px 10px;
    width: 100%;
  }

  .stack--carousel .stack__title {
    border-bottom-style: none;
    padding: 0px;
  }

  .stack--carousel .stack__img {
    display: block;
  }

  .stack--carousel .stack__nav {
    display: block;
    text-align: center;
  }

  .stack--carousel .stack__nav .button,
  .stack--carousel .stack__nav-item {
    background-color: transparent;
    border-style: none;
    cursor: pointer;
    margin: 0px;
    display: inline-block;
  }

  .stack--carousel .stack__nav-next {
    float: right;
  }

  .stack--carousel .stack__nav-previous {
    float: left;
  }


}';

$style_actions  = '@media (min-width: '.$breakpoint.'px) {'."\n";
foreach($list as $index=>$item) {
  $style_actions .= '#stack--' . $moduleclass_sfx . '__item' . $index . ':checked ~ .stack__viewport .stack__container { margin-left: ' . ($index * 100 * -1) .'%; }'."\n";
}
$items = array();
foreach($list as $index=>$item) {
  $item_index = '#stack--' . $moduleclass_sfx . '__item' . $index . ':checked ~ .stack__nav .stack__nav-item[for=stack--' . $moduleclass_sfx . '__item' . $index;
  $items[] = $item_index;
}


$style_actions .= implode(','."\n", $items) . '{ text-decoration: underline; }';
$style_actions .= '}';

$document->addStyleDeclaration( $style );
$document->addStyleDeclaration( $style_actions );

?>




<div class="stack  stack--carousel stack--<?php echo $moduleclass_sfx; ?>">

  <?php foreach($list as $index=>$item) : ?>
  <input type="radio" name="stack--<?php echo $moduleclass_sfx; ?>" id="stack--<?php echo $moduleclass_sfx; ?>__item<?php echo $index; ?>" class="stack__radio-control" <?php if ($index == 0) : ?>checked<?php endif; ?> />
  <?php endforeach; ?>

  <div class="stack__viewport">
    <div class="stack__container">

      <?php foreach($list as $index=>$item) : ?>

      <article class="stack__item">
        <figure class="stack__figure">
          <div class="stack__img-wrapper">
            <span class="stack__img" data-picture data-alt="<!-- $img-alt | Alt Text -->">
              <span data-src="http://placehold.it/480x270"></span>
              <span data-src="http://placehold.it/569x320" data-media="(min-width: 480px)"></span>
              <span data-src="http://placehold.it/853x480" data-media="(min-width: 768px)"></span>

              <noscript>
                  <img src="http://placehold.it/480x270" alt="<!-- $img-alt | Alt Text -->">
              </noscript>
            </span>
          </div>

          <figcaption class="stack__caption">
            <h1 class="stack__title"><?php echo $item->title; ?></h1>
            <p class="stack__text"><?php echo $item->introtext; ?></p>
            <a class="stack__link" href="#">Read More&hellip;</a>
          </figcaption>
        </figure>
      </article>

      <?php endforeach; ?>

    </div>
  </div>

  <nav class="stack__nav">

    <h3 class="section-header"><?php echo $module->title; ?> Navigation</h3>

    <?php foreach($list as $index=>$item) : ?>
    <label class="stack__nav-item" for="stack--<?php echo $moduleclass_sfx; ?>__item<?php echo $index; ?>"><?php echo $index + 1; ?></label>
    <?php endforeach; ?>

    <button class="stack__nav-previous  js-carousel-button-previous">Previous Item</button>
    <button class="stack__nav-next      js-carousel-button-next">Next Item</button>
  </nav>
</div>