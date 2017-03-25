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
  const OPTION_JUMPENABLED = 'kantoniak_pd_jumpenabled';
  const OPTION_BOXTITLE = 'kantoniak_pd_boxtitle';
  const OPTION_BOXCONTENTS = 'kantoniak_pd_boxcontents';

  const OPTION_CATEGORY_NONE = -1;
  const OPTION_BOXTITLE_DEFAULT = 'About the project';
  const OPTION_BOXCONTENTS_DEFAULT = '';

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
        update_option(OPTION_BOXTITLE, $_POST['boxtitle']);
        update_option(OPTION_BOXCONTENTS, $_POST['boxcontents']);
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
    $boxTitle = get_option(OPTION_BOXTITLE, ProjectDescription::OPTION_BOXTITLE_DEFAULT);
    $boxContents = get_option(OPTION_BOXCONTENTS, ProjectDescription::OPTION_BOXCONTENTS_DEFAULT);
    include('template-settings.php');
  }

  public function prependWithProjectDescription($content) {
    if (!is_single() || !in_the_loop() || !is_main_query() || !in_category(get_option(OPTION_CATEGORY, OPTION_CATEGORY_NONE))) {
        return $content;
    }
 
    // List posts from the category
    $others = get_posts(array('category' => get_option(OPTION_CATEGORY, OPTION_CATEGORY_NONE)));
    $postList = [];
    foreach ($others as $o) {
        $postList[] = array(
            'title' => get_the_title($o),
            'permalink' => get_permalink($o),
        );
    }

    $box = $this->renderBox(
        (boolean) get_option(OPTION_JUMPENABLED, true),
        get_option(OPTION_BOXTITLE, ProjectDescription::OPTION_BOXTITLE_DEFAULT),
        html_entity_decode(stripcslashes(get_option(OPTION_BOXCONTENTS, ProjectDescription::OPTION_BOXCONTENTS_DEFAULT))),
        $postList
    );

    return $box.$content;
  }

  protected function renderBox($jumpEnabled, $boxTitle, $boxContents, $postList) {
    ob_start();
    include('template-box.php');
    return ob_get_clean();
  }
}

$projectDesc = new ProjectDescription();
}