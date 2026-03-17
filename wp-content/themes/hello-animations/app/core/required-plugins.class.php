<?php

namespace Hello_Animation\Core;

/**
 * Required Plugin.
 */
class Required_Plugins
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action('tgmpa_register', [$this, 'register_required_plugins']);
    }

    public function get_plugin_config_data()
    {

        $plugins = array(

            array(
                'name' => esc_html__('Elementor', 'hello-animation'),
                'slug' => 'elementor',
                'required' => false,
            ),

            array(
                'name' => esc_html__('Kirki Customizer Framework', 'hello-animation'),
                'slug' => 'kirki',
                'required' => false,
            ),

            array(
                'name' => esc_html__('Animation Addons For Elementor', 'hello-animation'),
                'slug' => 'animation-addons-for-elementor',
                'required' => false,
            )

        );

        return $plugins;
    }
    //required plugins
    public function register_required_plugins()
    {

        $plugins = $this->get_plugin_config_data();

        $config = array(
            'id' => 'hello-animation-theme', // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '', // Default absolute path to bundled plugins.
            'menu' => 'hello-animation-install-plugins', // Menu slug.
            'parent_slug' => 'themes.php', // Parent menu slug.
            'capability' => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices' => true, // Show admin notices or not.
            'dismissable' => true, // If false, a user cannot dismiss the nag message.
            'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => true, // Automatically activate plugins after installation or not.
            'message' => '', // Message to output right before the plugins table.
        );

        tgmpa($plugins, $config);
    }

}