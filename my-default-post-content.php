<?php
/*
Plugin Name: My Default Post Content
Version: 0.1
Plugin URI: http://mrdenny.com/go/DefaultPostContent
Description: Sets default title and content for new blog posts.
Author: Denny Cherry
Author URI: http://mrdenny.com/
*/

function defaultpostcontent_activation() {

    // Default options
    $defaultpostcontent_options = array (
	'title' => '',
	'content' => '',
	'donate' => ''
    );
}


// Register settings, add sections and fields
function defaultpostcontent_admin_init(){
    register_setting( 'defaultpostcontent_options', 'defaultpostcontent_options', 'defaultpostcontent_validate' );
    add_settings_section('defaultpostcontent_main', __( 'Settings', '' ), 'defaultpostcontent_section', 'defaultpostcontent');
    add_settings_field('title', __( 'Default Post Title: ', '' ), 'defaultpostcontent_title', 'defaultpostcontent', 'defaultpostcontent_main');
    add_settings_field('content', __( 'Default Post Content: ', ''), 'defaultpostcontent_content', 'defaultpostcontent', 'defaultpostcontent_main');

// This setting should always be last. Don't move it up.
    add_settings_field('donate', __( '', ''), 'defaultpostcontent_donate', 'defaultpostcontent', 'defaultpostcontent_main');
}

function defaultpostcontent_section() {
    echo '<p>' . __( 'Please enter your default blog post settings.', '' ) . '</p>';
}

function defaultpostcontent_menu() {
     add_submenu_page('options-general.php', 'My Default Post Content Settings', 'My Default Post Content', 
'manage_options', 'defaultpostcontent', 'defaultpostcontent_options_page');

}

// Display options page
function defaultpostcontent_options_page() {
    ?>
    <div class="wrap">
    <h2><?php _e('Default Settings', TEXT_DOMAIN ); ?></h2>
        
        <form action="options.php" method="post">
            <?php settings_fields('defaultpostcontent_options'); ?>
            <?php do_settings_sections('defaultpostcontent'); ?>
            <p class="submit">
                <input name="submit" type="submit" class="button-primary" value="<?php _e('Save Changes', TEXT_DOMAIN ); ?>" />
            </p>
        </form>
    </div>
    <?php
}


function defaultpostcontent_title () {
	$options = get_option('defaultpostcontent_options');
	echo "<input id='title' name='defaultpostcontent_options[title]' type='text' class='regular-text' value='{$options['title']}' />";
}

function defaultpostcontent_content () {
	$options = get_option('defaultpostcontent_options');
	echo "<textarea id='content' name='defaultpostcontent_options[content]' rows='5' cols='60'/>{$options['content']}</textarea>";
}

function defaultpostcontent_donate() {
    $options = get_option('defaultpostcontent_options');
    if (empty($options['donate'])) {
        echo "<input id='donate' name='defaultpostcontent_options[donate]' type='checkbox' value='yes'/> I have <a href=\"http://mrdenny.com/go/DefaultPostContent\">donated</a> to the support of this plugin.";
    } else {
        echo "<input id='donate' name='defaultpostcontent_options[donate]' type='hidden' value='yes'/>";
    }

}

function defaultpostcontent_validate( $input) {

	return $input;
}

function return_defaultpostcontent_title($content) {
	$options = get_option('defaultpostcontent_options');
	$content = $options['title'];

	return $content;
}

function return_defaultpostcontent_content ($content) {
	$options = get_option('defaultpostcontent_options');
	$content = $options['content'];

	return $content;
}

// Add "Settings" link to the plugins page

function defaultpostcontent_pluginmenu ($links, $file) {
    if ( $file != plugin_basename( __FILE__ ))
        return $links;

	$options = get_option('defaultpostcontent_options');
	if (empty($options['donate'])) {
		$links[] = '<a href="http://mrdenny.com/go/DefaultPostContent">' . __('Donate','') . '</a>';
	}

	return $links;
}

function defaultpostcontent_action_links( $links, $file ) {
    if ( $file != plugin_basename( __FILE__ ))
        return $links;

    $settings_link = sprintf( '<a href="options-general.php?page=defaultpostcontent">%s</a>', __( 'Settings', '' ) );

    array_unshift( $links, $settings_link );

    return $links;
}





register_activation_hook(__FILE__,'defaultpostcontent_activation');


add_filter( 'default_title', 'return_defaultpostcontent_title' );
add_filter( 'default_content', 'return_defaultpostcontent_content' );
add_filter('plugin_action_links', 'defaultpostcontent_action_links',10,2);
add_filter('plugin_row_meta', 'defaultpostcontent_pluginmenu',10,2);

add_action('admin_init','defaultpostcontent_admin_init');
add_action('admin_menu', 'defaultpostcontent_menu');
