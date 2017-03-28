<?php
/*
Plugin Name: Project Description
Plugin URI: https://github.com/kantoniak/kantoniak-project-description/
Description: This plugin adds project description at the top of the article.
Version: 0.0.1
Author: Krzysztof Antoniak
Author URI: http://antoniak.in/
License: GNU General Public License, version 3.0 (GPL-3.0)
 */

namespace kantoniak {

class ProjectDescription {

  const OPTION_CATEGORY = 'kantoniak_pd_category';
  const OPTION_JUMPENABLED = 'kantoniak_pd_jumpenabled';
  const OPTION_SHOWFROMCAT = 'kantoniak_pd_showfromcat';
  const OPTION_CHANGEDESC = 'kantoniak_pd_changedesc';
  const OPTION_BOXTITLE = 'kantoniak_pd_boxtitle';
  const OPTION_BOXCONTENTS = 'kantoniak_pd_boxcontents';

  const OPTION_CATEGORY_NONE = -1;
  const OPTION_CHANGEDESC_DEFAULT = false;
  const OPTION_BOXTITLE_DEFAULT = 'About the project';
  const OPTION_BOXCONTENTS_DEFAULT = '';

  public function __construct() {
    if (is_admin()) {
        add_action('admin_menu', array($this, 'setupAdminMenu'));
    } else {
        add_action('wp_enqueue_scripts', array($this, 'addStylesheet'));
        add_filter('the_content', array($this, 'prependWithProjectDescription'));
        add_filter('category_description', array($this, 'onCategoryDescription'));
    }
  }

  public function setupAdminMenu() {
    add_options_page('Project description', 'Project description', 'edit_plugins', 'project-description', array($this, 'handleSettingsPage'));
  }

  public function addStylesheet() {
    wp_enqueue_style('kantoniak-project-description', plugins_url('kantoniak-project-description/css/style.css')); 
  }

  public function handleSettingsPage() {

    if (isset($_POST['submitted'])) {
        update_option(ProjectDescription::OPTION_CATEGORY, (int) $_POST['cat']);
        update_option(ProjectDescription::OPTION_JUMPENABLED, (boolean) $_POST['jump_to_content']);
        update_option(ProjectDescription::OPTION_SHOWFROMCAT, (boolean) $_POST['show_from_cat']);
        update_option(ProjectDescription::OPTION_CHANGEDESC, (boolean) $_POST['change_cat_desc']);
        update_option(ProjectDescription::OPTION_BOXTITLE, $_POST['boxtitle']);
        update_option(ProjectDescription::OPTION_BOXCONTENTS, $_POST['boxcontents']);
        $settingsUpdated = true;
    }

    $noCategorySelected = ((boolean) get_option(ProjectDescription::OPTION_CATEGORY, ProjectDescription::OPTION_CATEGORY_NONE) == false);
    $categoriesDropdownOptions = array(
      'show_option_none' => 'Do not show at all (disables plugin)',
      'option_none_value' => ProjectDescription::OPTION_CATEGORY_NONE,
      'hierarchical' => true,
      'hide_empty' => false,
      'selected' => (int) get_option(ProjectDescription::OPTION_CATEGORY, ProjectDescription::OPTION_CATEGORY_NONE)
    );
    $jumpToChecked = (boolean) get_option(ProjectDescription::OPTION_JUMPENABLED, true);
    $showFromCat = (boolean) get_option(ProjectDescription::OPTION_SHOWFROMCAT, true);
    $changeCatDesc = (boolean) get_option(ProjectDescription::OPTION_CHANGEDESC, ProjectDescription::OPTION_CHANGEDESC_DEFAULT);
    $boxTitle = get_option(ProjectDescription::OPTION_BOXTITLE, ProjectDescription::OPTION_BOXTITLE_DEFAULT);
    $boxContents = get_option(ProjectDescription::OPTION_BOXCONTENTS, ProjectDescription::OPTION_BOXCONTENTS_DEFAULT);
    include('template-settings.php');
  }

  public function prependWithProjectDescription($content) {
    if (!is_single() || !in_the_loop() || !is_main_query() || !in_category(get_option(ProjectDescription::OPTION_CATEGORY, ProjectDescription::OPTION_CATEGORY_NONE))) {
        return $content;
    }

    $box = $this->renderBox();
    return $box.$content;
  }

  public function onCategoryDescription($content) {
    if (!is_category(get_option(ProjectDescription::OPTION_CATEGORY, ProjectDescription::OPTION_CATEGORY_NONE)) || !(boolean) get_option(ProjectDescription::OPTION_CHANGEDESC, ProjectDescription::OPTION_CHANGEDESC_DEFAULT)) {
      return $content;
    }

    return $this->renderBox();
  }

  protected function renderBox() {
    // List posts from the category
    $others = get_posts(array(
        'category' => get_option(ProjectDescription::OPTION_CATEGORY, ProjectDescription::OPTION_CATEGORY_NONE),
        'numberposts' => -1
    ));
    $postList = [];
    foreach ($others as $o) {
        $postList[] = array(
            'title' => get_the_title($o),
            'permalink' => get_permalink($o),
        );
    }

    $jumpEnabled = (boolean) get_option(ProjectDescription::OPTION_JUMPENABLED, true);
    $boxTitle = get_option(ProjectDescription::OPTION_BOXTITLE, ProjectDescription::OPTION_BOXTITLE_DEFAULT);
    $boxContents = html_entity_decode(stripcslashes(get_option(ProjectDescription::OPTION_BOXCONTENTS, ProjectDescription::OPTION_BOXCONTENTS_DEFAULT)));
    $postList = $postList;
    $showFromCat = (boolean) get_option(ProjectDescription::OPTION_SHOWFROMCAT, true);

    ob_start();
    include('template-box.php');
    return ob_get_clean();
  }
}

$projectDesc = new ProjectDescription();
}