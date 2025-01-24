<?php
/* 
 * A dynamic wp-admin page for quickly creating settings in wp_options
 * Do a search/replace for "ajas" to a custom prefix for plugin
 * Option names should contain the work "ENABLE" to turn them into checkboxes
 */

// Register settings and sections
function ajasSET_register_settings() {
    $settings = [
        'section_name' => [
            'title'       => 'SECTION TITLE',
            'description' => 'SECTION DESCRIPTION',
            'fields'      => [
                'WP_OPTION_NAME'             => [
                    'label' => 'SETTING NAME',
                    'description_long' => 'SETTING DESCRIPTION',
                    'dependency' => 'WP_OPTION_NAME', //only show this if this option is true/1
                    'do_show' => true, //whether to display this on the admin page
                ]        
            ],
        ],
        
    ];

    foreach ($settings as $section_key => $section) {
        add_settings_section(
            $section_key,
            $section['title'],
            function () use ($section) {
                echo "<p>{$section['description']}</p>";
            },
            'ajas_options'
        );

        foreach ($section['fields'] as $field_key => $field_value) {
            if (isset($field_value['do_show']) && !$field_value['do_show']) {
                continue;
            }
            
            $field_label = $field_value['label'];
            $long_description = isset($field_value['description_long']) ? $field_value['description_long'] : '';
            $dependency = isset($field_value['dependency']) ? $field_value['dependency'] : null;

            register_setting('ajas_options', $field_key);

            add_settings_field(
                $field_key,
                $field_label,
                function () use ($field_key, $long_description, $dependency) {
                    $is_checkbox = strpos($field_key, 'ENABLE') !== false;
                    $value = get_option($field_key, $is_checkbox ? 0 : '');
                    $dependency_attr = $dependency ? "data-dependency='{$dependency}'" : '';
                    if ($is_checkbox) {
                        echo "<input type='checkbox' name='{$field_key}' value='1' {$dependency_attr}" . checked(1, $value, false) . ">";
                    } else {
                        echo "<input type='text' name='{$field_key}' value='" . esc_attr($value) . "' class='regular-text' {$dependency_attr}>";
                    }
                    if ($long_description) {
                        echo "<p class='description'>{$long_description}</p>";
                    }
                },
                'ajas_options',
                $section_key
            );
        }
    }
}
add_action('admin_init', 'ajasSET_register_settings');

// Render the admin settings page
function ajasSET_render_admin_settings_page() {
    ?>
    <div class="wrap">
        <h1>ADMIN PAGE</h1> <!-- PAge Title, Change Me!!!!-->
        <form method="post" action="options.php">
            <?php
            settings_fields('ajas_options');
            do_settings_sections('ajas_options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Add the admin menu
function ajasSET_add_admin_menu() {
    add_options_page(
        'CHANGE ME', //What displays in the menu, change me!!!!!
        'CHANGE ME', //What displays in the menu, change me!!!!!
        'manage_options',
        'ajas/settings.php',
        'ajasSET_render_admin_settings_page'
    );
}
add_action('admin_menu', 'ajasSET_add_admin_menu');

// Enqueue JavaScript for dependency management
function ajasSET_admin_scripts() {
    /*wp_enqueue_script(
        'ajas-settings-script',
        plugin_dir_url(__FILE__) . 'js/ajas-settings.js', // Adjust the path as needed
        ['jquery'],
        '1.0',
        true
    );*/
}
add_action('admin_enqueue_scripts', 'ajasSET_admin_scripts');

// JavaScript for dependency management
add_action('admin_footer', function () {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            function toggleDependentFields() {
                $('[data-dependency]').each(function () {
                    const $field = $(this);
                    const parentFieldName = $field.data('dependency');
                    const $parentField = $(`[name="${parentFieldName}"]`);

                    if ($parentField.is(':checkbox')) {
                        $field.closest('tr').toggle($parentField.is(':checked'));
                    }
                });
            }

            // Initial toggle
            toggleDependentFields();

            // Update on change
            $('[name]').on('change', function () {
                toggleDependentFields();
            });
        });
    </script>
    <?php
});
