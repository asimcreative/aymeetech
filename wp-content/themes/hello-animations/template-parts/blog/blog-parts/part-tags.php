<?php 
   $single_post_tags = get_theme_mod( 'ha_single_post_tags' , 1 );

   /* Check is enable */
   if(!$single_post_tags){
      return;
   }
   
   $tag_list = hello_animation_has_post_tags();   
    /* Check is content exist */
   if( !is_array($tag_list ) ){
      return; 
   } 
   
?>  
  <ul class="default-details-tags justify-content-start">
      <?php foreach($tag_list as $item): ?>
         <li>
            <a href="<?php echo esc_url(get_term_link($item->term_id)); ?>">
               <?php 
                  echo esc_html($item->name);
               ?>
            </a>
         </li>
      <?php endforeach; ?>
  </ul>
   