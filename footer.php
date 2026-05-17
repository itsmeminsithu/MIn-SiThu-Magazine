      </main>

<aside id="sidebar">

        <div class="widget about-widget">
          <?php
          $custom_logo_id   = get_theme_mod( 'custom_logo' );
          $custom_logo_url  = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'thumbnail' ) : '';
          $typewriter_items = array(
              'I am working as a Software Engineer',
              'Building clean, secure web experiences',
              'Open Source Contributor',
              'Full-Stack Web Engineer',
          );
          $emoji_items = array( '💻', '⚡', '🚀', '👨‍💻' );
          ?>
          <div class="author-card-glow" aria-hidden="true"></div>
          <?php if ( $custom_logo_url ) : ?>
            <div class="author-avatar-wrap">
              <img src="<?php echo esc_url( $custom_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="avatar">
              <span class="author-emoji" aria-hidden="true" data-emoji-items="<?php echo esc_attr( wp_json_encode( $emoji_items, JSON_UNESCAPED_UNICODE ) ); ?>">💻</span>
            </div>
          <?php else : ?>
            <div class="author-code-icon" aria-hidden="true">
              <span class="author-emoji" aria-hidden="true" data-emoji-items="<?php echo esc_attr( wp_json_encode( $emoji_items, JSON_UNESCAPED_UNICODE ) ); ?>">💻</span>
              <?php echo minsithu_coder_icon(); ?>
            </div>
          <?php endif; ?>
          <div class="name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
          <p class="bio typewriter-text" data-type-items="<?php echo esc_attr( wp_json_encode( $typewriter_items, JSON_UNESCAPED_UNICODE ) ); ?>"><?php echo esc_html( $typewriter_items[0] ); ?></p>
          <div class="social-links">
            <?php foreach ( minsithu_social_links() as $s ) : if ( ! empty( $s['url'] ) ) : ?>
              <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>">
                <?php echo minsithu_social_icon( $s['icon'] ); ?>
              </a>
            <?php endif; endforeach; ?>
          </div>
        </div>

        <div class="widget">
          <h3 class="widget-title">Search</h3>
          <form class="search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="search" name="s" placeholder="Search articles..." value="<?php echo esc_attr( get_search_query() ); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
          </form>
        </div>

        <div class="widget">
          <h3 class="widget-title">Popular Posts</h3>
          <?php
          $popular = new WP_Query( array(
              'posts_per_page' => 5,
              'orderby'        => 'comment_count',
              'order'          => 'DESC',
              'post_status'    => 'publish',
          ) );
          if ( $popular->have_posts() ) :
              while ( $popular->have_posts() ) : $popular->the_post();
          ?>
          <div class="popular-post-item">
            <a href="<?php echo esc_url( get_permalink() ); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'minsithu-small', array( 'loading' => 'lazy' ) ); ?>
                <?php else : ?>
                  <?php echo minsithu_placeholder_image_tag( get_the_ID(), 'small', '', 'lazy' ); ?>
                <?php endif; ?>
              </a>
            <div>
              <h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h4>
              <div class="meta"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></div>
            </div>
          </div>
          <?php
              endwhile;
              wp_reset_postdata();
          endif;
          ?>
        </div>

        <div class="widget">
          <h3 class="widget-title">Categories</h3>
          <div class="labels-cloud">
            <?php
            $categories = get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 20, 'hide_empty' => true ) );
            foreach ( $categories as $cat ) :
            ?>
            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="label-tag">
              <?php echo esc_html( $cat->name ); ?> <small style="opacity:.6;">(<?php echo esc_html( absint( $cat->count ) ); ?>)</small>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
          <?php dynamic_sidebar( 'sidebar-main' ); ?>
        <?php endif; ?>

      </aside>
</div>
</div>
</div>

<footer id="site-footer">
  <div class="container">
    <div class="footer-top">

      <div class="footer-widget">
        <h4><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h4>
        <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?><br><br>
        Developer: Min SiThu.<br>Building clean, secure, Myanmar-ready web experiences.</p>
        <div class="social-links" style="margin-top:14px; justify-content:flex-start;">
          <?php foreach ( minsithu_social_links() as $s ) : if ( ! empty( $s['url'] ) ) : ?>
            <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>">
              <?php echo minsithu_social_icon( $s['icon'] ); ?>
            </a>
          <?php endif; endforeach; ?>
        </div>
      </div>

      <div class="footer-widget">
        <h4>Quick Links</h4>
        <?php
        wp_nav_menu( array(
            'theme_location' => 'footer',
            'menu_class'     => 'footer-links',
            'container'      => false,
            'depth'          => 1,
            'fallback_cb'    => function() {
                echo '<ul class="footer-links">';
                $items = array(
                    home_url( '/' )               => 'Home',
                    home_url( '/about' )          => 'About',
                    home_url( '/contact' )        => 'Contact',
                    home_url( '/sitemap' )        => 'Sitemap',
                );
                foreach ( $items as $url => $label ) {
                    echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
                }
                echo '</ul>';
            }
        ) );
        ?>
      </div>
<div class="footer-widget">
        <h4>Categories</h4>
        <ul class="footer-links">
          <?php
          $fcats = get_categories( array( 'number' => 8, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ) );
          foreach ( $fcats as $cat ) :
          ?>
          <li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-widget">
        <h4>Recent Posts</h4>
        <ul class="footer-links">
          <?php
          $recent = new WP_Query( array( 'posts_per_page' => 5, 'post_status' => 'publish' ) );
          while ( $recent->have_posts() ) : $recent->the_post();
          ?>
          <li><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( wp_trim_words( get_the_title(), 6, '…' ) ); ?></a></li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>

    </div>
<div class="footer-bottom">
      <p>Copyright &copy; 2016 | <?php echo esc_html( date_i18n( 'Y' ) ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a> · Developer: <strong>Min SiThu</strong></p>
      <p>
        <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>">Privacy</a> ·
        <a href="<?php echo esc_url( home_url( '/eula' ) ); ?>">EULA</a> ·
        <a href="<?php echo esc_url( home_url( '/creative-commons' ) ); ?>">Creative Commons</a>
      </p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
