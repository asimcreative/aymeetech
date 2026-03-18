<?php
/**
 * Plugin Name: AymeeTech Blog Enhancements
 * Description: Table of Contents, reading time, and breadcrumb schema for blog posts
 * Version: 1.0.0
 * Author: AymeeTech
 */

if (!defined('ABSPATH')) exit;

class AymeeTech_Blog_Enhancements {

    public function __construct() {
        add_filter('the_content', [$this, 'add_toc_and_reading_time'], 5);
        add_action('wp_head', [$this, 'add_toc_styles']);
        add_action('wp_footer', [$this, 'add_toc_scripts']);
    }

    /**
     * Add Table of Contents + Reading Time to single blog posts
     */
    public function add_toc_and_reading_time($content) {
        if (!is_singular('post') || is_admin()) {
            return $content;
        }

        // Calculate reading time
        $word_count = str_word_count(strip_tags($content));
        $reading_time = max(1, ceil($word_count / 230));

        // Extract headings for TOC
        preg_match_all('/<h([23])[^>]*>(.*?)<\/h[23]>/i', $content, $matches, PREG_SET_ORDER);

        if (count($matches) < 3) {
            // Not enough headings for a TOC, just add reading time
            $reading_badge = '<div class="at-reading-time"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ' . $reading_time . ' min read</div>';
            return $reading_badge . $content;
        }

        // Build TOC
        $toc_items = [];
        $counter = 0;
        foreach ($matches as $match) {
            $level = $match[1];
            $text = strip_tags($match[2]);
            $id = 'section-' . (++$counter);
            $toc_items[] = [
                'level' => $level,
                'text' => $text,
                'id' => $id,
            ];

            // Add ID to the heading in content
            $old_tag = $match[0];
            $new_tag = '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>';
            $content = str_replace($old_tag, $new_tag, $content);
        }

        // Reading time badge
        $reading_badge = '<div class="at-reading-time"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ' . $reading_time . ' min read</div>';

        // Build TOC HTML
        $toc_html = '<div class="at-toc-wrapper">';
        $toc_html .= '<div class="at-toc-header" role="button" tabindex="0">';
        $toc_html .= '<span class="at-toc-title">Table of Contents</span>';
        $toc_html .= '<span class="at-toc-toggle">−</span>';
        $toc_html .= '</div>';
        $toc_html .= '<nav class="at-toc-nav" aria-label="Table of Contents"><ul>';

        foreach ($toc_items as $item) {
            $indent = $item['level'] === '3' ? ' class="at-toc-sub"' : '';
            $toc_html .= '<li' . $indent . '><a href="#' . $item['id'] . '">' . esc_html($item['text']) . '</a></li>';
        }

        $toc_html .= '</ul></nav></div>';

        return $reading_badge . $toc_html . $content;
    }

    /**
     * TOC + Reading Time Styles
     */
    public function add_toc_styles() {
        if (!is_singular('post')) return;
        ?>
        <style>
        .at-reading-time{display:inline-flex;align-items:center;gap:6px;background:#f0f2ff;color:#4054b2;padding:6px 14px;border-radius:20px;font-size:14px;font-weight:500;margin-bottom:20px}
        .at-reading-time svg{flex-shrink:0}
        .at-toc-wrapper{background:#f8f9fc;border:1px solid #e2e5f1;border-radius:8px;padding:0;margin:0 0 30px;overflow:hidden}
        .at-toc-header{display:flex;justify-content:space-between;align-items:center;padding:14px 20px;cursor:pointer;user-select:none;background:#eef0f8}
        .at-toc-header:hover{background:#e2e5f1}
        .at-toc-title{font-size:16px;font-weight:600;color:#2c3e50}
        .at-toc-toggle{font-size:20px;font-weight:700;color:#4054b2;line-height:1;width:24px;text-align:center}
        .at-toc-nav{padding:10px 20px 16px}
        .at-toc-nav.at-collapsed{display:none}
        .at-toc-nav ul{list-style:none;margin:0;padding:0}
        .at-toc-nav li{margin:0;padding:0}
        .at-toc-nav li a{display:block;padding:6px 0;color:#4054b2;text-decoration:none;font-size:14px;border-bottom:1px solid #eef0f8;transition:color .2s,padding-left .2s}
        .at-toc-nav li a:hover{color:#2c3e50;padding-left:6px}
        .at-toc-nav li:last-child a{border-bottom:none}
        .at-toc-nav li.at-toc-sub a{padding-left:20px;font-size:13px;color:#666}
        .at-toc-nav li.at-toc-sub a:hover{padding-left:26px;color:#4054b2}
        </style>
        <?php
    }

    /**
     * TOC toggle + smooth scroll
     */
    public function add_toc_scripts() {
        if (!is_singular('post')) return;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded',function(){
            var header=document.querySelector('.at-toc-header');
            if(!header)return;
            var nav=document.querySelector('.at-toc-nav');
            var toggle=document.querySelector('.at-toc-toggle');
            header.addEventListener('click',function(){
                nav.classList.toggle('at-collapsed');
                toggle.textContent=nav.classList.contains('at-collapsed')?'+':'−';
            });
            document.querySelectorAll('.at-toc-nav a').forEach(function(a){
                a.addEventListener('click',function(e){
                    e.preventDefault();
                    var target=document.querySelector(this.getAttribute('href'));
                    if(target){
                        var offset=target.getBoundingClientRect().top+window.pageYOffset-80;
                        window.scrollTo({top:offset,behavior:'smooth'});
                    }
                });
            });
        });
        </script>
        <?php
    }
}

new AymeeTech_Blog_Enhancements();
