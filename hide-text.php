<?php

/**
 * Plugin Name: Hide/Remove Text
 * Plugin URI: http://www.trivetechnology.com/
 * Description: Hide or remove emails, phone-numbers and anything else you can think of from your posts.
 * Version: 1.0
 * Author: Trive Technology B.V.
 * Author URI: http://www.trivetechnology.com
 * License: GPLv2 or later
 */

function hide_text_register_settings()
{
    add_option('hide_text_regex', '');
    register_setting('hide_text_options_group', 'hide_text_regex', 'hide_text_callback');

    add_option('hide_text_email', '0');
    register_setting('hide_text_options_group', 'hide_text_email', 'hide_text_callback');

    add_option('hide_text_url', '0');
    register_setting('hide_text_options_group', 'hide_text_url', 'hide_text_callback');

    add_option('hide_text_replace_text', '[hidden]');
    register_setting('hide_text_options_group', 'hide_text_replace_text', 'hide_text_callback');

    add_option('hide_text_admin_hidden', '');
    register_setting('hide_text_options_group', 'hide_text_admin_hidden', 'hide_text_callback');

    add_option('hide_text_phone_number', '0');
    register_setting('hide_text_options_group', 'hide_text_phone_number', 'hide_text_callback');
}
add_action('admin_init', 'hide_text_register_settings');

function hide_text_register_options_page()
{
    add_options_page('Select what to hide on your website', 'Hide/Remove Text', 'manage_options', 'hide_text', 'hide_text_options_page');
}
add_action('admin_menu', 'hide_text_register_options_page');

function hide_text_options_page()
{
?>
    <div class="wrap">
        <h2>Hide/Remove Text</h2>
        <p>Easily hide/remove e-mails, urls, phone numbers and other text from your users. The actual text is never removed; it is just hidden from website visitors. </p>
        <hr>
        <form method="post" action="options.php">
            <?php settings_fields('hide_text_options_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="hide_text_replace_text">Hidden label:</label></th>
                    <td><input type="text" id="hide_text_replace_text" style="font-weight: bold;" name="hide_text_replace_text" value="<?php echo get_option('hide_text_replace_text'); ?>" />
                        <p class="description">eg. [hidden], leave empty to completely remove the text </p>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="hide_text_admin_hidden">Hide text for administrators:</label></th>
                    <td><input type="checkbox" id="hide_text_admin_hidden" name="hide_text_admin_hidden" value="1" <?php checked('1', get_option('hide_text_admin_hidden')); ?> />
                        <p class="description">Select this option if the text should be hidden for administrators as well </p>
                    </td>
                </tr>
            </table>
            <hr>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="hide_text_email">Hide e-mails:</label></th>
                    <td><input type="checkbox" id="hide_text_email" name="hide_text_email" value="1" <?php checked('1', get_option('hide_text_email')); ?> />
                        <p class="description">Select this option if all emails should be hidden/removed </p>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="hide_text_url">Hide urls:</label></th>
                    <td><input type="checkbox" id="hide_text_url" name="hide_text_url" value="1" <?php checked('1', get_option('hide_text_url')); ?> />
                        <p class="description">Select this option if all urls should be hidden/removed </p>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="hide_text_url">Hide phone-numbers:</label></th>
                    <td><input type="checkbox" id="hide_text_phone_number" name="hide_text_phone_number" value="1" <?php checked('1', get_option('hide_text_phone_number')); ?> />
                        <p class="description"><b>Experimental: </b>Select this option if all phone numbers should be hidden/removed </p>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="hide_text_regex">Hide RegEx:</label></th>
                    <td><input type="text" id="hide_text_regex" name="hide_text_regex" value="<?php echo get_option('hide_text_regex'); ?>" />
                        <p class="description">Select custom regular expression to hide/remove. eg. /\b(\w*wordtohide\w*)\b/ </p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
            <p class="description">Created by <a target="_blank" href="https://trivetechnology.com">Trive Technology B.V.</a></p>
        </form>
    </div>
<?php
}

function hide_replace_text($content)
{
    if (current_user_can('administrator') && !get_option('hide_text_admin_hidden')) {
        return $content;
    }

    $replacement = "<b>" . get_option('hide_text_replace_text') . "</b>";
    if (get_option('hide_text_regex')) {
        $hide_text_regex = get_option('hide_text_regex');
        if (@preg_replace($hide_text_regex, $replacement, $content)) {
            $content = preg_replace($hide_text_regex, $replacement, $content);
        }
    }

    if (get_option('hide_text_email'))
        $content = preg_replace("/[^@\s]*@[^@\s]*\.[^@\s]*/", $replacement, $content);

    if (get_option('hide_text_url'))
        $content = preg_replace("/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i", $replacement, $content);

    if (get_option('hide_text_phone_number'))
        $content = preg_replace("/(([+][(]?[0-9]{1,3}[)]?)|([(]?[0-9]{4}[)]?))\s*[)]?[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s\.]?[0-9]{3})([-\s\.]?[0-9]{3,4})/", $replacement, $content);

    return $content;
}
add_action('the_content', 'hide_replace_text');
