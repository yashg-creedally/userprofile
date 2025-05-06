<?php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

if (!isset($user_id)) {
    $user_id = get_query_var('user_id_to_show', get_current_user_id());
}

error_log('UserProfileData - Start - User ID: ' . $user_id);

$user_info = get_userdata($user_id);
if ($user_info) {
    $email = $user_info->user_email;
} else {
    error_log('UserProfileData - Error: Could not retrieve user data for ID: ' . $user_id);
    $email = '';
}

$meta_handler = new MyPlugin\UserProfile\User_Profile_Meta_Fields();
$meta_fields = $meta_handler->get_meta_fields();

$profile_picture_id = get_user_meta($user_id, 'profile_picture', true);
$banner_image_id = get_user_meta($user_id, 'banner_image', true);

$default_profile_picture = plugin_dir_url(dirname(__FILE__)) . '/assets/img/anime.jpg';
$default_banner_image = plugin_dir_url(dirname(__FILE__)) . '/assets/img/porsche.jpeg';

$banner_url = (is_numeric($banner_image_id)) ? wp_get_attachment_url($banner_image_id) : $default_banner_image;
$profile_url = (is_numeric($profile_picture_id)) ? wp_get_attachment_url($profile_picture_id) : $default_profile_picture;

// Fetch all other meta fields dynamically
foreach ($meta_fields as $name => $field) {
    if ($name !== 'profile_picture' && $name !== 'banner_image' && $name !== 'social_links') {
        $$name = get_user_meta($user_id, $name, true);
    }
}
$social_links = maybe_unserialize(get_user_meta($user_id, 'social_links', true));
if (!is_array($social_links)) $social_links = [];


?>