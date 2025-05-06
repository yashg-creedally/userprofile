<?php

namespace MyPlugin\UserProfile;

if (!defined('ABSPATH')) {
    exit;
}

class User_Profile_Functions {

    public function enqueue_assets() {
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [], '5.15.4');
        wp_enqueue_style('user-profile-style', plugin_dir_url(__DIR__) . 'assets/css/style.css', [], '1.0');
    }

    public function render_profile() {
        $user_id_to_show = get_current_user_id(); // Default to the logged-in user

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $requested_user_id = (int) $_GET['id'];
            // Basic check: Only allow viewing other profiles if the ID is valid and exists
            if (get_userdata($requested_user_id)) {
                $user_id_to_show = $requested_user_id;
            }
        } elseif (is_author()) {
            $user = get_user_by('slug', get_query_var('author_name'));
            if ($user) {
                $user_id_to_show = $user->ID;
            }
        }

        set_query_var('user_id_to_show', $user_id_to_show);

        // Include user-profile-data.php to set $profile_url and $banner_url
        include plugin_dir_path(dirname(__FILE__)) . 'templates/user-profile-data.php';

        ob_start();
        include plugin_dir_path(dirname(__FILE__)) . 'templates/form.php';
        return ob_get_clean();
    }
}