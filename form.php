<?php

$hpb_formmain_result = 0;

function hpb_form_page() {
	wp_enqueue_style('hpb_dashboard_admin', HPB_PLUGIN_URL.'/hpb_dashboard_admin.css');
?>
	<div id="hpb_dashboard_body">
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	<div id="hpb_dashboard_title" class="wrap"><h2><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/icon_hpb.png';?>">フォーム設定</h2></div>
	<p>サイトに設置する問い合わせフォームの宛先や、入力画面に表示されるメッセージの設定を行います。</p>
	<div class="hpb_caption">送信先</div>
	<p>
		<label for="hpb_form_mail_address" class="label">送信先メールアドレス</label>
		<input size="60" type="text" name="hpb_form_mail_address" id="hpb_form_mail_address" value="<?php echo get_option('hpb_form_mail_address'); ?>"/>
	</p>
	<div class="hpb_caption">メッセージ表示</div>
	<div class="hpb_form_editor">
		<label for="hpbformreplyeditormust" class="label">必須項目が未入力の場合</label>
		<div class="wp-editor-wrap">
<?php
	$content_reply_must = hpb_form_get_reply_must();
	$settings = array(
        'textarea_name' => 'hpb_form_reply_must', 
	'textarea_rows' => '10',
        );
	wp_editor( $content_reply_must, 'hpbformreplyeditormust', $settings);
?>
		</div>
	</div>
	<div class="hpb_form_editor">
	
		<label for="hpbformreplyeditor" class="label">送信が成功した場合</label>
		<div class="wp-editor-wrap">
<?php
	$content_reply = hpb_form_get_reply();
	$settings = array(
        'textarea_name' => 'hpb_form_reply', 
	'textarea_rows' => '10',
        );
	wp_editor( $content_reply, 'hpbformreplyeditor', $settings);
?>
		</span>
	</div>

	<div class="hpb_form_editor">
		<label for="hpbformreplyeditor" class="label">送信が失敗した場合</label>
		<div class="wp-editor-wrap">
<?php
	$content_reply_error = hpb_form_get_reply_error();
	$settings = array(
        'textarea_name' => 'hpb_form_reply_error', 
	'textarea_rows' => '10',
        );
	wp_editor( $content_reply_error, 'hpbformreplyeditorerror', $settings);
?>
		</div>
	</div>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="hpb_form_mail_address, hpb_form_reply, hpb_form_reply_error, hpb_form_reply_must" />
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('設定を保存する') ?>" />
	</p></form>
	</div>
<?php
}

function hpb_form_get_reply() {
	return get_option( 'hpb_form_reply', '<div id="hpb_form_reply"><p>ありがとうございます。お問い合わせメールを送信いたしました。</p><p>弊社担当者が確認いたします。</p></div>' );
}

function hpb_form_get_reply_error() {
	return get_option( 'hpb_form_reply_error', '<div id="hpb_form_reply"><p>送信に失敗しました。</p><p>時間をおいて再度送信いただくか、電話にてお問い合わせいただけますようお願いいたします。</p></div>' );
}

function hpb_form_get_reply_must() {
	return get_option( 'hpb_form_reply_must', '<div id="hpb_form_reply"><p>入力していない必須項目があります。</p></div>' );
}

function hpb_form_the_content( $contents ) {
	if( isset( $_POST['hpb_plugin_form_submit'] ) ) {
		global $hpb_formmain_result;
		if( $hpb_formmain_result == 2) {
			$contents = hpb_form_get_reply_must().$contents;
		} else if ( $hpb_formmain_result == 1 ) {
			$contents = hpb_form_get_reply().$contents;
		} else if ( $hpb_formmain_result == -1 ) {
			$contents = hpb_form_get_reply_error().$contents;
		}
	}
	return $contents;
}

function hpb_form_enqueue_style() {
	wp_enqueue_script('jquery');
}

function hpb_head_form() {
?>
<script type="text/javascript"><!--
jQuery(document).ready(function(){
if (jQuery("[name='hpb_plugin_form']").find("[required]")[0]) {
required_param = "";
jQuery("[name='hpb_plugin_form']").find("[required]").each(function(){
	if(required_param != ""){
		required_param += ","; 
	}
	required_param += jQuery(this).attr("name");
});
inputHidden = jQuery("<input>").attr("type", "hidden").attr("name", "hpb_required").attr("value", required_param);
jQuery("[name='hpb_plugin_form']").append(inputHidden);
}});
--></script>
<?php

	if( isset( $_POST['hpb_plugin_form_submit'] ) ) {
		global $hpb_formmain_result;
		$subject = 'お問い合わせ';
		$to = get_option( 'hpb_form_mail_address' );
		if( $to != '' ) {						
 			$hpb_array_required = explode( ',', $_POST["hpb_required"]);
			$header = 'From: '. $_POST[ 'email' ];
			$post = $_POST;
			$body = '';
			foreach( $post as $key=>$value ) {
				if( $key == 'hpb_required' || $key == 'hpb_plugin_form_submit' ) {
					continue;
				}	
				foreach( $hpb_array_required as $hpb_name_required ) {
					if( $hpb_name_required == $key && $value == '' ) {
						$hpb_formmain_result = 2;
						return;
					} 
				}
				$body = $body.$key.' : '.$value."\n";
			}
			if( mb_send_mail( $to,$subject, $body,$header ) ) {
				$hpb_formmain_result = 1;
			} else {
				$hpb_formmain_result = -1;
			}
		}
	}
}

?>