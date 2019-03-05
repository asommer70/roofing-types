<?php
  $rooftypes = get_posts(['numberposts' => -1, 'post_type' => 'roofingtypes', 'orderby' => 'menu_order', 'order' => 'ASC',]);
?>

<!-- Carousel -->
<section class="carousel">
  <div class="reel">
    <?php foreach($rooftypes as $rooftype) { ?>
      <?php
        $link = get_post_meta($rooftype->ID, 'roofingtypes_link', true);
        $image_url = wp_get_attachment_url(get_post_thumbnail_id($rooftype->ID));
      ?>
      <article>
        <a href="<?php echo $link; ?>" class="image featured"><img src="<?php echo $image_url; ?>" alt="<?php echo $rooftype->post_title; ?>" /></a>
        <header>
          <h3><a href="" data-modal="#modal" class="modal__trigger"><?php echo $rooftype->post_title ?></a></h3>
        </header>

        <div id="modal" class="modal modal__bg" role="dialog" aria-hidden="true">
          <div class="modal__dialog">
            <div class="modal__content">
              <h2><?php echo $rooftype->post_title; ?></h2>

              <div>
                <?php echo $rooftype->post_content; ?>
              </div>

              <!-- modal close button -->
              <a href="" class="modal__close demo-close">
                <svg class="" viewBox="0 0 24 24">
                  <path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"/>
                  <path d="M0 0h24v24h-24z" fill="none"/>
                </svg>
              </a>

            </div>
          </div>
        </div>
      </article>
    <?php } ?>
    </div>
</section>
