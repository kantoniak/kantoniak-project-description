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


  public function __construct() {
    add_filter('the_content', array($this, 'prependWithProjectDescription'));
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