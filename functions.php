<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MINSITHU_VERSION', '3.4' );

/* =============================================
   THEME SETUP
   ============================================= */
function minsithu_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'site-icon' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );


    add_image_size( 'minsithu-hero',  800, 450, true );
    add_image_size( 'minsithu-card',  400, 225, true );
    add_image_size( 'minsithu-thumb', 120, 80,  true );
    add_image_size( 'minsithu-small', 70,  50,  true );

    register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'minsithu-magazine' ),
        'footer'  => __( 'Footer Navigation',  'minsithu-magazine' ),
    ) );
}
add_action( 'after_setup_theme', 'minsithu_setup' );

/* =============================================
   ENQUEUE ASSETS
   ============================================= */
function minsithu_enqueue() {
    wp_enqueue_style( 'minsithu-style', get_stylesheet_uri(), array(), MINSITHU_VERSION );
    wp_enqueue_script( 'minsithu-js', get_template_directory_uri() . '/assets/js/main.js', array(), MINSITHU_VERSION, true );

    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_localize_script( 'minsithu-js', 'minsithuData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'minsithu_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'minsithu_enqueue' );

function minsithu_defer_theme_script( $tag, $handle, $src ) {
    if ( 'minsithu-js' !== $handle ) {
        return $tag;
    }
    return '<script src="' . esc_url( $src ) . '" defer></script>';
}
add_filter( 'script_loader_tag', 'minsithu_defer_theme_script', 10, 3 );

function minsithu_resource_hints( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        $urls[] = 'https://fonts.gstatic.com';
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'minsithu_resource_hints', 10, 2 );

/* =============================================
   WIDGETS
   ============================================= */
function minsithu_widgets_init() {
    $defaults = array(
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    );

    register_sidebar( array_merge( $defaults, array(
        'name' => __( 'Main Sidebar', 'minsithu-magazine' ),
        'id'   => 'sidebar-main',
    ) ) );

    register_sidebar( array_merge( $defaults, array(
        'name' => __( 'Footer Column 2', 'minsithu-magazine' ),
        'id'   => 'footer-col-2',
    ) ) );

    register_sidebar( array_merge( $defaults, array(
        'name' => __( 'Footer Column 3', 'minsithu-magazine' ),
        'id'   => 'footer-col-3',
    ) ) );

    register_sidebar( array_merge( $defaults, array(
        'name' => __( 'Footer Column 4', 'minsithu-magazine' ),
        'id'   => 'footer-col-4',
    ) ) );
}
add_action( 'widgets_init', 'minsithu_widgets_init' );

/* =============================================
   READING TIME
   ============================================= */
function minsithu_reading_time( $post_id = null ) {
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $minutes    = max( 1, ceil( $word_count / 200 ) );
    return $minutes . ' min read';
}

/* =============================================
   EXCERPT
   ============================================= */
function minsithu_excerpt_length( $length ) { return 18; }
add_filter( 'excerpt_length', 'minsithu_excerpt_length' );

function minsithu_excerpt_more( $more ) { return '…'; }
add_filter( 'excerpt_more', 'minsithu_excerpt_more' );

/* =============================================
   CUSTOM CATEGORY COLOR (Customizer)
   ============================================= */
function minsithu_customizer( $wp_customize ) {
    $wp_customize->add_section( 'minsithu_options', array(
        'title'    => __( 'Min SiThu Theme', 'minsithu-magazine' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'breaking_news_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'breaking_news_text', array(
        'label'   => __( 'Breaking News Ticker Text (use | to separate items)', 'minsithu-magazine' ),
        'section' => 'minsithu_options',
        'type'    => 'textarea',
    ) );

    foreach ( array( 'github' => 'GitHub URL', 'facebook' => 'Facebook URL', 'medium' => 'Medium URL', 'linkedin' => 'LinkedIn URL', 'slack' => 'Slack URL', 'twitter' => 'Twitter/X URL', 'youtube' => 'YouTube URL' ) as $key => $label ) {
        $wp_customize->add_setting( "social_{$key}", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( "social_{$key}", array( 'label' => $label, 'section' => 'minsithu_options', 'type' => 'url' ) );
    }

    $wp_customize->add_setting( 'about_bio', array( 'default' => 'I am working as a Software Engineer · Building clean, secure web experiences · Open Source Contributor · Full-Stack Web Engineer', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'about_bio', array( 'label' => 'About Bio (sidebar)', 'section' => 'minsithu_options', 'type' => 'textarea' ) );
}
add_action( 'customize_register', 'minsithu_customizer' );

/* =============================================
   HELPER: Category badge HTML
   ============================================= */
function minsithu_category_badge( $post_id = null ) {
    $cats = get_the_category( $post_id );
    if ( empty( $cats ) ) return '';
    $cat = $cats[0];
    return '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="category-badge">' . esc_html( $cat->name ) . '</a>';
}

/* =============================================
   HELPER: Social links array
   ============================================= */
function minsithu_social_links() {
    return array(
        'github'   => array( 'url' => get_theme_mod( 'social_github',   'https://github.com/itsmeminsithu' ),        'icon' => 'github',   'label' => 'GitHub' ),
        'facebook' => array( 'url' => get_theme_mod( 'social_facebook', 'https://facebook.com/itsmeminsithu' ),      'icon' => 'facebook', 'label' => 'Facebook' ),
        'medium'   => array( 'url' => get_theme_mod( 'social_medium',   'https://medium.com/@itsmeminsithu' ),       'icon' => 'medium',   'label' => 'Medium' ),
        'linkedin' => array( 'url' => get_theme_mod( 'social_linkedin', 'https://www.linkedin.com/in/itsmeminsithu' ), 'icon' => 'linkedin', 'label' => 'LinkedIn' ),
        'slack'    => array( 'url' => get_theme_mod( 'social_slack',    '' ),                                      'icon' => 'slack',    'label' => 'Slack' ),
        'twitter'  => array( 'url' => get_theme_mod( 'social_twitter',  '' ),                                      'icon' => 'x',        'label' => 'X' ),
        'youtube'  => array( 'url' => get_theme_mod( 'social_youtube',  '' ),                                      'icon' => 'youtube',  'label' => 'YouTube' ),
    );
}

function minsithu_social_icon( $icon ) {
    $icons = array(
        'github'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.1c-3.3.7-4-1.4-4-1.4-.6-1.4-1.4-1.8-1.4-1.8-1.1-.8.1-.8.1-.8 1.2.1 1.9 1.3 1.9 1.3 1.1 1.9 2.9 1.3 3.5 1 .1-.8.4-1.3.8-1.6-2.6-.3-5.4-1.3-5.4-5.9 0-1.3.5-2.4 1.2-3.2-.1-.3-.5-1.6.1-3.2 0 0 1-.3 3.3 1.2a11.2 11.2 0 0 1 6 0C17.1 4.7 18.1 5 18.1 5c.6 1.6.2 2.9.1 3.2.8.8 1.2 1.9 1.2 3.2 0 4.6-2.8 5.6-5.4 5.9.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6A12 12 0 0 0 12 .5Z"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9.5h4v12H3v-12Zm6.2 0H13v1.7h.1c.5-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6v6.3h-4v-5.6c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9v5.7h-4V9.5Z"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.5V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.5 2.9h-2.3v7A10 10 0 0 0 22 12Z"/></svg>',
        'medium'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 12c0 3.6-2.9 6.5-6.5 6.5S.5 15.6.5 12 3.4 5.5 7 5.5s6.5 2.9 6.5 6.5Zm7.1 0c0 3.4-1.5 6.1-3.4 6.1s-3.4-2.7-3.4-6.1 1.5-6.1 3.4-6.1 3.4 2.7 3.4 6.1Zm3.1 0c0 3-.5 5.4-1.2 5.4s-1.2-2.4-1.2-5.4.5-5.4 1.2-5.4 1.2 2.4 1.2 5.4Z"/></svg>',
        'slack'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 14a2 2 0 1 1-2-2h2v2Zm1 0a2 2 0 0 1 4 0v5a2 2 0 1 1-4 0v-5Zm2-8a2 2 0 1 1 2-2v2H9Zm0 1a2 2 0 0 1 0 4H4a2 2 0 1 1 0-4h5Zm9 3a2 2 0 1 1 2 2h-2v-2Zm-1 0a2 2 0 0 1-4 0V5a2 2 0 1 1 4 0v5Zm-2 8a2 2 0 1 1-2 2v-2h2Zm0-1a2 2 0 0 1 0-4h5a2 2 0 1 1 0 4h-5Z"/></svg>',
        'x'        => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3L12 14.7 6.4 22H3.3l7.3-8.4L2.8 2h6.5l4.4 6.6L18.9 2Zm-1.1 18h1.7L8.4 3.9H6.6L17.8 20Z"/></svg>',
        'youtube'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 6.2s-.2-1.7-.9-2.4c-.9-.9-1.9-.9-2.4-1C16.9 2.5 12 2.5 12 2.5s-4.9 0-8.2.3c-.5.1-1.5.1-2.4 1C.7 4.5.5 6.2.5 6.2S.2 8.2.2 10.2v1.9c0 2 .3 4 .3 4s.2 1.7.9 2.4c.9.9 2.1.9 2.6 1 1.9.2 8 .3 8 .3s4.9 0 8.2-.3c.5-.1 1.5-.1 2.4-1 .7-.7.9-2.4.9-2.4s.3-2 .3-4v-1.9c0-2-.3-4-.3-4ZM9.7 14.6V7.7l6.3 3.5-6.3 3.4Z"/></svg>',
    );

    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

function minsithu_coder_icon() {
    return '<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><rect x="14" y="18" width="36" height="26" rx="5"/><path d="M24 29l-6 5 6 5M40 29l6 5-6 5M35 26l-6 16M24 52h16M32 44v8"/></svg>';
}


function minsithu_placeholder_variants() {
    return array(
        array( 'emoji' => '🤓', 'quote' => 'This article forgot its selfie.' ),
        array( 'emoji' => '😄', 'quote' => 'No photo today — only pure content.' ),
        array( 'emoji' => '🚀', 'quote' => 'Big ideas do not always need big images.' ),
        array( 'emoji' => '✨', 'quote' => 'Story mode: ON. Photo mode: OFF.' ),
        array( 'emoji' => '☕', 'quote' => 'Grab a coffee and enjoy the read.' ),
        array( 'emoji' => '💡', 'quote' => 'The headline is the hero this time.' ),
        array( 'emoji' => '🧠', 'quote' => 'Smart story, minimalist cover.' ),
        array( 'emoji' => '😎', 'quote' => 'No featured image? Still looking good.' ),
    );
}

function minsithu_placeholder_image_url( $post_id = null, $size = 'card' ) {
    $sizes = array(
        'hero'  => array( 800, 450 ),
        'card'  => array( 400, 225 ),
        'thumb' => array( 120, 80 ),
        'small' => array( 70, 50 ),
    );

    if ( ! isset( $sizes[ $size ] ) ) {
        $size = 'card';
    }

    list( $width, $height ) = $sizes[ $size ];
    $variants   = minsithu_placeholder_variants();
    $index      = $post_id ? absint( $post_id ) % count( $variants ) : 0;
    $choice     = $variants[ $index ];
    $emoji      = $choice['emoji'];
    $quote      = $choice['quote'];
    $site_name  = get_bloginfo( 'name' );
    $category   = '';
    $cats       = get_the_category( $post_id );

    if ( ! empty( $cats ) ) {
        $category = $cats[0]->name;
    }

    $font_stack = '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, Pyidaungsu, Myanmar3, Noto Sans Myanmar, sans-serif';
    $title      = __( 'Featured image is loading…', 'minsithu-magazine' );
    $subtitle   = $category ? $category : $site_name;

    if ( 'small' === $size ) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">' .
            '<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#F8FBFF"/><stop offset="100%" stop-color="#E7F3FF"/></linearGradient></defs>' .
            '<rect width="100%" height="100%" rx="8" fill="url(#bg)"/>' .
            '<text x="50%" y="57%" text-anchor="middle" font-size="28" font-family="Apple Color Emoji, Segoe UI Emoji, Noto Color Emoji, sans-serif">' . esc_html( $emoji ) . '</text>' .
            '</svg>';
    } elseif ( 'thumb' === $size ) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">' .
            '<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#F8FBFF"/><stop offset="100%" stop-color="#E7F3FF"/></linearGradient></defs>' .
            '<rect width="100%" height="100%" rx="10" fill="url(#bg)"/>' .
            '<rect x="1" y="1" width="' . ( $width - 2 ) . '" height="' . ( $height - 2 ) . '" rx="10" fill="none" stroke="#BFD7FF"/>' .
            '<text x="12" y="26" font-size="12" font-weight="700" fill="#1877F2" font-family="' . $font_stack . '">NO PHOTO</text>' .
            '<text x="12" y="52" font-size="22" font-family="Apple Color Emoji, Segoe UI Emoji, Noto Color Emoji, sans-serif">' . esc_html( $emoji ) . '</text>' .
            '</svg>';
    } else {
        $big_emoji = 'hero' === $size ? 72 : 52;
        $title_y   = 'hero' === $size ? 152 : 94;
        $quote_y   = 'hero' === $size ? 220 : 142;
        $sub_y     = 'hero' === $size ? 265 : 176;
        $label_w   = 'hero' === $size ? 188 : 150;
        $label_h   = 'hero' === $size ? 34 : 28;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">' .
            '<defs>' .
            '<linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#F8FBFF"/><stop offset="100%" stop-color="#E7F3FF"/></linearGradient>' .
            '<linearGradient id="panel" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.96"/><stop offset="100%" stop-color="#F7FAFF" stop-opacity="0.9"/></linearGradient>' .
            '</defs>' .
            '<rect width="100%" height="100%" rx="24" fill="url(#bg)"/>' .
            '<circle cx="' . ( $width - 70 ) . '" cy="50" r="42" fill="#D8E8FF"/>' .
            '<circle cx="70" cy="' . ( $height - 58 ) . '" r="56" fill="#DCEBFF"/>' .
            '<rect x="36" y="28" width="' . $label_w . '" height="' . $label_h . '" rx="' . ( $label_h / 2 ) . '" fill="#1877F2"/>' .
            '<text x="' . ( 36 + ( $label_w / 2 ) ) . '" y="' . ( 28 + ( $label_h / 2 ) + 5 ) . '" text-anchor="middle" font-size="13" font-weight="700" letter-spacing="1" fill="#FFFFFF" font-family="' . $font_stack . '">AUTO FEATURED</text>' .
            '<rect x="36" y="76" width="' . ( $width - 72 ) . '" height="' . ( $height - 112 ) . '" rx="24" fill="url(#panel)" stroke="#BFD7FF"/>' .
            '<text x="50%" y="' . $title_y . '" text-anchor="middle" font-size="' . $big_emoji . '" font-family="Apple Color Emoji, Segoe UI Emoji, Noto Color Emoji, sans-serif">' . esc_html( $emoji ) . '</text>' .
            '<text x="50%" y="' . ( $title_y + 38 ) . '" text-anchor="middle" font-size="' . ( 'hero' === $size ? 28 : 18 ) . '" font-weight="700" fill="#102A43" font-family="' . $font_stack . '">' . esc_html( $title ) . '</text>' .
            '<text x="50%" y="' . $quote_y . '" text-anchor="middle" font-size="' . ( 'hero' === $size ? 22 : 14 ) . '" fill="#425466" font-family="' . $font_stack . '">“' . esc_html( $quote ) . '”</text>' .
            '<text x="50%" y="' . $sub_y . '" text-anchor="middle" font-size="' . ( 'hero' === $size ? 15 : 12 ) . '" font-weight="600" fill="#1877F2" font-family="' . $font_stack . '">' . esc_html( $subtitle ) . '</text>' .
            '</svg>';
    }

    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode( $svg );
}

function minsithu_placeholder_image_tag( $post_id = null, $size = 'card', $class = '', $loading = 'lazy' ) {
    $class = trim( $class . ' minsithu-placeholder-image' );
    $alt   = sprintf( __( 'Auto-generated placeholder image for %s', 'minsithu-magazine' ), get_the_title( $post_id ) ? get_the_title( $post_id ) : get_bloginfo( 'name' ) );

    return '<img src="' . esc_url( minsithu_placeholder_image_url( $post_id, $size ) ) . '" alt="' . esc_attr( $alt ) . '" class="' . esc_attr( $class ) . '" loading="' . esc_attr( $loading ) . '" decoding="async">';
}


function minsithu_current_url() {
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
    $request_uri = '/' . ltrim( $request_uri, '/' );
    return home_url( $request_uri );
}

function minsithu_social_preview_image_url( $post_id = 0 ) {
    if ( $post_id && has_post_thumbnail( $post_id ) ) {
        $image = get_the_post_thumbnail_url( $post_id, 'full' );
        if ( $image ) {
            return $image;
        }
    }

    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $logo ) {
            return $logo;
        }
    }

    $site_icon_id = get_option( 'site_icon' );
    if ( $site_icon_id ) {
        $site_icon = wp_get_attachment_image_url( $site_icon_id, 'full' );
        if ( $site_icon ) {
            return $site_icon;
        }
    }

    return get_template_directory_uri() . '/assets/images/social-preview-default.png';
}

function minsithu_social_preview_image_dimensions( $image_url ) {
    $default = array(
        'width'  => 1200,
        'height' => 630,
        'type'   => 'image/png',
    );

    $uploads = wp_get_upload_dir();
    if ( ! empty( $uploads['baseurl'] ) && ! empty( $uploads['basedir'] ) && 0 === strpos( $image_url, $uploads['baseurl'] ) ) {
        $file = str_replace( $uploads['baseurl'], $uploads['basedir'], $image_url );
        $meta = @getimagesize( $file );
        if ( $meta ) {
            return array(
                'width'  => isset( $meta[0] ) ? absint( $meta[0] ) : $default['width'],
                'height' => isset( $meta[1] ) ? absint( $meta[1] ) : $default['height'],
                'type'   => isset( $meta['mime'] ) ? $meta['mime'] : $default['type'],
            );
        }
    }

    return $default;
}

function minsithu_meta_description() {
    if ( is_singular() ) {
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ), 28 );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $description = term_description() ? wp_strip_all_tags( term_description() ) : single_term_title( '', false );
    } else {
        $description = get_bloginfo( 'description' );
    }

    return trim( wp_strip_all_tags( $description ) );
}

function minsithu_seo_head() {
    if ( is_admin() ) {
        return;
    }

    $description = minsithu_meta_description();
    $title       = wp_get_document_title();
    $canonical   = is_singular() ? get_permalink() : minsithu_current_url();
    $post_id     = is_singular() ? get_queried_object_id() : 0;
    $image       = minsithu_social_preview_image_url( $post_id );
    $image_meta  = minsithu_social_preview_image_dimensions( $image );
    $site_name   = get_bloginfo( 'name' );
    $locale      = str_replace( '_', '-', get_locale() );

    if ( $description ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "
";
    }

    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "
";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "
";
    echo '<meta property="og:locale" content="' . esc_attr( $locale ) . '">' . "
";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "
";
    if ( $description ) {
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "
";
    }
    echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "
";
    echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . '">' . "
";
    echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "
";
    echo '<meta property="og:image:secure_url" content="' . esc_url( set_url_scheme( $image, 'https' ) ) . '">' . "
";
    echo '<meta property="og:image:width" content="' . esc_attr( $image_meta['width'] ) . '">' . "
";
    echo '<meta property="og:image:height" content="' . esc_attr( $image_meta['height'] ) . '">' . "
";
    echo '<meta property="og:image:type" content="' . esc_attr( $image_meta['type'] ) . '">' . "
";
    echo '<meta property="og:image:alt" content="' . esc_attr( $title ) . '">' . "
";

    echo '<meta name="twitter:card" content="summary_large_image">' . "
";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "
";
    if ( $description ) {
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "
";
    }
    echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "
";
    echo '<meta name="twitter:image:alt" content="' . esc_attr( $title ) . '">' . "
";
    $twitter_site = trim( (string) get_theme_mod( 'social_twitter', '' ) );
    if ( $twitter_site && preg_match( '#(?:x|twitter)\.com/([A-Za-z0-9_]+)#i', $twitter_site, $matches ) ) {
        echo '<meta name="twitter:site" content="@' . esc_attr( $matches[1] ) . '">' . "
";
        echo '<meta name="twitter:creator" content="@' . esc_attr( $matches[1] ) . '">' . "
";
    }

    if ( is_singular() ) {
        echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( DATE_W3C, $post_id ) ) . '">' . "
";
        echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( DATE_W3C, $post_id ) ) . '">' . "
";

        $schema = array(
            '@context'         => 'https://schema.org',
            '@type'            => is_single() ? 'NewsArticle' : 'BlogPosting',
            'headline'         => get_the_title( $post_id ),
            'description'      => $description,
            'datePublished'    => get_the_date( DATE_W3C, $post_id ),
            'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
            'author'           => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ) ),
            'publisher'        => array( '@type' => 'Organization', 'name' => $site_name ),
            'mainEntityOfPage' => get_permalink( $post_id ),
            'image'            => array( $image ),
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "
";
    }
}
add_action( 'wp_head', 'minsithu_seo_head', 2 );

function minsithu_image_performance_attributes( $attr, $attachment, $size ) {
    $attr['decoding'] = 'async';
    if ( empty( $attr['alt'] ) ) {
        $attr['alt'] = get_the_title( $attachment->ID );
    }
    if ( is_singular() && 'minsithu-hero' === $size ) {
        $attr['fetchpriority'] = 'high';
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'minsithu_image_performance_attributes', 10, 3 );

/* =============================================
   NAV WALKER — adds dropdown support
   ============================================= */
class Minsithu_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="sub-menu">';
    }
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }
}

/* =============================================
   SECURITY HARDENING — headers, comments, safe SVG disabled
   ============================================= */
function minsithu_security_headers() {
    if ( is_admin() || headers_sent() ) {
        return;
    }

    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()' );

    $csp = array(
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline'",
        "style-src 'self' 'unsafe-inline'",
        "font-src 'self' data:",
        "img-src 'self' data: https: blob:",
        "connect-src 'self'",
        "frame-ancestors 'self'",
        "base-uri 'self'",
        "form-action 'self'",
    );
    header( 'Content-Security-Policy: ' . implode( '; ', $csp ) );
}
add_action( 'send_headers', 'minsithu_security_headers' );

function minsithu_comment_nonce_field() {
    wp_nonce_field( 'minsithu_comment_nonce_action', 'minsithu_comment_nonce' );
    echo '<p class="minsithu-hp-wrap" aria-hidden="true"><label for="minsithu_hp">Leave this field empty</label><input type="text" id="minsithu_hp" name="minsithu_hp" tabindex="-1" autocomplete="off"></p>';
}
add_action( 'comment_form_after_fields', 'minsithu_comment_nonce_field' );
add_action( 'comment_form_logged_in_after', 'minsithu_comment_nonce_field' );

function minsithu_verify_comment_security( $commentdata ) {
    $nonce = isset( $_POST['minsithu_comment_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['minsithu_comment_nonce'] ) ) : '';
    if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'minsithu_comment_nonce_action' ) ) {
        wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'minsithu-magazine' ), '', array( 'response' => 403 ) );
    }

    $honeypot = isset( $_POST['minsithu_hp'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['minsithu_hp'] ) ) ) : '';
    if ( '' !== $honeypot ) {
        wp_die( esc_html__( 'Spam protection triggered.', 'minsithu-magazine' ), '', array( 'response' => 403 ) );
    }

    if ( isset( $commentdata['comment_content'] ) ) {
        $commentdata['comment_content'] = wp_kses_post( $commentdata['comment_content'] );
    }

    return $commentdata;
}
add_filter( 'preprocess_comment', 'minsithu_verify_comment_security' );

function minsithu_secure_search_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        $s = $query->get( 's' );
        if ( is_string( $s ) ) {
            $query->set( 's', sanitize_text_field( $s ) );
        }
    }
}
add_action( 'pre_get_posts', 'minsithu_secure_search_query' );
