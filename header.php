<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<style id="minsithu-critical-header-css">
.minsithu-skip-link{position:absolute!important;left:-9999px!important;top:auto!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;clip-path:inset(50%)!important;white-space:nowrap!important;border:0!important}.minsithu-skip-link:focus,.minsithu-skip-link:active{position:fixed!important;left:12px!important;top:12px!important;width:auto!important;height:auto!important;margin:0!important;padding:10px 14px!important;overflow:visible!important;clip:auto!important;clip-path:none!important;white-space:normal!important;z-index:100000!important;background:#fff!important;color:#1877f2!important;border:1px solid #dce3ee!important;border-radius:12px!important;box-shadow:0 8px 28px rgba(15,23,42,.14)!important}@media(max-width:860px){#top-bar{display:none!important}#site-header{position:sticky!important;top:0!important;min-height:64px!important;padding:10px 0!important;transform:none!important}#site-header .header-inner{display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:center!important;gap:10px!important;min-height:44px!important}.site-branding{min-width:0!important;flex:1 1 auto!important}.site-tagline{display:none!important}.header-actions{width:auto!important;flex:0 0 auto!important;display:flex!important;align-items:center!important;gap:8px!important}.header-actions .search-form{display:none!important;position:absolute!important;left:14px!important;right:14px!important;top:calc(100% + 8px)!important;z-index:1002!important}.search-open .header-actions .search-form{display:flex!important}#primary-nav{position:sticky!important;top:64px!important;z-index:998!important}#primary-nav ul{display:none!important}#primary-nav.open ul{display:flex!important}}
</style>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="minsithu-skip-link" href="#main-content"><?php echo esc_html__( 'Skip to content', 'minsithu-magazine' ); ?></a>

<?php if ( is_single() ) : ?>
<div id="reading-progress"></div>
<?php endif; ?>

<div id="top-bar">
  <div class="container">
    <span class="date site-clock" data-timezone="<?php echo esc_attr( wp_timezone_string() ); ?>" data-initial-time="<?php echo esc_attr( wp_date( DATE_W3C ) ); ?>">
      <span class="clock-date"><?php echo esc_html( wp_date( 'l, F j, Y' ) ); ?></span>
      <span class="clock-separator">•</span>
      <span class="clock-time"><?php echo esc_html( wp_date( 'H:i:s' ) ); ?></span>
    </span>
    <div class="social-top" aria-label="<?php echo esc_attr__( 'Social links', 'minsithu-magazine' ); ?>">
      <?php foreach ( minsithu_social_links() as $s ) : if ( ! empty( $s['url'] ) ) : ?>
        <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $s['label'] ); ?>">
          <?php echo minsithu_social_icon( $s['icon'] ); ?>
        </a>
      <?php endif; endforeach; ?>
    </div>
  </div>
</div>

<header id="site-header">
  <div class="container">
    <div class="header-inner">
      <div class="site-branding">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <div class="site-title">
            <?php
            $name = get_bloginfo( 'name' );
            $parts = explode( ' ', $name, 2 );
            echo esc_html( $parts[0] );
            if ( ! empty( $parts[1] ) ) echo ' <span>' . esc_html( $parts[1] ) . '</span>';
            ?>
          </div>
          <div class="site-tagline"><?php echo esc_html( get_bloginfo( 'description' ) ?: 'The Knowledge Box For You' ); ?></div>
        </a>
      </div>

      <div class="header-actions">
        <button class="search-toggle" id="search-toggle" type="button" aria-expanded="false" aria-controls="header-search" aria-label="<?php echo esc_attr__( 'Open search', 'minsithu-magazine' ); ?>"><i class="fas fa-search"></i></button>
        <form class="search-form" id="header-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
          <input type="search" name="s" placeholder="Search..." value="<?php echo esc_attr( get_search_query() ); ?>">
          <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <button class="nav-toggle" id="nav-toggle" type="button" aria-expanded="false" aria-label="<?php echo esc_attr__( 'Open navigation menu', 'minsithu-magazine' ); ?>">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
    <div class="header-meta-mobile" aria-label="<?php echo esc_attr__( 'Current date and social links', 'minsithu-magazine' ); ?>">
      <span class="site-clock mobile-clock" data-timezone="<?php echo esc_attr( wp_timezone_string() ); ?>" data-initial-time="<?php echo esc_attr( wp_date( DATE_W3C ) ); ?>">
        <span class="clock-date"><?php echo esc_html( wp_date( 'M j, Y' ) ); ?></span>
        <span class="clock-separator">•</span>
        <span class="clock-time"><?php echo esc_html( wp_date( 'H:i' ) ); ?></span>
      </span>
      <div class="social-top social-mobile">
        <?php foreach ( minsithu_social_links() as $s ) : if ( ! empty( $s['url'] ) ) : ?>
          <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $s['label'] ); ?>"><?php echo minsithu_social_icon( $s['icon'] ); ?></a>
        <?php endif; endforeach; ?>
      </div>
    </div>
  </div>
</header>

<nav id="primary-nav">
  <div class="container">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => '',
        'container'      => false,
        'walker'         => new Minsithu_Nav_Walker(),
        'fallback_cb'    => function() {
            echo '<ul>';
            echo '<li class="current-menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
            $cats = get_categories( array( 'number' => 6, 'hide_empty' => true ) );
            foreach ( $cats as $cat ) {
                echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
            }
            echo '</ul>';
        },
    ) );
    ?>
  </div>
</nav>

<?php
$breaking_text = get_theme_mod( 'breaking_news_text', '' );
$breaking_posts = new WP_Query( array(
    'posts_per_page'         => 5,
    'post_status'            => 'publish',
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'no_found_rows'          => true,
    'ignore_sticky_posts'    => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
) );
if ( $breaking_posts->have_posts() || $breaking_text ) :
?>
<div id="breaking-news">
  <div class="label"><span>Breaking</span></div>
  <div class="ticker-wrapper" aria-label="<?php echo esc_attr__( 'Breaking news', 'minsithu-magazine' ); ?>">
    <div class="ticker-content">
      <div class="ticker-track">
        <?php
        $ticker_items = array();
        if ( $breaking_text ) {
            foreach ( explode( '|', $breaking_text ) as $item ) {
                $item = trim( $item );
                if ( '' !== $item ) {
                    $ticker_items[] = array( 'text' => $item, 'url' => '' );
                }
            }
        } else {
            while ( $breaking_posts->have_posts() ) {
                $breaking_posts->the_post();
                $ticker_items[] = array( 'text' => get_the_title(), 'url' => get_permalink() );
            }
            wp_reset_postdata();
        }

        for ( $loop = 0; $loop < 2; $loop++ ) :
            foreach ( $ticker_items as $ticker_item ) :
                if ( ! empty( $ticker_item['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $ticker_item['url'] ); ?>"><?php echo esc_html( $ticker_item['text'] ); ?></a>
                <?php else : ?>
                    <span><?php echo esc_html( $ticker_item['text'] ); ?></span>
                <?php endif;
            endforeach;
        endfor;
        ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div id="content-area">
  <div class="container">
    <div class="content-wrapper">
      <main id="main-content">
