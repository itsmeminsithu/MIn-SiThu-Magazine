<?php get_header(); ?>

<div class="category-section">
  <h2 class="section-title">
    <?php
    if ( is_category() )      echo esc_html( 'Category: ' . single_cat_title( '', false ) );
    elseif ( is_tag() )       echo esc_html( 'Tag: ' . single_tag_title( '', false ) );
    elseif ( is_author() )    echo esc_html( 'Author: ' . get_the_author() );
    elseif ( is_year() )      echo esc_html( 'Year: ' . get_the_date( 'Y' ) );
    elseif ( is_month() )     echo esc_html( 'Month: ' . get_the_date( 'F Y' ) );
    elseif ( is_day() )       echo esc_html( 'Day: ' . get_the_date( 'F j, Y' ) );
    else                      echo esc_html__( 'Archive', 'minsithu-magazine' );
    ?>
  </h2>

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
        <div class="meta">
          <i class="fas fa-user"></i> <?php echo esc_html( get_the_author() ); ?> &nbsp;&middot;&nbsp;
          <i class="fas fa-calendar-alt"></i> <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
        </div>
      </div>
    </div>
    <?php endwhile;
    else : echo '<p style="color:var(--white-50);padding:20px 0;">No posts found.</p>';
    endif; ?>
  </div>

  <div class="pagination">
    <?php echo wp_kses_post( paginate_links( array(
        'prev_text' => '<i class="fas fa-chevron-left"></i> Prev',
        'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
    ) ) ); ?>
  </div>
</div>

<?php get_footer(); ?>
