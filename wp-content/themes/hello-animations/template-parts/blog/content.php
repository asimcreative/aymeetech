

<article <?php post_class('default-blog__item-single'); ?>>    
      <?php if(has_post_thumbnail()): ?> 
        <a href="<?php the_permalink(); ?>"><img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>"></a>
      <?php endif; ?>   
      <div class="default-blog__content">
        <?php echo hello_animation_post_meta(); ?>
          <h2 class="default-blog__item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="cf_text">
          <?php hello_animation_excerpt( 40, null ); ?>
          </div>
          <?php if(get_theme_mod('ha_blog_readmore',1)): ?>
            <div class="cf_btn">
              <a href="<?php the_permalink(); ?>" class="wc-btn-underline"><?php echo esc_html__('Readmore', 'hello-animation'); ?><i class="icon-wcf-arrow-right"></i></a>
            </div>
          <?php endif; ?>
      </div>
  </article>
