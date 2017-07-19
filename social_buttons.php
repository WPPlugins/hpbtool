<?php

function hpb_social_page()
{
	wp_enqueue_style('hpb_dashboard_admin', HPB_PLUGIN_URL.'/hpb_dashboard_admin.css');
	wp_enqueue_style('hpb_socialbutton_style', HPB_PLUGIN_URL.'/hpb_social_admin.css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_style('wp-jquery-ui-dialog');
	if ( wp_is_mobile() )
		wp_enqueue_script( 'jquery-touch-punch' );
	wp_enqueue_script('hpb_social_admin', HPB_PLUGIN_URL.'/hpb_social_admin.js');
?>
<div id="hpb_dashboard_body">
<form method="post" action="options.php">
<div id="hpb_dashboard_title" class="wrap"><h2><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/icon_hpb.png';?>">ソーシャルボタン設定</h2></div>
<p>ページに、ソーシャルネットワークのボタンをまとめて挿入します。ドラッグ＆ドロップでボタンを追加、移動、削除できます。</p>
<div class="postbox">
<h3 class="hpb_content_title">ソーシャルボタン</h3>
<div class="hpb_content">
<div id="preview_box">
<div class="hpb_caption">プレビュー</div>
<ul id="socialbuttonlist">
<?php
 $hpb_social_buttons_order = hpb_get_social_buttons_order();
 $array_data = explode( ',', $hpb_social_buttons_order );
 foreach( $array_data as $social_button_id ) {
	if( $social_button_id != '' ) {
		
		if( $social_button_id == 'linebutton_vertical' ) {
			$button_size = ' width="36" height="60"';
		} else if ( $social_button_id == 'linebutton_horizontal' ) {
			$button_size = ' width="82" height="20"';
		} else if ( $social_button_id == 'linebutton_large' ) {
			$button_size = ' width="40" height="40"';
		} else if ( $social_button_id == 'linebutton_medium' ) {
			$button_size = ' width="30" height="30"';
		} else if ( $social_button_id == 'linebutton_small' ) {
			$button_size = ' width="20" height="20"';
		} else {
			$button_size = '';
		}
?>
 <li id="<?php echo $social_button_id; ?>"><img id="<?php echo $social_button_id; ?>" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/<?php echo $social_button_id; ?>.png"<?php echo $button_size; ?>></li>
<?php
	}
 }
?>
</ul>
</div>
<div id="trashbox">
<span class="over">削除</span>
</div>
<div class="clearfloat"></div>
<div class="hpb_caption">挿入できるボタン</div>
<div id="hpb_social_tabs">
	<ul class="category-tabs">
	<li><a href="#tweet_tab">ツイート</a></li>
	<li><a href="#facebooklike_tab">いいね!</a></li>
	<li><a href="#googleplus_tab">Google +1</a></li>
	<li><a href="#mixicheck_tab">mixiチェック/mixiイイネ!</a></li>
	<li><a href="#hatenabookmark_tab">はてなブックマーク</a></li>
	<li><a href="#sendbyline_tab">LINEで送る</a></li>
	</ul>
<div id="tweet_tab">
	<div class="socialType" id="tweet">
		<ul class="buttonTypes">
		<li id="tweet_horizontal"><img id="tweet_horizontal" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/tweet_horizontal.png"></li>
		<li id="tweet_vertical"><img id="tweet_vertical" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/tweet_vertical.png"></li>
		<li id="tweet_nocount"><img id="tweet_nocount" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/tweet_nocount.png"></li>
		</ul>
	</div>
</div>
<div id="facebooklike_tab">
	<div class="socialType" id="facebooklike">
		<ul class="buttonTypes">
		<li id="facebook_like_horizontal"><img id="facebook_like_horizontal" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/facebook_like_horizontal.png"></li>
		<li id="facebook_like_vertical"><img id="facebook_like_vertical" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/facebook_like_vertical.png"></li>
		<li id="facebook_like_standart"><img id="facebook_like_standart" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/facebook_like_standart.png"></li>
		</ul>
		<div class="clearfloat"></div>
		<div class="buttonoption">
		<p>
			<label for="hpb_social_facebook_admins">ユーザーID</label>
			<input size="40" type="text" name="hpb_social_facebook_admins" id="hpb_social_facebook_admins" value="<?php echo get_option('hpb_social_facebook_admins'); ?>"/>
		</p>
		<p>
			<label for="hpb_social_facebook_app_id">アプリケーションID</label>
			<input size="40" type="text" name="hpb_social_facebook_app_id" id="hpb_social_facebook_app_id" value="<?php echo get_option('hpb_social_facebook_app_id'); ?>"/>
		</p>
		</dl>
		<div>取得方法については、FacebookのWebページ をご覧ください。</div>
		</div>
	</div>
</div>
<div id="googleplus_tab">
	<div class="socialType" id="googleplus">
		<ul class="buttonTypes">
		<li id="google_plusone_horizontal"><img id="google_plusone_horizontal" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/google_plusone_horizontal.png"></li>
		<li id="google_plusone_vertical"><img id="google_plusone_vertical" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/google_plusone_vertical.png"></li>
		<li id="google_plusone_nocount"><img id="google_plusone_nocount" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/google_plusone_nocount.png"></li>
		</ul>
	</div>
</div>
<div id="mixicheck_tab">
	<div class="socialType" id="mixicheck">
		<ul class="buttonTypes">
		<li id="mixi_check"><img id="mixi_check" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/mixi_check.png"></li>
		<li id="mixi_like"><img id="mixi_like" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/mixi_like.png"></li>
		</ul>
		<div class="clearfloat"></div>
		<div class="buttonoption">
		<p>
			<label for="hpb_social_mixi_check_key">mixiチェックキー</label>
			<input size="40" type="text" name="hpb_social_mixi_check_key" id="hpb_social_mixi_check_key" value="<?php echo get_option('hpb_social_mixi_check_key'); ?>"/>
		</p>
		<div>取得方法については、mixiのWebページ をご覧ください。</div>
		</div>
	</div>
</div>
<div id="hatenabookmark_tab">
	<div class="socialType" id="hatenabookmark">
	<ul class="buttonTypes">
	<li id="hatena_bookmark_horizontal"><img id="hatena_bookmark_horizontal" id="aaaa" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/hatena_bookmark_horizontal.png"></li>
	<li id="hatena_bookmark_vertical"><img id="hatena_bookmark_vertical" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/hatena_bookmark_vertical.png"></li>
	<li id="hatena_bookmark_nocount"><img id="hatena_bookmark_nocount" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/hatena_bookmark_nocount.png"></li>
	</ul>
	</div>
</div>
<div id="sendbyline_tab">
	<div class="socialType" id="sendbyline">
	<ul class="buttonTypes">
	<li id="linebutton_horizontal"><img id="linebutton_horizontal" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/linebutton_horizontal.png" width="82" height="20"></li>
	<li id="linebutton_vertical"><img id="linebutton_vertical" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/linebutton_vertical.png" width="36" height="60"></li>
	<li id="linebutton_large"><img id="linebutton_large" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/linebutton_large.png" width="40" height="40"></li>
	<li id="linebutton_medium"><img id="linebutton_medium" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/linebutton_medium.png" width="30" height="30"></li>
	<li id="linebutton_small"><img id="linebutton_small" src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/linebutton_small.png" width="20" height="20"></li>
	</ul>
	</div>
</div>
</div></div>
<div class="hpb_content">
<?php wp_nonce_field('update-options'); ?>
<div id="hpb_insert_pos_settings"><div class="hpb_caption">挿入位置</div>
<p class="indent1"><input type="checkbox" name="hpb_social_post" id="hpb_social_post" value="1" <?php checked( get_option('hpb_social_post', 1 ), 1 ); ?> /><label for="hpb_social_post"> 投稿単体ページ</label></p>
<p class="indent2"><input type="radio" name="hpb_social_post_insert_position" id="insert_post_before" value="0" <?php checked( get_option('hpb_social_post_insert_position', 0 ), 0 ); ?> /><label for="insert_post_before"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/socialbutton_insert_top.png"/>記事の前</label>
<input type="radio" name="hpb_social_post_insert_position" id="insert_post_after" value="1" <?php checked( get_option('hpb_social_post_insert_position', 0 ), 1 ); ?> /><label for="insert_post_after"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/socialbutton_insert_bottom.png"/>記事の後ろ</label></p>
<p class="indent1"><input type="checkbox" name="hpb_social_page" id="hpb_social_page" value="1" <?php checked( get_option('hpb_social_page', 1 ), 1 ); ?> /><label for="hpb_social_page"> 固定ページ</label></p>
<p class="indent2"><input type="checkbox" name="hpb_social_front_page" id="hpb_social_front_page" value="1" <?php checked( get_option('hpb_social_front_page', 0 ), 1 ); ?> /><label for="hpb_social_front_page"> トップページのみ</label></p>
<p class="indent2"><input type="radio" name="hpb_social_page_insert_position" id="insert_page_before" value="0" <?php checked( get_option('hpb_social_page_insert_position', 0 ), 0 ); ?> /><label for="insert_page_before"><img src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/socialbutton_insert_top.png"/>記事の前</label>
<input type="radio" name="hpb_social_page_insert_position" id="insert_page_after" value="1" <?php checked( get_option('hpb_social_page_insert_position', 0 ), 1 ); ?> ><label for="insert_page_after"/><img src="<?php echo HPB_PLUGIN_URL; ?>/image/social_button/socialbutton_insert_bottom.png"/>記事の後ろ</label></p><br/>
<p class="indent1"><input type="checkbox" name="hpb_social_ogp_set" id="hpb_social_ogp_set" value="1" <?php checked( get_option('hpb_social_ogp_set', 1 ), 1 ); ?> /><label for="hpb_social_ogp_set"> OGPを自動設定する</label></p>
</div></div>
<div class="hpb_content">
<p><input type="hidden" name="hpb_social_buttons_order" id="order" value="<?php echo hpb_get_social_buttons_order() ?>"/><input type="submit" class="button-primary" value="<?php _e('設定を保存する') ?>" /></p></div>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="hpb_social_post, hpb_social_post_insert_position, hpb_social_page, hpb_social_page_insert_position, hpb_social_front_page, hpb_social_archive_page, hpb_social_archive_page_insert_position, hpb_social_buttons_order, hpb_social_ogp_set, hpb_social_mixi_check_key, hpb_social_facebook_admins, hpb_social_facebook_app_id" /></div>
</form></div>
<?php
}

function hpb_social_enqueue_style () {
	if( hpb_is_social() ) {
		wp_enqueue_style( 'hpb_social_style', HPB_PLUGIN_URL.'/'.'hpb_social.css' );
	}
}

function hpb_is_social() {
	$option_post = get_option( 'hpb_social_post', 1  );
	$option_page = get_option( 'hpb_social_page', 1  );
	$option_archive = get_option( 'hpb_archive_page', 0  );
	if( hpb_get_social_buttons_order() != '' && ( $option_post == 1 || $option_page == 1 ||  $option_archive == 1 ) ) {
		return true;
	} else {
		return false;
	}
}

function hpb_social_the_content( $content ) {
	if( is_feed() || is_404() || is_robots() ) {
		return $content;
	}

	$option_post = get_option( 'hpb_social_post', 1  );
	$option_page = get_option( 'hpb_social_page', 1  );
	$option_front_page = get_option( 'hpb_social_front_page', 0  );
	$option_archive = get_option( 'hpb_archive_page', 0  );

	$content_social = '';
	if( ( is_singular() && $option_post == 1 && !is_page() ) || ( is_page() && $option_page == 1 && 
		 !( !is_front_page() && $option_front_page == 1 ) ) || ( is_archive() && $option_archive == 1 ) ) {
		$hpb_social_buttons_order = hpb_get_social_buttons_order();
		$array_data = explode( ',', $hpb_social_buttons_order );
		foreach( $array_data as $social_button_id ) {
			if( $social_button_id == 'facebook_like_horizontal' || $social_button_id == 'facebook_like_vertical' || $social_button_id == 'facebook_like_standart' ) {
				if( $social_button_id == 'facebook_like_horizontal' ) {
					$facebook_like_width = '120';
					$facebook_like_layout = 'button_count';
				} else if ( $social_button_id == 'facebook_like_vertical' ) {
					$facebook_like_width = '75';
					$facebook_like_layout = 'box_count';
				} else if ( $social_button_id == 'facebook_like_standart' ){
					$facebook_like_width = '225';
					$facebook_like_layout = 'standart';
				} 
				$facebook_like = '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>
<div class="fb-like" data-send="false" data-layout="'.$facebook_like_layout.'" data-width="'.$facebook_like_width.'" data-show-faces="false"></div>'."\n";
				$content_social .= $facebook_like;
			} else if ( $social_button_id == 'google_plusone_horizontal' || $social_button_id == 'google_plusone_vertical' || $social_button_id == 'google_plusone_nocount' ) {
				if( $social_button_id == 'google_plusone_horizontal' ) {
					$google_plus_size = ' data-size="medium"';
					$google_plus_annotation = '';
				} else if( $social_button_id == 'google_plusone_vertical' ) {
					$google_plus_size = ' data-size="tall"';
					$google_plus_annotation = '';
				} else if( $social_button_id == 'google_plusone_nocount' ) {
					$google_plus_size = ' data-size="medium"';
					$google_plus_annotation = 'data-annotation="none"';
				} 
				$google_plus = '<div><div class="g-plusone" '.$google_plus_size.' '.$google_plus_annotation.' ></div>'."\n";
				$google_plus .= '<script type="text/javascript">
  window.___gcfg = {lang: \'ja\'};
  (function() {
    var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
    po.src = \'https://apis.google.com/js/plusone.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></div>';
				$content_social .= $google_plus;

			} else if ( $social_button_id == 'mixi_check' ) {
				$mixi_check = '<div><a href="http://mixi.jp/share.pl" class="mixi-check-button" data-key="'.get_option( 'hpb_social_mixi_check_key' ).'" data-url="'.get_permalink().'">mixiチェック</a><script type="text/javascript" src="http://static.mixi.jp/js/share.js"></script></div>'."\n";
				$content_social .= $mixi_check;	
			} else if ( $social_button_id == 'mixi_like' ) {
				$mixi_like = '<div><iframe src="http://plugins.mixi.jp/favorite.pl?href='.urlencode(get_permalink()).'&service_key='.get_option( 'hpb_social_mixi_check_key' ).'&show_faces=true" scrolling="no" frameborder="0" allowtransparency="true" style="border:0; overflow:hidden; width:120px; height:20px;"></iframe></div>'."\n";
				$content_social .= $mixi_like;	
			} else if ( $social_button_id == 'tweet_horizontal' || $social_button_id == 'tweet_vertical' || $social_button_id == 'tweet_nocount' ) {
				if( $social_button_id == 'tweet_horizontal' ) {
					$twitter_share_data_count = '';
				} else if( $social_button_id == 'tweet_vertical' ) {
					$twitter_share_data_count = ' data-count="vertical"';
				} else if( $social_button_id == 'tweet_nocount' ) {
					$twitter_share_data_count = ' data-count="none"';
				} 
				$twitter_share = '<div><a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja"'.$twitter_share_data_count.'>ツイート</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>'."\n";
				$content_social .= $twitter_share;
			} else if ( $social_button_id == 'hatena_bookmark_horizontal' || $social_button_id == 'hatena_bookmark_vertical' || $social_button_id == 'hatena_bookmark_nocount' ) {
				if( $social_button_id == 'hatena_bookmark_horizontal' ) {
					$hatema_bookmark_layout = 'standart';
				} else if( $social_button_id == 'hatena_bookmark_vertical' ) {
					$hatema_bookmark_layout = 'vertical';
				} else if( $social_button_id == 'hatena_bookmark_nocount' ) {
					$hatema_bookmark_layout = 'simple';
				} 
				$hatena_bookmark = '<div><a href="http://b.hatena.ne.jp/entry/'.get_permalink().'" class="hatena-bookmark-button" data-hatena-bookmark-title="'.get_the_title() .'" data-hatena-bookmark-layout="'.$hatema_bookmark_layout.'" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script></div>'."\n";
				$content_social .= $hatena_bookmark;
			} else if ( $social_button_id == 'linebutton_vertical' || $social_button_id == 'linebutton_horizontal' || $social_button_id == 'linebutton_large' || $social_button_id == 'linebutton_medium' || $social_button_id == 'linebutton_small' ) {
				if( $social_button_id == 'linebutton_vertical' ) {
					$line_button_width = '36';
					$line_button_height = '60';
				} else if ( $social_button_id == 'linebutton_horizontal' ) {
					$line_button_width = '82';
					$line_button_height = '20';
				} else if ( $social_button_id == 'linebutton_large' ) {
					$line_button_width = '40';
					$line_button_height = '40';
				} else if ( $social_button_id == 'linebutton_medium' ) {
					$line_button_width = '30';
					$line_button_height = '30';
				} else if ( $social_button_id == 'linebutton_small' ) {
					$line_button_width = '20';
					$line_button_height = '20';
				}
				$send_by_line = '<div><a href="http://line.me/R/msg/text/?'.urlencode(get_the_title()) .'%0D%0A'.urlencode(get_permalink()) .'" class="send-by-line-button" target="blank"><img src="'.HPB_PLUGIN_URL.'/image/social_button/'.$social_button_id.'.png" width="'.$line_button_width.'" height="'.$line_button_height.'" alt="LINEで送る" style="border:none; width:'.$line_button_width.'px !important; height:'.$line_button_height.'px !important;"></a></div>'."\n";
				$content_social .= $send_by_line;
			} 
		}
	}

	if( $content_social !== '' ) {
		$content_social = '<div class="hpb_social">'.$content_social.'</div>'."\n";
	}

	$option_post_insert_position = get_option( 'hpb_social_post_insert_position', 0  );
	$option_page_insert_position = get_option( 'hpb_social_page_insert_position', 0  );
	$option_archive_page_insert_position = get_option( 'hpb_social_archive_page_insert_position', 0  );

	if(  ( is_singular() && !is_page() && $option_post_insert_position == 0 ) || ( is_page() && $option_page_insert_position == 0 && 
		 !( !is_front_page() && $option_front_page == 1 )) || ( is_archive() && $option_archive_page_insert_position == 0 )  ) {
		return $content_social.$content;
	} else {
		return $content.$content_social;
	}

	return $content;
}

function hpb_get_social_buttons_order() {
	return get_option( 'hpb_social_buttons_order', 'facebook_like_vertical,google_plusone_vertical,tweet_vertical,hatena_bookmark_vertical,linebutton_vertical' );
}

function hpb_head_social() {

	if( is_feed() || is_404() || is_robots() ) {
		return;
	}
	$option_post = get_option( 'hpb_social_post', 1  );
	$option_page = get_option( 'hpb_social_page', 1  );
	$option_archive = get_option( 'hpb_archive_page', 0  );
	$content_social = '';
	if( !( $option_post == 1  || $option_page == 1 || $option_archive == 1 ) ) {
		return;
	}
	if( get_option( 'hpb_social_ogp_set', 1 ) == 1 && ( is_single() || is_page() ) ) {
		while( have_posts() ) : the_post(); global $id;
?>
<meta property="og:title" content="<?php the_title(); ?> | <?php bloginfo('name'); ?>"/>
<meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>"/>
<meta property="og:image" content="<?php esc_url(hpb_og_image_url($id)); ?>"/>
<meta property="og:description" content="<?php echo strip_tags(get_the_excerpt()); ?>"/>
<meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
<meta property="og:type" content="<?php hpb_get_og_type() ?>"/>
<?php $fb_admins = get_option('hpb_social_facebook_admins'); if( $fb_admins != '' ) {?>
<meta property="fb:admins" content="<?php echo $fb_admins; ?>"/>
<?php } $fb_appid = get_option('hpb_social_facebook_app_id'); if( $fb_appid != '' ) {?>
<meta property="fb:app_id" content="<?php echo $fb_appid; ?>"/>
<?php
}		endwhile;
	}
}

function hpb_get_og_type() {
	if( is_front_page() ) {
		echo 'website';
	}  else  {
		echo 'article';
	}
}

function hpb_og_image_url($id) {
	$attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id($id) );
	if ( $attachment_image ){
		echo $attachment_image[0];
	} else {
		$query = 'post_parent=' . $id . '&post_type=attachment&post_mime_type=image';
		$postImg = get_children($query);
		if ( !empty($postImg) ){
			$keys = array_keys($postImg);
			$num = $keys[sizeOf($keys)-1];
			$thumb = wp_get_attachment_image_src($num, 'thumbnail');
			echo clean_url($thumb[0]);
		}  else {
			echo get_bloginfo('template_directory').'/screenshot.jpg';
		}
	}
}

?>