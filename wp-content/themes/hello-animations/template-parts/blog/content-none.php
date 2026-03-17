
<div class="default-search__again-form">
    <div class="default-search-title-wrapper pb-20 text-center">
        <h2 class="default-search-title"><?php echo esc_html__('Nothing found!','hello-animation'); ?></h2>
    </div>
    <?php echo wp_kses_post( wpautop(esc_html__('It looks like nothing was found here. Maybe try a search?','hello-animation')) ); ?>    
    <?php get_search_form(); ?>   
</div>
 