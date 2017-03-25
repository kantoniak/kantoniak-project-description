<div class="wrap">
  <h1>Project Description</h1>

<?php
  if ($settingsUpdated) {
    echo '<div class="updated"><p>Settings updated.</p></div>';
  }

  if ($noCategorySelected) {
    echo '<div class="notice notice-info"><p>You have not selected the category. Project description will never be shown.</p></div>';
  }
?>

  <form method="POST">
    <div>
      <h2>General settings</h2>
      <table class="form-table">
      <tr>
          <th scope="row"><label for="category">For category</label></th>
          <td>
            <?php wp_dropdown_categories($categoriesDropdownOptions); ?>
            <p class="description">Box will be shown only for the posts in this category, on a single post page.</p>
          </td>
      </tr>
      <tr>
          <th scope="row">"Jump to content"</th>
          <td>
          <input name="jump_to_content" type="checkbox" <?php echo ($jumpToChecked ? ' checked' : ''); ?>>
            <label for="jump_to_content">Show "Jump to content" link</label>
          </td>
      </tr>
      </table>
    </div>

    <h2>About the project</h2>
    
    <input type="submit" value="Save settings" name="submitted" class="button button-primary button-large">
  </form>

</div>