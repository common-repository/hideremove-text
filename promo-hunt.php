<?php

/**
 * Plugin Name: Promotion Hunt
 * Plugin URI: http://www.trivetechnology.com/
 * Description: Hide small gifts like promo codes on your website and send your visitors on a hunt.
 * Version: 1.0
 * Author: Trive Technology B.V.
 * Author URI: http://www.trivetechnology.com
 * License: GPLv2 or later
 */

function promo_hunt_register_settings()
{
    add_option('promo_hunt_title', '');
    register_setting('promo_hunt_group', 'promo_hunt_title', 'promo_hunt_callback');
    add_option('promo_hunt_message', 'Congratulations! You found the gift!');
    register_setting('promo_hunt_group', 'promo_hunt_message', 'promo_hunt_callback');
    add_option('promo_image', '');
    register_setting('promo_hunt_group', 'promo_image', 'promo_hunt_callback');
}
add_action('admin_init', 'promo_hunt_register_settings');

function promo_hunt_register_options_page()
{
    add_options_page('Promo Hunt', 'Promo Hunt', 'manage_options', 'promo_hunt', 'promo_hunt_options_page');
}
add_action('admin_menu', 'promo_hunt_register_options_page');

function promo_hunt_options_page()
{
?>
    <div class="wrap">
        <h2>Promo Hunt</h2>
        <h4>Send your users on a hunt for small gifts throughout your website!</h4>
        <hr>
        <div id="promo_hunt_copy_box">
            <p><i>Copy this shortcode and paste it into your post, page, or text widget content:</i></p>
            <p id="promo_hunt_copy_shortcode">[promo_hunt]</p>
            <button id="promo_hunt_copy_shortcode_button" onclick="promo_hunt_copy_shortcode()">Copy</button>
        </div>
        <hr>
        <form method="post" action="options.php">
            <?php settings_fields('promo_hunt_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="promo_hunt_title">Promo title</label></th>
                    <td><input type="text" id="promo_hunt_title" name="promo_hunt_title" value="<?php echo get_option('promo_hunt_title'); ?>" />
                        <p class="description">Put in the title that users will see when they find the treasure. </p>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="promo_hunt_message">Promo message</label></th>
                    <td><?php echo wp_editor(get_option('promo_hunt_message'), 'promo_hunt_message', array('textarea_name' => 'promo_hunt_message')); ?>
                        <p class="description">Put in the message that users will see when they find the treasure. </p>
                    </td>
                </tr>
            </table>
            <hr>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="promo_hunt_code">Choose icon</label></th>
                    <td><input id="visa" type="radio" name="promo_image" value="1" <?php checked('1', get_option('promo_image'), true); ?> />
                        <img style="width: 50px; margin-right: 10px;" src="<?php echo plugin_dir_url(__FILE__) . 'images/egg2.png' ?>" /></td>
                    <td><input id="mastercard" type="radio" name="promo_image" value="2" <?php checked('2', get_option('promo_image'), true); ?> />
                        <img style="width: 50px; margin-right: 10px;" src="<?php echo plugin_dir_url(__FILE__) . 'images/gift1.png' ?>" /></td>
                    <td><input id="mastercard" type="radio" name="promo_image" value="3" <?php checked('3', get_option('promo_image'), true); ?> />
                        <img style="width: 50px; margin-right: 10px;" src="<?php echo plugin_dir_url(__FILE__) . 'images/star1.png' ?>" /></td>
                </tr>
            </table>
            <hr>
            <p>Save your changes and test the gift below</p>
            <p style="display: flex;">Click here --> <?php echo do_shortcode("[promo_hunt]"); ?></p>
            <?php submit_button(); ?>
            <p class="description">Created by <a target="_blank" href="https://trivetechnology.com">Trive Technology B.V.</a></p>
    </div>
<?php
}

function promo_hunt_short_code($atts)
{
    if (get_option('promo_image') === '1') {
        $url = plugin_dir_url(__FILE__) . 'images/egg2.png';
    }
    if (get_option('promo_image') === '2') {
        $url = plugin_dir_url(__FILE__) . 'images/gift1.png';
    }
    if (get_option('promo_image') === '3') {
        $url = plugin_dir_url(__FILE__) . 'images/star1.png';
    }

    $Content = '<img class="promo_hunt_image" id="promo_hunt_image" src="' . $url . '" />';

    return $Content;
}

add_shortcode('promo_hunt', 'promo_hunt_short_code');

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
add_action('admin_enqueue_scripts', 'callback_for_setting_up_scripts');
function callback_for_setting_up_scripts()
{
    wp_register_style('promo_hunt_style', plugin_dir_url(__FILE__) . 'style/index.css');
    wp_enqueue_style('promo_hunt_style');
    wp_enqueue_script('promo_hunt_script', plugin_dir_url(__FILE__) . 'script/index.js');
    wp_enqueue_script('promo_hunt_script', plugin_dir_url(__FILE__) . 'script/confetti.js');
}

function your_function()
{
    $promo_title = get_option('promo_hunt_title');
    $promo_text = get_option('promo_hunt_message');
    echo '<!-- The Modal -->
    <div id="promo_hunt_modal" class="promo_hunt_modal">
    
      <!-- Modal content -->
      <div class="promo_hunt_modal_content">
        <span class="promo_hunt_close">&times;</span>
        <h3>' . $promo_title . '</h3>
        <hr>
        <p>' . $promo_text . '</p>
      </div>
    
    </div>';;
}
add_action('wp_footer', 'your_function');
