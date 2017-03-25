<aside id="project-description">
  <h2><?php echo $boxTitle; ?></h2>
  <?php if ($jumpEnabled) {
    echo '<a href="#project-description-end">Jump to content</a>';
  } ?>
  <div class="content">
    <?php echo $boxContents; ?>
  </div>
<?php if ($postList) { ?>
  <div class="other-posts">
    <p>See other posts from this category:</p>
    <ul class="other-posts-list">
<?php
  foreach ($postList as $post) {
      echo '<li><a href="'. $post['permalink'] .'">'. $post['title'] .'</a></li>';
  }
?>
    </ul>
  </div>
<?php } ?>
</aside>
<?php if ($jumpEnabled) {
  echo '<a name="project-description-end" />';
} ?>