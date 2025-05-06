<h3>Additional Profile Information</h3>
<table class="form-table">
<?php
$meta_fields = $this->get_meta_fields();

foreach ($meta_fields as $name => $field) {
    $value = get_user_meta($user->ID, $name, true);
    echo '<tr>';
    echo '<th><label for="' . esc_attr($name) . '">' . esc_html($field['label']) . '</label></th>';
    echo '<td>';

    if ($field['type'] === 'text' || $field['type'] === 'date') {
        echo '<input type="' . esc_attr($field['type']) . '" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="regular-text">';
    } elseif ($field['type'] === 'textarea') {
        echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" rows="5" cols="15" class="regular-text">' . esc_textarea($value) . '</textarea>';
    }elseif ($field['type'] === 'social' && isset($field['platforms']) && is_array($field['platforms'])) {
        $social_links = maybe_unserialize($value);
        if (!is_array($social_links)) $social_links = [];
        foreach ($field['platforms'] as $platform) {
            $link = isset($social_links[$platform]) ? $social_links[$platform] : '';
            echo '<input type="text" name="social_links[' . esc_attr($platform) . ']" value="' . esc_url($link) . '" class="regular-text" placeholder="' . ucfirst($platform) . ' URL"><br>';
        }
    } elseif ($field['type'] === 'file') {
        echo '<input type="file" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '">';
        if ($value) {
            $url = wp_get_attachment_url($value);
            echo '<br><img src="' . esc_url($url) . '" style="max-width: 100px;">';
        }
    }

    echo '</td>';
    echo '</tr>';
}
?>
</table>