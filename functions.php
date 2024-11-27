<?php

/*----------------------------------------------------
SHORTHAND CONSTANTS FOR THEME VERSION
-----------------------------------------------------*/
if ( site_url() === 'http://localhost:8080/development' ) {
    define( 'AROLAX_VERSION', time() );
} else {
    define( 'AROLAX_VERSION', 2.0 );
}

/*----------------------------------------------------
SHORTHAND CONSTANTS FOR THEME ASSETS URL
-----------------------------------------------------*/
define( 'AROLAX_THEME_URI', get_template_directory_uri() );
define( 'AROLAX_ASSETS', AROLAX_THEME_URI . '/assets/' );
define( 'AROLAX_IMG', AROLAX_THEME_URI . '/assets/imgs' );
define( 'AROLAX_CSS', AROLAX_THEME_URI . '/assets/css' );
define( 'AROLAX_JS', AROLAX_THEME_URI . '/assets/js' );

/*----------------------------------------------------
SHORTHAND CONSTANTS FOR THEME ASSETS DIRECTORY PATH
-----------------------------------------------------*/
define( 'AROLAX_THEME_DIR', get_template_directory() );
define( 'AROLAX_IMG_DIR', AROLAX_THEME_DIR . '/assets/imgs' );
define( 'AROLAX_CSS_DIR', AROLAX_THEME_DIR . '/assets/css' );
define( 'AROLAX_JS_DIR', AROLAX_THEME_DIR . '/assets/js' );

/*----------------------------------------------------
LOAD Classes
-----------------------------------------------------*/
if ( file_exists( dirname( __FILE__ ) . '/app/loader.php' ) ) {
    require_once dirname( __FILE__ ) . '/app/loader.php';    
}

/*----------------------------------------------------
SET UP THE CONTENT WIDTH VALUE BASED ON THE THEME'S DESIGN
-----------------------------------------------------*/
if ( !isset( $content_width ) ) {
    $content_width = 800;
}

add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

// Woocommerce Supports
function arolex_add_woocommerce_support() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 350,
        'single_image_width'    => 350,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ));

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'arolex_add_woocommerce_support' );

add_action('init', function() {  
    if (isset($_COOKIE['_ga_8GK6634RBN'])) {  
        setcookie('_ga_8GK6634RBN', $_COOKIE['_ga_8GK6634RBN'], [  
            'expires' => time() + 3600, // Set an appropriate expiration time  
            'path' => '/',  
            'domain' => $_SERVER['HTTP_HOST'],  
            'secure' => true, // Set to true if using HTTPS  
            'httponly' => false, // Set to true to prevent JavaScript access  
            'samesite' => 'None' // Important: Set the SameSite attribute  
        ]);  
    }  
});

add_action('init', function () {  
    $container_id = 'G-8GK6634RBN';  

    if (isset($_COOKIE['_ga'])) {  
        setcookie('_ga', $_COOKIE['_ga'], [  
            'expires' => time() + 63072000, // 2 years  
            'path' => '/',  
            'domain' => $_SERVER['HTTP_HOST'],  
            'secure' => true, // Only if your site uses HTTPS  
            'httponly' => false,  
            'samesite' => 'None'  
        ]);  
    }  

    if (isset($_COOKIE['_gat_' . $container_id])) {  
        setcookie('_gat_' . $container_id, $_COOKIE['_gat_' . $container_id], [  
            'expires' => time() + 600, // 10 minutes  
            'path' => '/',  
            'domain' => $_SERVER['HTTP_HOST'],  
            'secure' => true,  
            'httponly' => false,  
            'samesite' => 'None'  
        ]);  
    }  

    if (isset($_COOKIE['_gid'])) {  
        setcookie('_gid', $_COOKIE['_gid'], [  
            'expires' => time() + 86400, // 1 day  
            'path' => '/',  
            'domain' => $_SERVER['HTTP_HOST'],  
            'secure' => true,  
            'httponly' => false,  
            'samesite' => 'None'  
        ]);  
    }  

    if (isset($_COOKIE['_gtag_' . $container_id])) {  
        setcookie('_gtag_' . $container_id, $_COOKIE['_gtag_' . $container_id], [  
            'expires' => time() + 63072000, // 2 years  
            'path' => '/',  
            'domain' => $_SERVER['HTTP_HOST'],  
            'secure' => true,  
            'httponly' => false,  
            'samesite' => 'None'  
        ]);  
    }  
});

function modern_mixitup_loader() {
    // Deregister the original mixitup script
    wp_deregister_script('mixitup');
    
    // Define the paths for modern and legacy scripts
    $modern_script_url = get_template_directory_uri() . '/assets/js/mixitup.modern.min.js';
    $legacy_script_url = get_template_directory_uri() . '/assets/js/mixitup.legacy.min.js';
    
    // Add modern version with type="module"
    wp_enqueue_script(
        'mixitup-modern',
        $modern_script_url,
        array(),
        '3.3.1',
        true
    );
    
    // Add legacy version with nomodule
    wp_enqueue_script(
        'mixitup-legacy',
        $legacy_script_url,
        array(),
        '3.3.1',
        true
    );
    
    // Modify script tags
    add_filter('script_loader_tag', function($tag, $handle, $src) {
        if ($handle === 'mixitup-modern') {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        if ($handle === 'mixitup-legacy') {
            return '<script nomodule src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }, 10, 3);
}
add_action('wp_enqueue_scripts', 'modern_mixitup_loader');

function custom_enqueue_google_fonts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap', false);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_google_fonts');

function my_custom_scripts() {  
    wp_enqueue_script('swiper-js', 'https://jbconcept.ro/wp-content/plugins/elementor/assets/lib/swiper/v8/swiper.min.js', array(), '8.4.5', true);  
}  
add_action('wp_enqueue_scripts', 'my_custom_scripts');

// Optimize jQuery Loading
function optimize_jquery() {
    if (!is_admin()) {
        // Deregister the default jQuery
        wp_deregister_script('jquery');

        // Register jQuery from a CDN with defer attribute
        wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), null, true);
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'optimize_jquery');

function disable_scroll_to_top() {
    // Dequeue the script if it's enqueued
    wp_dequeue_script('scroll-to-top'); // Replace 'scroll-to-top' with the actual handle of the script

    // Add inline CSS to hide the scroll-to-top element
    echo '<style>
        .wcf-scroll-to-top { display: none !important; }
    </style>';
}
add_action('wp_enqueue_scripts', 'disable_scroll_to_top', 20);