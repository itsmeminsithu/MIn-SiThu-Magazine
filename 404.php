<?php get_header(); ?>
<div class="category-section" style="text-align:center;padding:60px 20px;">
  <div style="font-size:80px;font-weight:900;color:var(--blue-bright);font-family:var(--font-display);line-height:1;margin-bottom:16px;">404</div>
  <h2 style="font-size:24px;margin-bottom:12px;">Page Not Found</h2>
  <p style="color:var(--white-50);margin-bottom:24px;">The page you're looking for doesn't exist or has been moved.</p>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="background:var(--blue-medium);color:var(--white);padding:12px 28px;border-radius:4px;font-weight:600;display:inline-block;transition:all .25s;">
    <i class="fas fa-home"></i> Go Home
  </a>
</div>
<?php get_footer(); ?>
