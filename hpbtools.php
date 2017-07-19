<?php
/*
Plugin Name: hpb Dashboard
Plugin URI:http://www.justsystems.com/jp/links/hpb/wppdf.html?p=hpb20_wp_hpbdash 
Description: ホームページビルダーが提供するプラグインです。hpbダッシュボードが追加されます。
Version: 1.3.1
Author: JustSystems
Author URI:http://www.justsystems.com/jp/links/hpb/creator.html?p=hpb20_wp_hpbdash
License URI: license.txt
Text Domain: hpbtool
*/

define( 'HPB_PLUGIN_DIR', WP_PLUGIN_DIR.'/hpbtool' );
define( 'HPB_PLUGIN_URL', WP_PLUGIN_URL.'/hpbtool' );
define( 'HPB_PLUGINDATA_DIR', WP_PLUGIN_DIR.'/hpbtooldata' );

require_once HPB_PLUGIN_DIR.'/social_buttons.php';
require_once HPB_PLUGIN_DIR.'/form.php';
require_once HPB_PLUGIN_DIR.'/access_analysis.php';
require_once HPB_PLUGIN_DIR.'/google_analytics.php';
require_once HPB_PLUGIN_DIR.'/import.php';

add_action( 'admin_menu' , 'hpb_option' );
 
function hpb_option() {
	$icon_url = HPB_PLUGIN_URL.'/image/admin/menu_hpb.png';	add_menu_page( 'HPBTOOL', 'hpbダッシュボード', 'level_1', 'hpb_main', 'hpb_admin_home', $icon_url, 3 );
	add_submenu_page( 'hpb_main', 'ホーム', 'ホーム', 'level_1', 'hpb_main', 'hpb_admin_home' );
	add_submenu_page( 'hpb_main', 'ソーシャルボタン設定', 'ソーシャルボタン設定', 'administrator', 'hpb_social_page', 'hpb_social_page' );
	add_submenu_page( 'hpb_main', 'フォーム設定', 'フォーム設定', 'administrator', 'hpb_form_page', 'hpb_form_page' );
	add_submenu_page( 'hpb_main', 'アクセス解析設定', 'アクセス解析設定', 'administrator', 'hpb_access_analysis_page', 'hpb_access_analysis_page' );
	add_submenu_page( 'hpb_main', 'Google アナリティクス設定', 'Google アナリティクス設定', 'administrator', 'hpb_google_analytics_page', 'hpb_google_analytics_page' );
	add_submenu_page( 'hpb_main', 'オプション', 'オプション', 'administrator', 'hpb_dashborad_widget_option', 'hpb_dashborad_widget_option' );		
}

function hpb_admin_home() {
?>
<div id="hpb_dashboard_body">
<?php
	if( isset( $_POST['hpb-update-data'] ) && hpb_is_admin_level() ) {
		hpb_import_list_page();
	} else {
		echo '<div id="hpb_dashboard_title" class="wrap"><h2><img src="'.HPB_PLUGIN_URL.'/image/admin/icon_hpb.png">hpbダッシュボード</h2></div>';
		if( hpb_is_admin_level() ) {
			hpb_plugin_update();
			hpb_import_page();
			hpb_install_seo_plugin();
		}
		hpb_guidance_activate_multibyte_patch();
		hpb_guidance_new_post();
		hpb_dashboard_widget_function();
	}
?>
</div>
<?php
}

function hpb_plugin_update() {
	$update_plugins = get_plugin_updates();
	foreach( (array) $update_plugins as $update_plugin ) {
		if('hpbtool' === $update_plugin->update->slug) {
?>
<form method="post" action="update-core.php?action=do-plugin-upgrade" name="upgrade-plugins">
<?php wp_nonce_field('upgrade-core'); wp_nonce_field('upgrade-core'); ?>
<input type="hidden" name="checked[]" value="hpbtool/hpbtools.php"/><div class="submit hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch2.png';?>" class="hpb_eyecatch">hpbダッシュボード の更新があります。<input id="upgrade-plugins" class="button-primary" type="submit" value="今すぐ更新する" name="upgrade" /></div>	</form>	
<?php
		}
	}
}

function hpb_install_seo_plugin() {
	if( !file_exists( HPB_PLUGINDATA_DIR.'/usercontents.xml' ) ) {
		return;
	}

	$version = '';

	$xml = new XMLReader();
	$xml->open( HPB_PLUGINDATA_DIR.'/usercontents.xml' );
	while ( $xml->read() ) {
		if ( $xml->nodeType == XMLReader::ELEMENT ) {
			$version = $xml->getAttribute( 'version' );
			break;
		}
	}
	$xml->close();

	if( $version == '' ){
		return;
	}
	$vlist = explode( ".", $version );
	$major = intval( $vlist[0] );
	$minor = intval( $vlist[1] );
	if ( $major < 1 || ( $major == 1 && $minor < 1 ) ) {
		return;
	}

	$seo_plugin_exits = false;
	$plugins = get_plugins();
	if ( $plugins ){
		foreach( $plugins as $pfile => $pdata ) {
			if( 'hpbseo/hpbseo.php' == $pfile ) {
				$seo_plugin_exits = true;
				break;
			}
		}
	}
	if ( $seo_plugin_exits == false ) {
?>
<form method="post" action="<?php echo admin_url( 'update.php?action=install-plugin&plugin=hpbseo' )?>" name="install-seo-plugin">
<?php wp_nonce_field('install-plugin_hpbseo'); wp_nonce_field('install-plugin_hpbseo'); ?>
<div class="submit hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">hpb SEOプラグインをインストールしましょう。<input id="install-seo-plugin" class="button-primary" type="submit" value="今すぐインストール" name="install-seo-plugin" /></div>	</form>	
<?php
	} else {
		if( isset($_POST['hpb_activate_seo_plugin']) ) {
			hpb_activate_seo_plugin();
		}
		if( is_plugin_active('hpbseo/hpbseo.php') == false ) {
?>
<div class="hpb_eyecatch_area" class="submit"><form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">hpb SEOプラグインを有効化して使ってみましょう。<input class="button-primary" type="submit" name="hpb_activate_seo_plugin" value="有効化する" /></form></div>
<?php
		} else {
			$update_plugins = get_plugin_updates();
			foreach( (array) $update_plugins as $update_plugin ) {
				if ( 'hpbseo' === $update_plugin->update->slug ) {
?>
<form method="post" action="update-core.php?action=do-plugin-upgrade" name="upgrade-plugins">
<?php wp_nonce_field('upgrade-core'); wp_nonce_field('upgrade-core'); ?>
<input type="hidden" name="checked[]" value="hpbseo/hpbseo.php"/><div class="submit hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch2.png';?>" class="hpb_eyecatch">hpb SEOプラグインの更新があります。<input id="upgrade-seo-plugin" class="button-primary" type="submit" value="今すぐ更新する" name="upgrade" /></div>	</form>	
<?php
				}
			}
		}
	}
}

function hpb_activate_seo_plugin() {
	activate_plugin( 'hpbseo/hpbseo.php');
}

function hpb_guidance_new_post() {
?>
<?php
	if( get_option('hpb_dashboard_guidance', '0') == 1 ) {
		return;
	}
	if( $_GET['hpb_visible'] == 'false' ){
		update_option('hpb_dashboard_guidance', '1');
		return;
	}
	$wp_post_type_names = get_post_types(array('_builtin' => false, 'public' => true), 'objects');
?>
<div id="hpb_guidance">
	<a href="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>&hpb_visible=false" id="hpb_guidance_invisible"></a>
	<div id="hpb_guidance_image"></div>
</div>
<div id="hpb_guidance_step">
	<table><tr><td><div id="hpb_guidance_step1"></div></td><td><div id="hpb_guidance_step2"></div></td></tr>
	<tr><td>
<?php
	if( count($wp_post_type_names) > 0 ) {
?>
	<a href="post-new.php?post_type=<?php echo reset($wp_post_type_names)->name; ?>" id="hpb_guidance_post"></a>
<?php
	} else {
?>
	<a href="post-new.php" id="hpb_guidance_post"></a>
<?php
	}
?>
	</td><td><a href="<?php echo home_url();?>" id="hpb_guidance_view_site" target="_blank"></a></td></tr></table>
</div>
<?php
}

add_action( 'wp_head', 'hpb_head');

function hpb_head() {
	hpb_head_form();
	hpb_head_social();
	hpb_head_google_analytics();
}

add_action('template_redirect', 'hpb_template_redirect');

function hpb_template_redirect() {
	if (get_option('hpb_site_private', 0 ) == 1 && !is_user_logged_in()) { auth_redirect(); }
}
add_action( 'wp_footer', 'hpb_getfooter' );

function hpb_getfooter() {
	hpb_getfooter_access_analysis();
}

add_filter ('wp_default_editor', 'hpb_default_editor');

function hpb_default_editor ($r) {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$reg = '/Android (\d+)\\.(\d+)/';
	$a   = array();
	preg_match($reg, $user_agent, $a);
	if(count($a) >= 2 && ($a[1] == 3 || ($a[1] == 4 && $a[2] == 0))) {
		return "html";
	}
	return $r;
}

add_action('admin_head-post-new.php', 'hpb_header_postnew');
add_action('admin_head-post.php', 'hpb_header_postnew');

function hpb_header_postnew() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$reg = '/OS (\d+)_(\d+)(.+)like Mac OS X/';
	$a   = array();
	preg_match($reg, $user_agent, $a);
	if(count($a) >= 2 && $a[1] == 5){
		wp_enqueue_script('jquery');
?>
<script type="text/javascript">jQuery(document).ready(function($){$('input').focusout(function(){$('iframe#content_ifr').focus();});});</script>
<?php
	}
}

add_action("wp_enqueue_scripts", "hpb_enqueue_style");

function hpb_enqueue_style () {
	hpb_social_enqueue_style();
	hpb_form_enqueue_style();
}

add_filter( 'the_content', 'hpb_the_content' );

function hpb_the_content ( $content ) {
	$content = hpb_form_the_content( $content );
	$content = hpb_social_the_content( $content );
	return $content;
}

function hpb_is_admin_level() {
	if( !is_user_logged_in() ) {
		return false;
	}
	$user = get_userdata( get_current_user_id() );
	$user_level = $user->user_level;
	if( $user_level >= 8 ){
		return true;
	}
	return false;
}

function hpb_is_editor_level() {
	if( !is_user_logged_in() ) {
		return false;
	}
	$user = get_userdata( get_current_user_id() );
	$user_level = $user->user_level;
	if( $user_level >= 5 ){
		return true;
	}
	return false;
}

function hpb_is_author_level() {
	if( !is_user_logged_in() ) {
		return false;
	}
	$user = get_userdata( get_current_user_id() );
	$user_level = $user->user_level;
	if( $user_level >= 2 ){
		return true;
	}
	return false;
}

function hpb_is_subscriber_level() {
	if( !is_user_logged_in() ) {
		return false;
	}
	$user = get_userdata( get_current_user_id() );
	$user_level = $user->user_level;
	if( $user_level >= 1 ){
		return true;
	}
	return false;
}

function hpb_custom_admin_menu () {
	if( !hpb_is_subscriber_level() ){
		return;
	}
	if( get_option('hpb_hide_menus', 1 ) != 1 ) {
		return;
	}
	global $menu;
	$remove_menus = array();
	if( get_option( 'hpb_visible_menu_dashbord', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('ダッシュボード');
	}
	if( get_option( 'hpb_visible_menu_media', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('メディア');
	}
	if( get_option( 'hpb_visible_menu_link', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('リンク');
	}
	if( get_option( 'hpb_visible_menu_themes', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('外観');
	}
	if( get_option( 'hpb_visible_menu_tools', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('ツール');
	}
	if( get_option( 'hpb_visible_menu_users', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('ユーザー');
	}
	if( get_option( 'hpb_visible_menu_options', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('設定');
	}
	if( get_option( 'hpb_visible_menu_plugins', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('プラグイン');
	}
	if( get_option( 'hpb_visible_menu_addpage', 0 ) == 0 ) {
		$remove_menus[count( $remove_menus )] = __('固定ページ');
	}
	end ( $menu );
	while ( prev($menu) ){
		$value = explode( ' ', $menu[key($menu)][0] );
		if( in_array( $value[0] != NULL?$value[0]:"" , $remove_menus ) ) {
			unset( $menu[key($menu)] );
		}
	}
}
add_action('admin_menu', 'hpb_custom_admin_menu');

function hpb_dashborad_widget_option() {
	wp_enqueue_style('hpb_dashboard_admin', HPB_PLUGIN_URL.'/hpb_dashboard_admin.css');
?>
	<div id="hpb_dashboard_body">
	<div id="hpb_dashboard_title" class="wrap"><h2><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/icon_hpb.png';?>"/>オプション</h2></div>
	<p>項目を選択して[設定を保存する]をクリックすると、画面の左側に表示されているメニューが切り替わります。</p>
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	<div class="annotation">※「カスタマイズ項目」については、サポートサービスを行っていません。<br/>「カスタマイズ項目」をONにして使用したり、「すべてのメニュー」を選択した場合は、サポート対象外となりますので、ご了承ください。</div>
	<div id="hpb_menu_customize">
		<div id="hpb_menu_customize_title">メニューのカスタマイズ</div>
		<div id="hpb_menu_customize_option">
			<input type="radio" name="hpb_hide_menus" id="hpb_hide_menus1" value="1" <?php checked( get_option('hpb_hide_menus', 1 ), 1 ); ?> /><label for="hpb_hide_menus1"> かんたんメニュー</label>
			<p class="indent1">サイト更新によく使う機能をまとめたメニューです。WordPressを初めてお使いの方にお勧めです。<br/>用途に応じて、表示する項目をカスタマイズすることもできます。</p>
			<div id="hpb_visible_settings">
			<span class="indent1">カスタマイズ項目:</span><br/>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_dashbord" id="hpb_visible_menu_dashbord" value="1" <?php checked( get_option('hpb_visible_menu_dashbord', 0 ), 1 ); ?>/><label for="hpb_visible_menu_dashbord"><?php echo __('ダッシュボード'); ?></label></div>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_addpage" id="hpb_visible_menu_addpage" value="1" <?php checked( get_option('hpb_visible_menu_addpage', 0 ), 1 ); ?>/><label for="hpb_visible_menu_addpage"><?php echo __('固定ページ'); ?></label></div>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_media" id="hpb_visible_menu_media" value="1" <?php checked( get_option('hpb_visible_menu_media', 0 ), 1 ); ?>/><label for="hpb_visible_menu_media"><?php echo __('メディア'); ?></label></div>
		<?php if( current_user_can('manage_links')){ ?>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_link" id="hpb_visible_menu_link" value="1" <?php checked( get_option('hpb_visible_menu_link', 0 ), 1 ); ?>/><label for="hpb_visible_menu_link"><?php echo __('リンク'); ?></label></div>
		<?php } ?>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_themes" id="hpb_visible_menu_themes" value="1" <?php checked( get_option('hpb_visible_menu_themes', 0 ), 1 ); ?>/><label for="hpb_visible_menu_themes"><?php echo __('外観'); ?></label></div>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_tools" id="hpb_visible_menu_tools" value="1" <?php checked( get_option('hpb_visible_menu_tools', 0 ), 1 ); ?>/><label for="hpb_visible_menu_tools"><?php echo __('ツール'); ?></label></div>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_users" id="hpb_visible_menu_users" value="1" <?php checked( get_option('hpb_visible_menu_users', 0 ), 1 ); ?>/><label for="hpb_visible_menu_users"><?php echo __('ユーザー'); ?></label></div>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_options" id="hpb_visible_menu_options" value="1" <?php checked( get_option('hpb_visible_menu_options', 0 ), 1 ); ?>/><label for="hpb_visible_menu_options"><?php echo __('設定'); ?></label></div>
			<div class="indent1"><input type="checkbox" name="hpb_visible_menu_plugins" id="hpb_visible_menu_plugins" value="1" <?php checked( get_option('hpb_visible_menu_plugins', 0 ), 1 ); ?>/><label for="hpb_visible_menu_plugins"><?php echo __('プラグイン'); ?></label></div>
			</div>
			<p><input type="radio" name="hpb_hide_menus" id="hpb_hide_menus2" value="0" <?php checked( get_option('hpb_hide_menus', 1 ), 0 ); ?> /><label for="hpb_hide_menus2"> すべてのメニュー</label></p>
			<p class="indent1">WordPressのすべての機能が使えます。WordPressを使い慣れている方にお勧めです。</p>
		</div>
	</div>
	<div id="hpb_private_customize">
		<input type="checkbox" name="hpb_site_private" id="hpb_site_private" value="1" <?php checked( get_option('hpb_site_private', 0 ), 1 ); ?>/><label for="hpb_site_private"><?php echo __(' サイトを非公開にする。サイトの閲覧にログインが必要になります。'); ?></label>
	</div>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="hpb_hide_menus, hpb_visible_menu_dashbord, hpb_visible_menu_addpage, hpb_visible_menu_media, hpb_visible_menu_widgets, hpb_visible_menu_link, hpb_visible_menu_themes, hpb_visible_menu_tools, hpb_visible_menu_users, hpb_visible_menu_options, hpb_visible_menu_plugins, hpb_site_private" />
	<br><input type="submit" class="button-primary" value="<?php _e('設定を保存する') ?>" />
	</form>
	</div>
<?php
}

function hpb_dashboard_widget_function() {
	wp_enqueue_style('hpb_dashboard_menu_widget_style', HPB_PLUGIN_URL.'/hpb_dashboard_admin.css');
?>
<p class="hpb_content_head"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/post_new_head.png"/></p>
<table class="hpb_content_table">
<?php
	$wp_post_type_names = get_post_types(array('_builtin' => false, 'public' => true), 'objects');
	$index = 0;
	foreach( $wp_post_type_names as $post_type ) {
		$index += 1;
?>
	<tr><td class="hpb_menu_icon<?php if($index == 1) { echo ' hpb_first_menu'; }?>"><?php echo getPostTypeIconByName($post_type->label); if( isUserDefineType($post_type->label) ) { echo '<div class="hpb_user_define_post">'.$post_type->label.'</div>';}?></td><td <?php if($index == 1) { echo 'class="hpb_first_menu_contents"'; }?>><ul><li class="hpb_menu_item"><a href="post-new.php?post_type=<?php echo $post_type->name; ?>" class="hpb_post_new_btn"></a></li><li class="hpb_menu_item"><a href="<?php echo home_url().'?post_type='.$post_type->name; ?>" class="hpb_view_post_btn" target="_blank"></a></li><li class="hpb_menu_item"><a href="edit.php?post_type=<?php echo $post_type->name; ?>" class="hpb_view_post_list_btn"></a><li></ul></td></tr>
<?php
	}
?>
	<tr><td class="hpb_menu_icon"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/post_blog_icon.png"></td><td><ul><li class="hpb_menu_item"><a href="post-new.php" class="hpb_post_new_btn"></a></li><li class="hpb_menu_item"><a href="<?php echo home_url();?>?post_type=post" class="hpb_view_post_btn" target="_blank"></a></li><li class="hpb_menu_item"><a href="edit.php" class="hpb_view_post_list_btn"></a><li></ul></td><tr>
</table><br>
<?php if( hpb_is_subscriber_level() ) { ?>
<p class="hpb_content_head"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/check_head.png"/></p>
<table class="hpb_content_table"><tr>
	<td class="hpb_menu_icon hpb_first_menu"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/comment_icon.png"></td>
	<td class="hpb_first_menu_contents"><ul><li class="hpb_menu_item"><a href='edit-comments.php?comment_status=moderated' id="<?php $comment_counts = wp_count_comments(); if( $comment_counts->moderated == 0 ) { echo 'hpb_comment_approval_btn3';} elseif($comment_counts->moderated < 100 ) { echo 'hpb_comment_approval_btn';} else { echo 'hpb_comment_approval_btn2';}?>"><span class="hpb_comment_count<?php if( $comment_counts->moderated == 0 ) { echo ' displaynone';} ?>"><?php echo $comment_counts->moderated; ?></span></a></li>
	<li class="hpb_menu_item"><a href='edit-comments.php?comment_status=approved' id="<?php if($comment_counts->approved == 0 ) { echo 'hpb_comment_view_btn3';} elseif($comment_counts->approved < 100 ) { echo 'hpb_comment_view_btn';} else { echo 'hpb_comment_view_btn2';}?>"><span class="hpb_comment_count<?php if( $comment_counts->approved == 0 ) { echo ' displaynone';} ?>"><?php echo $comment_counts->approved; ?></span></a></li></ul></td>
</tr><tr>
	<td class="hpb_menu_icon"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/seo_icon.png"></td>
	<td><ul><li class="hpb_menu_item"><a href='https://kantan-access.com/jana_webapp/login.do' id="hpb_aa_check_btn" target="_blank"></a></li></ul></td>
</tr></table>
<?php } ?>
<div class="hpb_clearboth"></div></td>
<p class="hpb_content_head"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/settings_head.png"/></p>
<table class="hpb_content_table">
<?php if( hpb_is_admin_level() ) { ?>
<tr><td class="hpb_menu_icon hpb_first_menu"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/option_icon.png"/></td>
<td class="hpb_first_menu_contents"><ul class="hpb_option_settings_category">
	<li class="hpb_option_menu_item"><a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=hpb_main&page=hpb_social_page'; ?>" id="hpb_socialbutton_settings"></a></li>
	<li class="hpb_option_menu_item"><a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=hpb_main&page=hpb_access_analysis_page'; ?>" id="hpb_aa_settings"></a></li>
	<li class="hpb_option_menu_item"><a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=hpb_main&page=hpb_form_page'; ?>" id="hpb_form_settings"></a></li>
	<li class="hpb_option_menu_item"><a href="widgets.php" id="hpb_layout_settings"></a></li>
	<li class="hpb_option_menu_item"><a href="<?php echo get_option('siteurl') . '/wp-admin/admin.php?page=hpb_main&page=hpb_dashborad_widget_option'; ?>" id="hpb_option_settings"></a></li>	
</ul>
<div class="hpb_clearboth"></div></td></tr>
<?php  } ?>
<tr><td class="hpb_menu_icon"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/admin/help_icon.png"/></td>
<td><ul class="hpb_option_settings_category hpb_menu_list">
	<li class="hpb_option_setting hpb_option_menu_item"><a href="http://www.justsystems.com/jp/links/hpb/wppdf.html?p=hpb20_wp_hpbdash" id="hpb_help" target="_blank"></a></li>
	<li class="hpb_option_setting hpb_option_menu_item"><a href="http://support.justsystems.com/jp/app/servlet/productslink?apl=hpb20" id="hpb_faq" target="_blank"></a></li>
</ul>
<div class="hpb_clearboth"></div></td></tr></table>
<?php
global $wp_version;
$plugin_data = get_plugin_data(__FILE__);
?>
<div id="version_info"><p>WordPressのバージョン：<?php echo $wp_version;?></p>
<p>プラグインのバージョン：<?php echo $plugin_data['Version'];?></p></div>
<?php
} 

function isUserDefineType ( $post_title ) {

	switch( $post_title ) {
		case 'ニュース':
		case '製品':
		case 'サービス':
		case '活動':
		case 'FAQ':
		case '商品':
		case 'メニュー':
		case '物件':
			return 0;
		default:
			return 1;
	} 
	return 0;	
}

function getPostTypeIconByName( $post_title ) {
	switch( $post_title ) {
		case 'ニュース':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_news_icon.png"/>';
			break;
		case '製品':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_product_icon.png"/>';
			break;
		case 'サービス':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_service_icon.png"/>';
			break;
		case '活動':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_activity_icon.png"/>';
			break;
		case 'FAQ':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_faq_icon.png"/>';
			break;
		case '商品':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_goods_icon.png"/>';
			break;
		case 'メニュー':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_menu_icon.png"/>';
			break;
		case '物件':
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_property_icon.png"/>';
			break;
		default:
			$hpb_post_icon = '<img src="'.HPB_PLUGIN_URL.'/image/admin/post_user_icon.png"/>';
	} 
	return $hpb_post_icon;
}

function redirect_dashiboard() {
	if( !hpb_is_subscriber_level() ){
		return;
	}
	if ( get_option('hpb_hide_menus', 1 ) == 1 && basename($_SERVER['REQUEST_URI']) == 'wp-admin' ) {
		wp_redirect(get_option('siteurl') . '/wp-admin/admin.php?page=hpb_main');
		exit;
	}
}
add_action( 'init', 'redirect_dashiboard' );

add_action( 'admin_init', 'hpb_plugin_admin_init' );

function hpb_plugin_admin_init() {
	hpb_plugin_admin_init_import();
}

function hpb_custom_editor_settings( $initArray ){
    $initArray['theme_advanced_buttons1'] = 'bold,italic,strikethrough,underline,|,|,bullist,numlist,|,|,justifyleft,justifycenter,justifyright,justifyfull,|,|,link,unlink,wp_more,|,|,wp_adv';
    $initArray['theme_advanced_buttons2'] = 'formatselect,|,|, fontsizeselect,forecolor,backcolor,|,|,hr,|,|,charmap,|,|,outdent,indent,|,|,undo,redo';
    return $initArray;
}

add_filter( 'tiny_mce_before_init', 'hpb_custom_editor_settings' );

function change_post_menu_label() {
	if( !hpb_is_subscriber_level() ){
		return;
	}
	global $menu;
	global $submenu;
	if( $menu[5][0] == '投稿' ) {
		$menu[5][0] = 'ブログ';
		$submenu['edit.php'][5][0] = 'ブログ一覧';
	}
}

function change_post_object_label() {
	if( !hpb_is_subscriber_level() ){
		return;
	}
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	if( $labels->name == '投稿' ) {
		$labels->name = 'ブログ';
	}
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );

add_action( 'admin_footer_text', 'custom_admin_footer' );
function custom_admin_footer() {
    echo '<a href="http://www.justsystems.com/jp/links/hpb/wppdf.html?p=hpb20_wp_hpbdash" target="_blank"/>hpbダッシュボードで投稿・集客 PDFマニュアル</a>';
}

add_action( 'admin_print_styles', 'hpb_plugin_admin_styles' );
   
function hpb_plugin_admin_styles() {
	wp_enqueue_style( 'hpbpluginadminstyle', HPB_PLUGIN_URL.'/hpb_plugin_admin_style.css' );
}

function hpb_guidance_activate_multibyte_patch() {
if( isset($_POST['hpb_activate_multibyte_patch']) ) {
	hpb_activate_multibyte_patch();
}
if( is_plugin_active('wp-multibyte-patch/wp-multibyte-patch.php') == false ) {
?>
<div class="hpb_eyecatch_area" class="submit"><form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">WordPressを日本語環境で正しく動作させるために WP Multibyte Patch プラグインを必ず有効化しましょう。<input class="button-primary" type="submit" name="hpb_activate_multibyte_patch" value="有効化する" /></form></div>
<?php
}
}

function hpb_activate_multibyte_patch() {
	activate_plugin( 'wp-multibyte-patch/wp-multibyte-patch.php');
}

?>