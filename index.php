<?php get_header(); ?>

<?php if ( is_home() && ! is_paged() ) : ?>
<section id="featured-hero">
  <?php
  $hero_query = new WP_Query( array(
      'posts_per_page' => 5,
      'post_status'    => 'publish',
      'orderby'        => 'date',
      'order'                  => 'DESC',
      'no_found_rows'          => true,
      'ignore_sticky_posts'    => true,
      'update_post_meta_cache' => false,
      'update_post_term_cache' => false,
  ) );

  $hero_posts = array();
  if ( $hero_query->have_posts() ) {
      while ( $hero_query->have_posts() ) {
          $hero_query->the_post();
          $hero_posts[] = array(
              'id'       => get_the_ID(),
              'title'    => get_the_title(),
              'url'      => get_permalink(),
              'date'     => get_the_date( 'M j, Y' ),
              'category' => minsithu_category_badge(),
              'thumb'    => get_the_post_thumbnail_url( null, 'minsithu-hero' ) ? get_the_post_thumbnail_url( null, 'minsithu-hero' ) : minsithu_placeholder_image_url( get_the_ID(), 'hero' ),
              'author'   => get_the_author(),
          );
      }
      wp_reset_postdata();
  }

  if ( ! empty( $hero_posts ) ) :
      $main = $hero_posts[0];
  ?>
<div class="hero-main">
    <img src="<?php echo esc_url( $main['thumb'] ); ?>" alt="<?php echo esc_attr( $main['title'] ); ?>" class="<?php echo has_post_thumbnail( $main['id'] ) ? '' : 'minsithu-placeholder-image'; ?>" loading="eager" decoding="async">
    <div class="hero-overlay">
      <?php echo $main['category']; ?>
      <h2 class="hero-title"><a href="<?php echo esc_url( $main['url'] ); ?>" style="color:inherit;"><?php echo esc_html( $main['title'] ); ?></a></h2>
      <div class="hero-meta">
        <i class="fas fa-user"></i> <?php echo esc_html( $main['author'] ); ?> &nbsp;&middot;&nbsp;
        <i class="fas fa-calendar"></i> <?php echo esc_html( $main['date'] ); ?>
      </div>
    </div>
  </div>
<?php if ( count( $hero_posts ) > 1 ) : ?>
  <div class="hero-grid">
    <?php foreach ( array_slice( $hero_posts, 1, 4 ) as $p ) : ?>
    <div class="hero-small-card">
      <?php $hero_small_thumb = get_the_post_thumbnail_url( $p['id'], 'minsithu-card' ) ? get_the_post_thumbnail_url( $p['id'], 'minsithu-card' ) : minsithu_placeholder_image_url( $p['id'], 'card' ); ?>
        <img src="<?php echo esc_url( $hero_small_thumb ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" class="<?php echo has_post_thumbnail( $p['id'] ) ? '' : 'minsithu-placeholder-image'; ?>" loading="lazy" decoding="async">
      <div class="card-overlay">
        <div class="card-category"><?php
          $cats = get_the_category( $p['id'] );
          echo $cats ? esc_html( $cats[0]->name ) : '';
        ?></div>
        <div class="card-title"><a href="<?php echo esc_url( $p['url'] ); ?>" style="color:inherit;"><?php echo esc_html( wp_trim_words( $p['title'], 10, '…' ) ); ?></a></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php endif; ?>
</section>
<?php endif; ?>
<div class="category-section">
  <h2 class="section-title">Latest Posts</h2>
  <div class="post-list">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
    ?>
    <div class="post-list-item">
      <a href="<?php echo esc_url( get_permalink() ); ?>">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'minsithu-thumb', array( 'class' => 'thumb', 'loading' => 'lazy' ) ); ?>
          <?php else : ?>
            <?php echo minsithu_placeholder_image_tag( get_the_ID(), 'thumb', 'thumb', 'lazy' ); ?>
          <?php endif; ?>
        </a>
      <div class="post-info">
        <?php echo minsithu_category_badge(); ?>
        <h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h3>
        <div class="meta">
          <i class="fas fa-user"></i> <?php echo esc_html( get_the_author() ); ?> &nbsp;&middot;&nbsp;
          <i class="fas fa-calendar-alt"></i> <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?> &nbsp;&middot;&nbsp;
          <i class="fas fa-clock"></i> <?php echo esc_html( minsithu_reading_time() ); ?>
        </div>
      </div>
    </div>
    <?php
        endwhile;
    else :
        echo '<p style="color:var(--white-50);padding:20px 0;">No posts found.</p>';
    endif;
    ?>
  </div>

    <div class="pagination">
    <?php echo wp_kses_post( paginate_links( array(
        'prev_text' => '<i class="fas fa-chevron-left"></i> Prev',
        'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
    ) ) ); ?>
  </div>
</div>

<?php if ( is_home() && ! is_paged() ) :

$block_categories = array( 'Hacker', 'Biography', 'Computer', 'Programming Languages' );

foreach ( $block_categories as $cat_name ) :
    $term = get_term_by( 'name', $cat_name, 'category' );
    if ( ! $term ) continue;

    $cat_query = new WP_Query( array(
        'cat'            => $term->term_id,
        'posts_per_page'         => 3,
        'post_status'            => 'publish',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ) );

    if ( ! $cat_query->have_posts() ) continue;
?>
<div class="category-section">
  <h2 class="section-title">
    <?php echo esc_html( $term->name ); ?>
    <a href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>" style="font-size:11px;font-weight:600;letter-spacing:1px;color:var(--blue-bright);margin-left:auto;text-transform:uppercase;">View All →</a>
  </h2>
  <div class="post-grid-3">
    <?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
    <div class="post-card">
      <a href="<?php echo esc_url( get_permalink() ); ?>">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'minsithu-card', array( 'class' => 'card-thumb', 'loading' => 'lazy' ) ); ?>
          <?php else : ?>
            <?php echo minsithu_placeholder_image_tag( get_the_ID(), 'card', 'card-thumb', 'lazy' ); ?>
          <?php endif; ?>
        </a>
      <div class="card-body">
        <?php echo minsithu_category_badge(); ?>
        <h3 class="card-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h3>
        <div class="card-meta">
          <i class="fas fa-calendar-alt"></i> <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?> &nbsp;&middot;&nbsp;
          <i class="fas fa-clock"></i> <?php echo esc_html( minsithu_reading_time() ); ?>
        </div>
      </div>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</div>
<?php
endforeach;
endif;
?>

<?php get_footer(); ?>
