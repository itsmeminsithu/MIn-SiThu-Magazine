<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <div class="post-header">
    <?php echo minsithu_category_badge(); ?>
    <h1><?php echo esc_html( get_the_title() ); ?></h1>
    <div class="post-meta">
      <span><i class="fas fa-user"></i> <?php the_author_posts_link(); ?></span>
      <span><i class="fas fa-calendar-alt"></i> <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
      <span><i class="fas fa-clock"></i> <?php echo esc_html( minsithu_reading_time() ); ?></span>
      <span><i class="fas fa-comments"></i> <?php comments_number( '0 comments', '1 comment', '% comments' ); ?></span>
    </div>
  </div>

  <?php if ( has_post_thumbnail() ) : ?>
    <?php the_post_thumbnail( 'minsithu-hero', array( 'class' => 'post-featured-image', 'loading' => 'eager' ) ); ?>
  <?php else : ?>
    <?php echo minsithu_placeholder_image_tag( get_the_ID(), 'hero', 'post-featured-image', 'eager' ); ?>
  <?php endif; ?>

  <div class="share-buttons">
    <a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( get_permalink() ) ); ?>" target="_blank" rel="noopener" class="share-btn fb">
      <i class="fab fa-facebook"></i> Share
    </a>
    <a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . rawurlencode( get_permalink() ) . '&text=' . rawurlencode( get_the_title() ) ); ?>" target="_blank" rel="noopener" class="share-btn tw">
      <i class="fab fa-x-twitter"></i> Tweet
    </a>
    <a href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( get_the_title() . ' ' . get_permalink() ) ); ?>" target="_blank" rel="noopener" class="share-btn wa">
      <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <button class="share-btn cp" type="button" data-copy-url="<?php echo esc_url( get_permalink() ); ?>">
      <i class="fas fa-link"></i> Copy Link
    </button>
  </div>

  <div class="post-content" id="post-content">
    <?php the_content(); ?>
  </div>

  <?php
  $tags = get_the_tags();
  if ( $tags ) :
  ?>
  <div class="post-tags">
    <span style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--white-50);margin-right:8px;"><i class="fas fa-tags"></i> Tags:</span>
    <?php foreach ( $tags as $tag ) : ?>
    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <nav class="post-nav">
    <?php
    $prev = get_previous_post();
    $next = get_next_post();
    ?>
    <?php if ( $prev ) : ?>
    <div class="post-nav-item">
      <div class="nav-label"><i class="fas fa-chevron-left"></i> Previous</div>
      <h4><a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>"><?php echo esc_html( get_the_title( $prev->ID ) ); ?></a></h4>
    </div>
    <?php else : ?>
    <div></div>
    <?php endif; ?>
    <?php if ( $next ) : ?>
    <div class="post-nav-item" style="text-align:right;">
      <div class="nav-label">Next <i class="fas fa-chevron-right"></i></div>
      <h4><a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>"><?php echo esc_html( get_the_title( $next->ID ) ); ?></a></h4>
    </div>
    <?php endif; ?>
  </nav>

</article>

<?php
$cats     = get_the_category();
$cat_ids  = wp_list_pluck( $cats, 'term_id' );
$related  = ! empty( $cat_ids ) ? new WP_Query( array(
    'category__in'   => $cat_ids,
    'posts_per_page' => 3,
    'post__not_in'   => array( get_the_ID() ),
    'orderby'                => 'date',
    'post_status'            => 'publish',
    'no_found_rows'          => true,
    'ignore_sticky_posts'    => true,
) ) : null;
if ( $related && $related->have_posts() ) :
?>
<div class="category-section" style="margin-top:28px;">
  <h2 class="section-title">Related Posts</h2>
  <div class="post-grid-3">
    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
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
        <div class="card-meta"><i class="fas fa-calendar-alt"></i> <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></div>
      </div>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</div>
<?php endif; ?>

<?php
comments_template();
endwhile;
?>

<?php get_footer(); ?>
