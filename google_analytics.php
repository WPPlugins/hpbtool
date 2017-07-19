<?php

function hpb_google_analytics_page() {

	$hpb_is_setGA = false;
	$hpb_is_clearGA = false;
	$hpb_is_ga = false;
	if (isset($_POST[ 'setGA' ]) && $_POST[ 'setGA' ] == "1") {
		update_option('hpb_tracking_id', trim( $_POST['hpb_tracking_id'] ));
		$hpb_tracking_id =  get_option( 'hpb_tracking_id' );
		if ( $hpb_tracking_id != '' ) {
			if ( strpbrk( $hpb_tracking_id, " \t\r\n'\"<>&" ) == FALSE ) {
				$hpb_response = 'サイトを Google アナリティクス対象に設定しました。';
				$hpb_google_analytics = 1;
			} else {
				$hpb_response = 'トラッキング ID が不正です。';
				$hpb_google_analytics = 0;
			}
		} else {
			$hpb_response = 'トラッキング ID を入力してください。';
			$hpb_google_analytics = 0;
		}
		$hpb_is_setGA = true;
	} elseif (isset($_POST[ 'clearGA' ]) && $_POST[ 'clearGA' ] == "1") {
		update_option('hpb_tracking_id', trim( $_POST['hpb_tracking_id'] ));
		$hpb_response = 'Google アナリティクス対象に設定したサイトを、解析対象から外しました。';
		$hpb_google_analytics = 0;
		$hpb_is_clearGA = true;
	} else {
		$hpb_google_analytics = intval( get_option( 'hpb_google_analytics', 0 ) );
		if ( $hpb_google_analytics != 0 && get_option( 'hpb_tracking_id' ) != '' ) {
			$hpb_is_ga = true;
		}
	}
	wp_enqueue_style('hpb_dashboard_admin', HPB_PLUGIN_URL.'/hpb_dashboard_admin.css');
	wp_enqueue_script( 'jquery' );
?>
	<div id="hpb_dashboard_body">
	<div id="hpb_dashboard_title" class="wrap"><h2><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/icon_hpb.png';?>">Google アナリティクス設定</h2></div>
<?php
	if ($hpb_is_setGA) {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">
<?php
		echo $hpb_response.'</div>';
		if ( $hpb_google_analytics != 0 ) {
			$hpb_is_ga = true;
		}
	} else if ($hpb_is_clearGA) {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">
<?php
		echo $hpb_response.'</div>';
	}
	if( $hpb_is_ga == false ) {
		$chkmessage = 'Google アナリティクスを設定します。よろしいですか？';
	} else {
		$chkmessage = 'Google アナリティクスを解除します。よろしいですか？';
	}
?>
	<form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" autocomplete="off" onsubmit="if( confirm('<?php echo $chkmessage; ?>') ) return true; return false;">
	<?php wp_nonce_field('update-options'); ?>
<?php
	if ( $hpb_is_ga ) {
		if ( $hpb_google_analytics != 0 ) {
			$hpb_is_readonly = true;
		} else {
			$hpb_is_readonly = false;
		}
	} else {
		echo '<p>Google アナリティクスの設定を行い、サイト内の各ページにトラッキングコードを埋め込みます。</p>';
		$hpb_is_readonly = false;
	}
?>
	<div class="hpb_caption">トラッキング ID 設定</div>
	<p>Google アナリティクスのトラッキング ID を入力します。トラッキング ID は、<a href="http://www.google.com/analytics/" target="_blank">Google アカウント</a>から取得できます。
	<div id="hpb_aa_acount_form">
	<p>
		<label for="hpb_tracking_id">トラッキング ID</label>
		<input size="60" type="text" name="hpb_tracking_id" id="hpb_tracking_id" value="<?php echo get_option( 'hpb_tracking_id' ); ?>" <?php if ( $hpb_is_readonly ) { echo 'readonly'; }?>/>
	</p>
	</div>
	<input type="hidden" name="permission" value="0"/>
<?php 
	if( $hpb_is_ga == false ) {
		if ( $hpb_is_clearGA == true ) {
			update_option( 'hpb_google_analytics', 0 ); 
		}
		echo '<input type="hidden" name="setGA" value="1">
		<p class="submit"><input class="button-primary" type="submit" name="submit" value="サイトを Google アナリティクス対象に設定"</p></form>'; 
	} else {
		if ( $hpb_is_setGA == true ) {
			update_option( 'hpb_google_analytics', 1 );
		}
		echo '<input type="hidden" name="clearGA" value="1">
		<p class="submit"><input class="button-primary" type="submit" name="submit" value="サイトを Google アナリティクス対象から解除"/></p></form>';
	}
?>
</div>
<?php
}

function hpb_head_google_analytics() {
	if ( !is_search() && get_option( 'hpb_google_analytics', 0 ) != 0 ) {
		$hpb_tracking_id =  get_option( 'hpb_tracking_id' );
		if ( $hpb_tracking_id != '' ) {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $hpb_tracking_id; ?>', 'auto');
  ga('send', 'pageview');

</script>
<?php
		}
	}
}

?>