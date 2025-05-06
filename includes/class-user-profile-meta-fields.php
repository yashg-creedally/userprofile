<?php

namespace MyPlugin\UserProfile;

if (!defined('ABSPATH')) {
    exit;
}

class User_Profile_Meta_Fields {

    private $meta_field_definitions = [
        'gender' => ['label' => 'Gender', 'type' => 'text'],
        'phone_number' => ['label' => 'Phone Number', 'type' => 'text'],
        'birth_date' => ['label' => 'Birth Date', 'type' => 'date'],
        'skills' => ['label' => 'Skills/Interests', 'type' => 'text'],
        'education_institution' => ['label' => 'Institution', 'type' => 'text'],
        'education_degree' => ['label' => 'Degree', 'type' => 'text'],
        'work_title' => ['label' => 'Job Title', 'type' => 'text'],
        'work_company' => ['label' => 'Company', 'type' => 'text'],
        'work_duration' => ['label' => 'Duration', 'type' => 'text'],
        'bio' => ['label' => 'Bio', 'type' => 'textarea'],
        'social_links' => ['label' => 'Social Links', 'type' => 'social', 'platforms' => ['facebook', 'linkedin', 'twitter', 'instagram', 'github']],
        'profile_picture' => ['label' => 'Profile Picture', 'type' => 'file'],
        'banner_image' => ['label' => 'Banner Image', 'type' => 'file'],
        'favorite_color' => ['label' => 'Favorite Color', 'type' => 'text'], // Example new field
        'hobbies' => ['label' => 'Hobbies', 'type' => 'text'] // Another example
    ];

    public function __construct() {
        add_action('show_user_profile', [$this, 'display_profile_fields']);
        add_action('edit_user_profile', [$this, 'display_profile_fields']);
        add_action('personal_options_update', [$this, 'save_profile_fields']);
        add_action('edit_user_profile_update', [$this, 'save_profile_fields']);
        add_action('user_edit_form_tag', [$this, 'add_enctype_to_form']);

        // Add action to display the "View Profile" button
        add_action('show_user_profile', [$this, 'add_view_profile_button']);
        add_action('edit_user_profile', [$this, 'add_view_profile_button']);
    }

    public function add_enctype_to_form() {
        echo ' enctype="multipart/form-data"';
    }

    public function display_profile_fields($user) {
        include plugin_dir_path(__FILE__) . '../templates/admin-meta-fields.php';
    }

    public function save_profile_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) return;

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        foreach ($this->meta_field_definitions as $name => $field) {
            if ($field['type'] === 'social' && isset($_POST['social_links']) && is_array($_POST['social_links'])) {
                $social_links = [];
                if (isset($field['platforms']) && is_array($field['platforms'])) {
                    foreach ($field['platforms'] as $platform) {
                        $social_links[$platform] = isset($_POST['social_links'][$platform]) ? esc_url_raw($_POST['social_links'][$platform]) : '';
                    }
                    update_user_meta($user_id, 'social_links', $social_links);
                }
            } elseif ($field['type'] === 'file' && !empty($_FILES[$name]['name'])) {
                $media_id = media_handle_upload($name, 0);
                if (!is_wp_error($media_id)) {
                    update_user_meta($user_id, $name, $media_id);
                } else {
                    error_log('Upload failed for ' . $field['label'] . ': ' . $media_id->get_error_message());
                }
            } elseif (isset($_POST[$name])) {
                update_user_meta($user_id, $name, sanitize_text_field($_POST[$name]));
            }
        }
    }

    public function get_meta_fields() {
        return $this->meta_field_definitions;
    }

    /**
     * Adds a "View Profile" button to the user edit form, linking to the author URL.
     *
     * @param WP_User $user The user object being edited.
     */
    public function add_view_profile_button($user) {
        $author_url = get_author_posts_url($user->ID);
        if ($author_url) {
            ?>
            <p class="submit">
                <a href="<?php echo esc_url($author_url); ?>" class="button"><?php esc_html_e('View Profile', 'your-plugin-slug'); ?></a>
            </p>
            <?php
        }
    }
}