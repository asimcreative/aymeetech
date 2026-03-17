<?php
/**
 * Plugin Name: AymeeTech Custom Styles & UI Enhancements
 * Description: Modern UI improvements, animations, and visual enhancements
 * Version: 1.0.0
 * Author: AymeeTech
 */

if (!defined('ABSPATH')) exit;

class AymeeTech_Custom_Styles {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles'], 999);
        add_action('wp_head', [$this, 'inline_critical_css'], 5);
        add_action('wp_footer', [$this, 'scroll_animations_script']);
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'aymeetech-custom',
            content_url('/mu-plugins/assets/aymeetech-custom.css'),
            [],
            '1.0.0'
        );
    }

    public function inline_critical_css() {
        ?>
        <style id="aymeetech-critical">
            /* Prevent layout shift */
            .rev_slider_wrapper { min-height: 400px; }
            body { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        </style>
        <?php
    }

    public function scroll_animations_script() {
        ?>
        <script id="aymeetech-animations">
        (function() {
            // Intersection Observer for scroll animations
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('ayt-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            // Observe elements
            document.addEventListener('DOMContentLoaded', function() {
                var elements = document.querySelectorAll(
                    '.wgl_module_team, .seofy_module_counter, .wgl_module_testimonial, ' +
                    '.seofy_module_blog, .wgl-services-5, .wgl-portfolio, ' +
                    '.wgl_module_team_2, .vc_row, .wpb_column, ' +
                    '.ayt-animate'
                );
                elements.forEach(function(el) {
                    el.classList.add('ayt-animate-init');
                    observer.observe(el);
                });

                // Smooth scroll for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                    anchor.addEventListener('click', function(e) {
                        var target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            });
        })();
        </script>
        <?php
    }
}

new AymeeTech_Custom_Styles();
