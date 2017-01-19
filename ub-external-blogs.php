<?php
/**
 * Contains Main file of plugin "External blogs".
 *
 * @copyright 2016 Sigma Software
 * @package   UB
 *
 * @author    Victor Bairak(victor.bairak@sigma.software).
 */

/**
 * Plugin Name: External blogs
 * Description: This plugin provides an ability to get common info about the remote blogs using REST API.
 * Author: Victor Bairak
 * Version: 0.1
 * Network: true
 */

namespace UB\External_Blogs;

require_once __DIR__ . '/includes/class-rest-blog-data.php';
require_once __DIR__ . '/includes/class-blogs_list.php';
require_once __DIR__ . '/includes/class-blog.php';
require_once __DIR__ . '/includes/class-external-blogs.php';
require_once __DIR__ . '/includes/class-settings.php';

/**
 * Returns path to initial file of this plugin.
 *
 * @return string
 */
function get_path_to_plugin_file() {
	return __FILE__;
}

new Rest_Blog_Data();
Settings::get_instance();
