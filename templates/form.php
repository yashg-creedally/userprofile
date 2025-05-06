<?php
// Get the user ID from query var or fallback to current logged-in user
$user_id = get_query_var('user_id_to_show', get_current_user_id());

// Load all user profile meta and image URLs
include plugin_dir_path(__FILE__) . 'user-profile-data.php';

// Load meta fields from the class (labels, keys, etc.)
$meta_handler = new MyPlugin\UserProfile\User_Profile_Meta_Fields();
$meta_fields = $meta_handler->get_meta_fields();
?>

<div class="profile">
    <!-- Banner background -->
    <div class="profile__banner" style="background-image: url('<?php echo esc_url($banner_url); ?>');"></div>

    <!-- Profile picture + name + bio -->
    <div class="profile__header">
        <div class="profile__picture">
            <img src="<?php echo esc_url($profile_url); ?>" alt="Profile Picture">
        </div>
        <div class="profile__header-info">
            <h2 class="profile__name"><?php echo esc_html($user_info->display_name); ?></h2>
            <p class="profile__bio"><?php echo esc_html($bio); ?></p>
        </div>
    </div>

    <!-- Main profile info section -->
    <div class="profile__info">
        <div class="profile__info-container">

            <!-- Left Column: Basic Info -->
            <div class="profile__column profile__column--left">
                <h3 class="profile__section-title">Basic Info</h3>
                <?php foreach ($meta_fields as $name => $field): ?>
                    <?php if (in_array($name, ['gender', 'phone_number', 'birth_date', 'skills', 'favorite_color', 'hobbies'])): ?>
                        <p class="profile__field">
                            <strong class="profile__field-label"><?php echo esc_html($field['label']); ?>:</strong>
                            <span class="profile__field-value"><?php echo esc_html($$name); ?></span>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Right Column: Professional Details + Social -->
            <div class="profile__column profile__column--right">
                <h3 class="profile__section-title">Professional Details</h3>
                <?php foreach ($meta_fields as $name => $field): ?>
                    <?php if (in_array($name, ['education_institution', 'education_degree', 'work_title', 'work_company', 'work_duration'])): ?>
                        <p class="profile__field">
                            <strong class="profile__field-label"><?php echo esc_html($field['label']); ?>:</strong>
                            <span class="profile__field-value"><?php echo esc_html($$name); ?></span>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Social media icons (if available) -->
                <div class="profile__social-links">
                    <?php
                    $social_icons = [
                        'instagram' => 'fab fa-instagram',
                        'facebook' => 'fab fa-facebook-f',
                        'twitter' => 'fab fa-twitter',
                        'linkedin' => 'fab fa-linkedin-in',
                        'github' => 'fab fa-github',
                    ];
                    foreach ($social_icons as $key => $icon) {
                        if (!empty($social_links[$key])) {
                            echo '<a href="' . esc_url($social_links[$key]) . '" target="_blank" class="profile__social-icon ' . esc_attr($icon) . '"></a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
