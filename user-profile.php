<?php
/*
Plugin Name: User Profile Manage
Description: A beautiful front-end user profile management plugin.
Version: 1.0
Author: Yash Gondaliya
*/

if (!defined('ABSPATH')) {
    exit; // No direct access allowed.
}

// Include all required class files
require_once plugin_dir_path(__FILE__) . 'includes/class-user-profile.php'; 

// Initialize the front-end user profile functionality
function upp_initialize_user_profile() {
    new \MyPlugin\UserProfile\User_Profile(); 
}
add_action('plugins_loaded', 'upp_initialize_user_profile');

