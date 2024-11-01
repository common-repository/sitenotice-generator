<?php 

/*
Plugin Name: Sitenotice Generator
Plugin URI: http://www.joeyday.com
Description: Causes a sitenotice to appear in the header.
Version: 1.0 alpha 1
Author: Joey Day
Author URI: http://www.joeyday.com
*/

// Add some default options if they don't already exist.
add_option('sitonotice_enabled', TRUE, 'If enabled, displays the sitenotice; if disabled, hides the sitenotice.');
add_option('sitenotice_message', "Congratulations, you've successfully installed the Sitenotice Generator plugin! To change this message, visit the options section of the admin panel.", 'Message that will be displayed in the sitenotice.');
add_option('sitenotice_exclude', "", "Used to exclude the sitenotice from a specific URI.");

// BEGIN ADMIN CONSOLE
if (! function_exists('sitenotice_add_options')) {
    function sitenotice_add_options() {
        if (function_exists('add_options_page')) {
            add_options_page('Sitenotice Generator Options', 'Sitenotice', 9, basename(__FILE__), 'sitenotice_options_subpanel');
        }
    }
}

function sitenotice_options_subpanel() {
    if ( isset($_POST['info_update']) ) {
        update_option('sitenotice_enabled', $_POST['sitenotice_enabled']);
        update_option('sitenotice_message', $_POST['sitenotice_message']);
        update_option('sitenotice_exclude', $_POST['sitenotice_exclude']);
        ?><div class="updated"><p><strong><?php 
            _e('Options saved.', 'Sitenotice')
        ?></strong></p></div><?php
    } ?>
    <div class="wrap">
        <form method="post">
            <h2>Sitenotice Generator Options</h2>
            <?php // Get values from options table in database.
            $enabled = get_option('sitenotice_enabled');
            $message = stripslashes(get_option('sitenotice_message'));
            $exclude = stripslashes(get_option('sitenotice_exclude')); ?>
            <table class="optiontable">
                <tr valign="top">
                    <th scope="row">Enable:</th>
                    <td>
                        <input type="checkbox" name="sitenotice_enabled" id="sitenotice_enabled"<?php if ($enabled) echo ' checked="checked"'; ?> />
                        <br />
                        Check this to enable the sitenotice message.
                    </td>
                </tr> 
                <tr valign="top"> 
                    <th scope="row">Sitenotice message:</th>
                    <td>
                        <textarea cols="50" rows="3" name="sitenotice_message" id="sitenotice_message"><?php echo $message; ?></textarea>
                        <br />
                        Type the message you would like displayed in the sitenotice.
                    </td>
                </tr>
                <tr valign="top"> 
                    <th scope="row">Sitenotice message:</th>
                    <td>
                        <input type="text" size="50" name="sitenotice_exclude" id="sitenotice_exclude" value="<?php echo $exclude; ?>" />
                        <br />
                        If desired, specify the path to a page you would like to exclude the sitenotice from, starting with the forward slash (i.e. '/about'). Leave blank to disable this feature.
                    </td>
                </tr>
            </table>
            <div class="submit">
            <input type="submit" name="info_update" value="<?php
                _e('Update options', 'Sitenotice')
            ?> &raquo;" /></div>
        </form>
    </div><?php
}
// END ADMIN CONSOLE

function get_the_sitenotice() {
    if ( get_option('sitenotice_enabled') && $_SERVER['REQUEST_URI'] != stripslashes(get_option('sitenotice_exclude')) ) { ?>
    <div id="sitenotice">
        <div>
            <?php echo do_textile(stripslashes(get_option('sitenotice_message'))); ?>
        </div>
    </div>
<?php }
}

// Load the Options page into the Admin Console
add_action('admin_menu', 'sitenotice_add_options');