<?php
/*
Plugin Name: Background Color Changer
Plugin URI: http://example.com
Description: A plugin that allows the administrator to change the website's background color.
Version: 1.0
Author: Your Name
Author URI: http://example.com
*/

function bgcc_add_menu_item() {
    add_menu_page('Background Color Changer', 'BG Color Changer', 'manage_options', 'bg-color-changer', 'bgcc_settings_page', 'dashicons-admin-customizer', 99);
}
add_action('admin_menu', 'bgcc_add_menu_item');

function bgcc_settings_page() {
    ?>
    <div class="wrap">
        <h1>Background Color Changer</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('bgcc_settings_group');
                do_settings_sections('bg-color-changer');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

function bgcc_settings_init() {
    register_setting('bgcc_settings_group', 'bgcc_settings');
    add_settings_section('bgcc_section', 'Background Color Settings', 'bgcc_section_cb', 'bg-color-changer');
    add_settings_field('bgcc_field', 'Background Color', 'bgcc_field_cb', 'bg-color-changer', 'bgcc_section');
}
add_action('admin_init', 'bgcc_settings_init');

function bgcc_section_cb() {
    echo '<p>Select the background color for the website.</p>';
}

function bgcc_field_cb() {
    $options = get_option('bgcc_settings');
    ?>
    <input type="text" name="bgcc_settings[bgcc_field]" value="<?php echo $options['bgcc_field']; ?>">
    <?php
}

function bgcc_enqueue_scripts() {
    wp_enqueue_style('bgcc-style', plugins_url('bg-color-changer.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'bgcc_enqueue_scripts');

function bgcc_custom_css() {
    $options = get_option('bgcc_settings');
    ?>
    <style type="text/css">
        body {
            background-color: <?php echo $options['bgcc_field']; ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'bgcc_custom_css');
