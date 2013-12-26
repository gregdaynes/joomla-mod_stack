<?php // no direct acces
defined( '_JEXEC' ) or die( 'Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'media/mod_stack/css/mod_stack.css');
$document->addScript(JURI::base() . 'media/mod_stack/js/picturefill.js');
$template   = $params->get('template', 'Carousel');
$moduleclass_sfx = $params->get('moduleclass_sfx', $template);
?>


<div class="stack  stack--carousel stack--<?php echo $moduleclass_sfx; ?>">

  <?php foreach($list as $index=>$item) : ?>
  <input type="radio" name="stack--<?php $moduleclass_sfx; ?>" id="stack--<?php echo $moduleclass_sfx; ?>__item<?php echo $index; ?>" class="stack__radio-control  is-hidden" <?php if ($index == 0) : ?>checked<?php endif; ?> />
  <?php endforeach; ?>

  <div class="stack__viewport">
    <div class="stack__container">

      <?php foreach($list as $index=>$item) : ?>

      <article class="stack__item">
        <figure>
          <div class="stack__img-wrapper">
            <span class="stack__img" data-picture data-alt="<!-- $img-alt | Alt Text -->">
              <span data-src="http://placehold.it/320x180"></span>
              <span data-src="http://placehold.it/569x320" data-media="(min-width: 480px)"></span>
              <span data-src="http://placehold.it/853x480" data-media="(min-width: 768px)"></span>

              <noscript>
                  <img src="http://placehold.it/320x320" alt="<!-- $img-alt | Alt Text -->">
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