<?php
namespace MyPlugin\UserProfile;

if (!defined('ABSPATH')) {
    exit; // No direct access allowed.
}

// Require necessary files
require_once plugin_dir_path(__FILE__) . 'class-user-profile-functions.php';
require_once plugin_dir_path(__FILE__) . 'class-user-profile-meta-fields.php';

use MyPlugin\UserProfile\User_Profile_Functions;
use MyPlugin\UserProfile\User_Profile_Meta_Fields;

class User_Profile {

    private $functions;
    private $admin;
    private $meta_fields;

    public function __construct() {
        $this->functions = new User_Profile_Functions();
        $this->meta_fields = new User_Profile_Meta_Fields();

        // Frontend
        add_shortcode('user_profile_form', [$this->functions, 'render_profile']);
        add_action('wp_enqueue_scripts', [$this->functions, 'enqueue_assets']);

        // Login redirection
        add_filter('login_redirect', [$this, 'redirect_to_profile'], 10, 3);
    }

    public function redirect_to_profile($redirect_to, $request, $user) {
        if (is_wp_error($user) || !isset($user->ID)) {
            return $redirect_to;
        }

        if (!in_array('administrator', $user->roles)) {
            $profile_page = get_page_by_path('profile-page');
            if ($profile_page) {
                return get_permalink($profile_page) . '?id=' . $user->ID;
            }
        }

        return $redirect_to;
    }
}
