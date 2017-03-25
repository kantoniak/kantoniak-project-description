<?php
/*
Plugin Name: Project Description
Plugin URI: http://antoniak.in/
Description: This plugin adds project description at the top of the article.
Version: 0.0.0
Author: Krzysztof Antoniak
Author URI: http://antoniak.in/
License: GNU General Public License, version 3.0 (GPL-3.0)
 */

namespace kantoniak {

class ProjectDescription {

  const OPTION_CATEGORY = 'kantoniak_pd_category';
  const OPTION_CATEGORY_NONE = -1;
  const OPTION_JUMPENABLED = 'kantoniak_pd_jumpenabled';

  public function __construct() {
    add_action('admin_menu', array($this, 'setupAdminMenu'));
    add_filter('the_content', array($this, 'prependWithProjectDescription'));
  }

  public function setupAdminMenu() {
    add_options_page('Project description', 'Project description', 'edit_plugins', 'project-description', array($this, 'handleSettingsPage'));
  }

  public function handleSettingsPage() {

    if (isset($_POST['submitted'])) {
        update_option(OPTION_CATEGORY, (int) $_POST['cat']);
        update_option(OPTION_JUMPENABLED, (boolean) $_POST['jump_to_content']);
        $settingsUpdated = true;
    }

    $noCategorySelected = ((boolean) get_option(OPTION_CATEGORY, OPTION_CATEGORY_NONE) == false);
    $categoriesDropdownOptions = array(
      'show_option_none' => 'Do not show at all (disables plugin)',
      'option_none_value' => OPTION_CATEGORY_NONE,
      'hierarchical' => true,
      'hide_empty' => false,
      'selected' => (int) get_option(OPTION_CATEGORY, OPTION_CATEGORY_NONE)
    );
    $jumpToChecked = (boolean) get_option(OPTION_JUMPENABLED, true);
    include('template-settings.php');
  }

  public function prependWithProjectDescription($content) {
    if (!is_single() || !in_the_loop() || !is_main_query() ) {
        return $content;
    }
 
    return $this->renderBox().$content;
  }

  protected function renderBox() {
    ob_start();
    include('template-box.php');
    return ob_get_clean();
  }
}

$projectDesc = new ProjectDescription();
}