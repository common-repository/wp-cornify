<?php
/**
 * WP Cornify Plugin
 * A simple plugin to activate an easter egg such as Cornify in your WordPress site when one enters the "Konami code"
 *
 * Thanks to Paul Irish for the original code available at
 * http://paulirish.com/2009/cornify-easter-egg-with-jquery/
 **

Plugin Name: WP Cornify
Plugin URI: http://www.moufette.com/wp-cornify/
Description: A simple plugin to activate an easter egg such as Cornify in your WordPress site when one enters the "Konami code"
Version: 0.2.0
Author: Thomas Ward [Jouva] <jouva@moufette.com>
Author URI: http://www.moufette.com/

	Copyright 2009 Thomas Ward [Jouva] <jouva@moufette.com>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Pre-2.6 compatibility
if(!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if(!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

$pluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
$plugindir = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

$script = array(
	'cornify'	=> 'cornify.js',
	'nippleit'	=> 'nippleit.js',
	'ninjafy'	=> 'ninjafy.js',
	'sharkify'	=> 'sharkify.js',
	'hoffify'	=> 'hoffify.js'
);

function wpcornify_admin_panels() {
	$hookname = add_options_page(__('WP Cornify options', 'wp-cornify'), __('WP Cornify', 'wp-cornify'), 8, 'wp-cornify', 'config_page');
	add_filter('plugin_action_links', 'wpcornify_admin_hook', 10, 2);
}

function wpcornify_admin_hook($links, $file) {
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

	if($file == $this_plugin) {
		$links[] = '<a href="options-general.php?page=wp-cornify.php">' . __('Settings') . '</a>';
	}
	return $links;
}

function config_page() {
	if ($_POST['action'] == 'update')
		update_option('wpcornify_type', $_POST['wpcornify_type']);

	$wpcornify_type = get_option('wpcornify_type');
?>
<div class="wrap">
	<h2><?php _e('WP Cornify Options', 'wp-cornify') ?></h2>

	<form name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>&updated=true">
		<?php wp_nonce_field('wpcornify_update-options'); ?>
		<table class="form-table">

			<!-- wpcornify_type -->
			<tr valign="top">
			<th scope="row">
				<label for="wpcornify_type"><?php _e('Type of easter egg', 'wp-cornify') ?></label>
			</th>
			<td>
				<select name="wpcornify_type" id="wpcornify_type">
<?
	$type = array(
			'cornify'	=> 'Cornify',
			'nippleit'	=> 'Nipple It!',
			'ninjafy'	=> 'ninjafy!',
			'sharkify'	=> 'Sharkify',
			'hoffify'	=> 'Hoffify',
			'random'	=> 'Random'
			);
	foreach ($type as $type_key => $type_value) {
?>
					<option value="<?php echo $type_key; ?>"<?php if($wpcornify_type == $type_key) { echo ' selected'; } ?>><?php echo $type_value; ?></option>
<?
	}
?>
				</select>
			<!-- <p><small><?php _e('Only enable Konami code input on the homepage', 'wp-konami') ?></small></p> -->
</td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="wpcornify_type" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'wp-cornify') ?>" />
</p>

</form>
</div>
<?
}

add_option('wpcornify_type', 'cornify');

if(is_admin())
	add_action('admin_menu', 'wpcornify_admin_panels');

$wpcornify_type = get_option('wpcornify_type');

if($wpcornify_type == 'random')
	$wpcornify_type = array_rand($script);

wp_enqueue_script($wpcornify_type, $pluginpath . $script[$wpcornify_type], array('jquery'), '0.2');
?>