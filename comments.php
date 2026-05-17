<?php if ( post_password_required() ) return; ?>

<div id="comments">
  <?php if ( have_comments() ) : ?>
    <h3><i class="fas fa-comments"></i> <?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></h3>
    <ul class="comment-list">
      <?php wp_list_comments( array(
          'style'       => 'ul',
          'short_ping'  => true,
          'avatar_size' => 40,
          'callback'    => function( $comment, $args, $depth ) {
              ?>
              <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment' ); ?>>
                <div class="comment-author"><?php echo esc_html( get_comment_author() ); ?></div>
                <div class="comment-meta"><?php echo esc_html( get_comment_date( 'F j, Y' ) ); ?> at <?php echo esc_html( get_comment_time() ); ?></div>
                <div class="comment-body"><?php comment_text(); ?></div>
                <div style="margin-top:6px;">
                  <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div>
              </li>
              <?php
          },
      ) ); ?>
    </ul>
    <div class="pagination"><?php paginate_comments_links( array( 'screen_reader_text' => esc_html__( 'Comment navigation', 'minsithu-magazine' ) ) ); ?></div>
  <?php endif; ?>

  <?php if ( comments_open() ) :
      comment_form( array(
          'class_form'           => 'comment-form',
          'class_submit'         => 'btn-submit',
          'title_reply'          => '<h3 style="margin-bottom:16px;"><i class="fas fa-pen"></i> Leave a Comment</h3>',
          'label_submit'         => 'Post Comment',
          'comment_field'        => '<p><textarea name="comment" id="comment" placeholder="Write your comment..." required></textarea></p>',
      ) );
  else :
      echo '<p style="color:var(--white-50);">Comments are closed.</p>';
  endif; ?>
</div>
