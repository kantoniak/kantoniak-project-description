<aside id="project-description">
  <h2>Project name</h2>
  <?php if ($jumpEnabled) {
    echo '<a href="#project-description-end">Jump to content</a>';
  } ?>
  <div class="content">
    <p>Lorem ipsum dolor sit amet.</p>
  </div>
</aside>
<?php if ($jumpEnabled) {
  echo '<a name="project-description-end" />';
} ?>