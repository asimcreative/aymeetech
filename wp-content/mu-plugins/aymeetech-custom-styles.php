<?php
/**
 * Plugin Name: AymeeTech Custom Styles & UI Enhancements
 * Description: Modern UI improvements, animations, and visual enhancements
 * Version: 2.0.0
 * Author: AymeeTech
 */

if (!defined('ABSPATH')) exit;

class AymeeTech_Custom_Styles {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles'], 999);
        add_action('wp_head', [$this, 'inline_critical_css'], 5);
        add_action('wp_footer', [$this, 'scroll_animations_script']);
        add_action('wp_footer', [$this, 'whatsapp_button']);
        add_action('wp_footer', [$this, 'faq_toggle_script']);
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'aymeetech-custom',
            content_url('/mu-plugins/assets/aymeetech-custom.css'),
            [],
            '2.0.0'
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

    public function whatsapp_button() {
        if (is_admin()) return;
        ?>
        <a href="https://wa.me/923172370470" target="_blank" class="ayt-whatsapp-float" aria-label="Chat on WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16.004 0h-.008C7.174 0 0 7.176 0 16c0 3.5 1.134 6.74 3.058 9.372L1.058 31.13l5.97-1.966A15.89 15.89 0 0016.004 32C24.826 32 32 24.822 32 16S24.826 0 16.004 0zm9.334 22.608c-.39 1.1-1.932 2.014-3.182 2.28-.854.18-1.968.324-5.72-1.23-4.802-1.988-7.892-6.856-8.132-7.174-.23-.318-1.932-2.572-1.932-4.904s1.222-3.476 1.656-3.952c.434-.476.948-.596 1.264-.596.316 0 .632.002.908.016.29.016.682-.112 1.068.814.39.944 1.332 3.276 1.45 3.514.118.238.196.516.04.832-.158.318-.236.516-.474.792-.238.278-.5.62-.714.832-.238.236-.486.494-.208.968.278.474 1.234 2.036 2.65 3.3 1.822 1.626 3.358 2.13 3.832 2.368.474.238.752.198 1.028-.12.278-.316 1.186-1.382 1.504-1.858.316-.476.634-.396 1.068-.238.434.158 2.766 1.304 3.24 1.542.474.238.79.356.908.554.118.198.118 1.148-.272 2.248z"/></svg>
            <span class="ayt-wa-tooltip">Chat with us!</span>
        </a>
        <?php
    }

    public function faq_toggle_script() {
        ?>
        <script id="aymeetech-faq">
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.ayt-faq-question').forEach(function(q) {
                    q.addEventListener('click', function() {
                        var item = this.closest('.ayt-faq-item');
                        var wasActive = item.classList.contains('active');
                        // Close all
                        document.querySelectorAll('.ayt-faq-item.active').forEach(function(i) {
                            i.classList.remove('active');
                        });
                        // Toggle clicked
                        if (!wasActive) item.classList.add('active');
                    });
                });
            });
        })();
        </script>
        <?php
    }
}

new AymeeTech_Custom_Styles();
