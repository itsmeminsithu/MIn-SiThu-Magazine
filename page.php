<?php get_header(); ?>
<div class="category-section">
<?php while ( have_posts() ) : the_post(); ?>
  <h1 style="font-size:28px;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid var(--border-subtle);"><?php echo esc_html( get_the_title() ); ?></h1>
  <div class="post-content"><?php the_content(); ?></div>
  <?php comments_template(); ?>
<?php endwhile; ?>
</div>
<?php get_footer(); ?>
