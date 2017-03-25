<aside id="project-description">
  <h2><?php echo $boxTitle; ?></h2>
  <?php if ($jumpEnabled) {
    echo '<a href="#project-description-end">Jump to content</a>';
  } ?>
  <div class="content">
    <?php echo $boxContents; ?>
  </div>
</aside>
<?php if ($jumpEnabled) {
  echo '<a name="project-description-end" />';
} ?>