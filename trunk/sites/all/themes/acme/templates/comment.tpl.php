<?php
// $Id$

/**
 * @file comment.tpl.php
 * Modified theme implementation for comments which adds a pipe.
 *
 * Available variables:
 * - $author: Comment author. Can be link or plain text.
 * - $content: Body of the post.
 * - $date: Date and time of posting.
 * - $links: Various operational links.
 * - $new: New comment marker.
 * - $picture: Authors picture.
 * - $signature: Authors signature.
 * - $status: Comment status. Possible values are:
 *   comment-unpublished, comment-published or comment-preview.
 * - $submitted: By line with date and time.
 * - $title: Linked title.
 *
 * These two variables are provided for context.
 * - $comment: Full comment object.
 * - $node: Node object the comments are attached to.
 *
 * @see template_preprocess_comment()
 * @see theme_comment()
 */
?>
<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status ?> clearfix">
  <?php print $picture ?>
  
  <div class="author">
    by <?php print $author; ?>
    <?php if ($comment->new): ?>
      <span class="new"><?php print $new ?></span>
    <?php endif; ?>
  </div>
  <div class="submitted">
    <div class="date"><?php print $date ?></div>
    <?php if ($links) : ?>
      <div class="pipe">|</div><?php print $links ?>
    <?php endif; ?>
  </div>

  <div class="content">
    <?php print $content ?>
    <?php if ($signature): ?>
    <div class="user-signature clearfix">
      <?php print $signature ?>
    </div>
    <?php endif; ?>
  </div>
</div>
