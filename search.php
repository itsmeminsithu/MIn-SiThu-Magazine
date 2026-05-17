<?php get_header(); ?>
<div class="category-section">
  <h2 class="section-title">Search: "<?php echo esc_html( get_search_query() ); ?>"</h2>
  <div class="post-list">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
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
        <div class="meta"><i class="fas fa-calendar-alt"></i> <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></div>
      </div>
    </div>
    <?php endwhile;
    else : ?>
      <p style="color:var(--white-50);padding:30px 0;text-align:center;">
        <i class="fas fa-search" style="font-size:32px;margin-bottom:12px;display:block;"></i>
        No results for "<?php echo esc_html( get_search_query() ); ?>".
      </p>
    <?php endif; ?>
  </div>
  <div class="pagination"><?php echo wp_kses_post( paginate_links() ); ?></div>
</div>
<?php get_footer(); ?>
