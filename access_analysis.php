<?php

function hpb_access_analysis_page() {

	$hpb_is_addSite = false;
	$hpb_is_deleteSite = false;
	$hpb_is_changeAccount = false;
	$hpb_is_aa = false;
	if (isset($_POST[ 'addSite' ]) && $_POST[ 'addSite' ] == "1") {
		update_option('hpb_access_acount', $_POST['hpb_access_acount']);
		update_option('hpb_access_password', $_POST['hpb_access_password']);
		$hpb_response = hpb_add_site_access_analysis();
		$hpb_is_addSite = true;
	} elseif (isset($_POST[ 'deleteSite' ]) && $_POST[ 'deleteSite' ] == "1") {
		update_option('hpb_access_acount', $_POST['hpb_access_acount']);
		update_option('hpb_access_password', $_POST['hpb_access_password']);
		$hpb_response = hpb_delete_site_access_analysis();
		$hpb_is_deleteSite = true;
	} else {
		if (isset($_POST[ 'changeAccount' ]) && $_POST[ 'changeAccount' ] == "1") {
			update_option('hpb_access_acount', $_POST['hpb_access_acount']);
			update_option('hpb_access_password', $_POST['hpb_access_password']);
			$hpb_is_changeAccount = true;
		}
		if (get_option( 'hpb_site_id', 0 ) != 0) {
			$hpb_is_aa = true;
		}
	}
	$ErrorMessage;
	$statusCode = 0;
	$id_aa = hpb_get_access_analysis_id( $ErrorMessage, $statusCode );
	$hpb_site_id = hpb_get_site_id( $id_aa, get_bloginfo( 'url' ), $ErrorMessage, $statusCode );
	wp_enqueue_style('hpb_dashboard_admin', HPB_PLUGIN_URL.'/hpb_dashboard_admin.css');
	wp_enqueue_script( 'jquery' );
?>
	<div id="hpb_dashboard_body">
	<div id="hpb_dashboard_title" class="wrap"><h2><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/icon_hpb.png';?>">アクセス解析設定</h2></div>
<?php
	if ( $statusCode == -1 || $statusCode == 101 ) {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">
<?php
		echo $ErrorMessage;
?>
	</div>
<?php
	} else {
		if ($hpb_is_addSite) {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">
<?php
			echo $hpb_response.'</div>';
			if ( $hpb_site_id != '' ) {
				$hpb_is_aa = true;
			}
 		} else if ($hpb_is_deleteSite) {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">
<?php
			echo $hpb_response.'</div>';
		} else if ( $statusCode != 0 && get_option('hpb_access_acount') != '') {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">
<?php
			echo $ErrorMessage.'</div>';
		} else if ( $statusCode == 0 && $hpb_is_changeAccount == true ) {
?>
	<div class="hpb_eyecatch_area"><img src="<?php echo HPB_PLUGIN_URL.'/image/admin/eyecatch.png';?>" class="hpb_eyecatch">Just アカウントを再設定しました。</div>
<?php
		}
		if( $hpb_is_aa == false ) {
			$chkmessage = 'アクセス解析を設定します。よろしいですか？';
		} else {
			if ( $statusCode == 0 ) {
				$chkmessage = 'アクセス解析を解除します。よろしいですか？アクセス解析を解除すると今までの解析情報はすべて削除されます。';
			} else {
				$chkmessage = 'Just アカウントを再設定します。よろしいですか？';
			}
		}
?>
	<script><!--
		jQuery('#check_access_analysis').hide();
	--></script>
	<form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" autocomplete="off" onsubmit="if( confirm('<?php echo $chkmessage; ?>') ) return true; return false;">
	<?php wp_nonce_field('update-options'); ?>
<?php
		if ( $hpb_is_aa ) {
			if ( $hpb_site_id != '' ) {
				$hpb_is_readonly = true;
			} else {
				$hpb_is_readonly = false;
			}
		} else {
			echo '<p>アクセス解析サービスのアカウント設定を行い、サイトをアクセス解析の対象に設定します。</p><div id="hpb_aa_service_start"><div class="hpb_caption">サービスの利用手続き</div><p>下のボタンをクリックして、「かんたんアクセス解析」の利用手続きを行います。</p><p><a class="button" href="https://kantan-access.com/apply/index.html" target="_blank">かんたんアクセス解析の利用手続き</a></p></div>';
			$hpb_is_readonly = false;
		}
?>
	<div class="hpb_caption">アカウント設定</div>
	<p>Just アカウントに登録したメールアドレスとパスワードを入力します。<br/>Just アカウントについて詳しくは <a href="http://account.justsystems.com/jp/about.html" target="_blank">こちら</a> をご覧ください。
	<div id="hpb_aa_acount_form">
	<img id="hpb_just_account_logo" src="<?php echo HPB_PLUGIN_URL.'/image/admin/just_account.png';?>"/>
	<p>
		<label for="hpb_access_acount">メールアドレス</label>
		<input size="60" type="text" name="hpb_access_acount" id="hpb_access_acount" value="<?php echo get_option('hpb_access_acount'); ?>" <?php if($hpb_is_readonly){ echo 'readonly';}?>/>
	</p>
	<p>
		<label for="hpb_access_password">パスワード</label>
		<input size="60" type="password" name="hpb_access_password" id="hpb_access_password" value="<?php echo get_option('hpb_access_password'); ?>" <?php if($hpb_is_readonly){ echo 'readonly';}?>/>
	</p>
	</div>
	<input type="hidden" name="permission" value="0"/>
<?php 
		if( $hpb_is_aa == false ) {
			if ( $hpb_is_deleteSite == true ) {
				update_option( 'hpb_site_id', 0 ); 
			}
			echo '<input type="hidden" name="addSite" value="1">
			<p class="submit"><input class="button-primary" type="submit" name="submit" value="サイトをアクセス解析対象に設定"</p></form>'; 
		} else {
			if ( $hpb_is_addSite == true || ( $hpb_is_changeAccount == true && $hpb_site_id != '' ) ) {
				update_option( 'hpb_site_id', strval( $hpb_site_id ) );
			}
			if ( $statusCode == 0 ){
				echo '<input type="hidden" name="deleteSite" value="1">
				<p class="submit"><input class="button-primary" type="submit" name="submit" value="サイトをアクセス解析対象から解除"/></p></form>';
			} else {
				echo '<input type="hidden" name="changeAccount" value="1">
				<p class="submit"><input class="button-primary" type="submit" name="submit" value="Just アカウントの再設定"/></p></form>';
			}
		}
	}
?>
</div>
<?php
}

function hpb_getfooter_access_analysis() {
if(!is_search() && get_option( 'hpb_site_id', 0 ) != 0){
?>
	<script type="text/javascript"><!--
	var _JustAnalyticsConfig = {
	'siteid': '<?php echo get_option( 'hpb_site_id' ); ?>',
	'domain': '<?php
	$url = parse_url(get_bloginfo( 'url' ));
	echo $url[ 'host' ];?>','path': '/'};// --></script>
	<script type="text/javascript" src="http://tracker.kantan-access.com/js/ja.js"></script><noscript><img width="1" height="1" alt="" src="http://tracker.kantan-access.com/jana_tracker/track4ns.gif?sid=<?php get_option('hpb_site_id');?>&t=&p=%2Findex.php&cs=UTF-8"></noscript>
<?php
}
}

function hpb_delete_site_access_analysis() {
	$uri = 'http://webservice2.jana.justsystems.com';
	$sm_location = 'https://kantan-access.com/jana_webservice2/services/SiteManager';
	$response_hpb_get_access_analysis_id;
	$id_aa = hpb_get_access_analysis_id( $response_hpb_get_access_analysis_id, $statusCode );
	if( $id_aa == '' ) {
		return $response_hpb_get_access_analysis_id;
	}
	$statusCode = 0;
	$site_id = hpb_get_site_id( $id_aa, get_bloginfo( 'url' ), $ErrorMessage, $statusCode );
	if ( $statusCode != 0 ){
		return $ErrorMessage;
	}
	$request = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<soapenv:Body>
		<deleteSite xmlns="http://webservice2.jana.justsystems.com">
			<accessInfo>
				<loginName>'.get_option('hpb_access_acount').'</loginName>
				<password>'.get_option('hpb_access_password').'</password>
			</accessInfo>
			<serviceTicketId>'.$id_aa.'</serviceTicketId>
			<siteId>'.$site_id.'</siteId>
		</deleteSite>
	</soapenv:Body>
</soapenv:Envelope>';
	$res = wp_remote_post( $sm_location, 
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'text/xml', 'SOAPAction' => 'deleteSite',),
					'body' => $request,
					));
	if(is_wp_error($res)){
		return  $res->get_error_message();
	}
	$res = wp_remote_retrieve_body($res);
	$res_xml = simplexml_load_string( $res );
	$elm = $res_xml->children( 'soapenv', true );
	$elm = $elm->children( 'http://webservice2.jana.justsystems.com' );
	$elm = $elm->children( '' );
	$elm = $elm->children( '' );
	$response = '';
	if( $elm->statusCode == 0 || $elm->statusCode == 20300 ) {
		$response = 'かんたんアクセス解析の解析対象に登録したサイトを、解析対象から外しました。';
	} else {
		$response = $elm->statusMessage;
	}
	return $response;
}

function hpb_add_site_access_analysis() {
	$uri = 'http://webservice2.jana.justsystems.com';
	$sm_location = 'https://kantan-access.com/jana_webservice2/services/SiteManager';
	$ErrorMessage;
	$id_aa = hpb_get_access_analysis_id( $ErrorMessage, $statusCode );
	if( $id_aa == '' ) {
		return $ErrorMessage;
	}
	$site_name = get_bloginfo('name');
	if( $site_name == '' ) {
		$site_name = preg_replace( '#^(https?://)?(www.)?#', '', get_home_url() );
	}
	$request = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<soapenv:Body>
		<addSite xmlns="http://webservice2.jana.justsystems.com">
			<accessInfo>
				<loginName>'.get_option('hpb_access_acount').'</loginName>
				<password>'.get_option('hpb_access_password').'</password>
			</accessInfo>
			<serviceTicketId>'.$id_aa.'</serviceTicketId>
			<siteName>'.$site_name.'</siteName>
			<siteUrl>'.get_bloginfo('url').'</siteUrl>
		</addSite>
  </soapenv:Body>
</soapenv:Envelope>';
	$res = wp_remote_post( $sm_location, 
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'text/xml', 'SOAPAction' => 'addSite',),
					'body' => $request,
					));
	if(is_wp_error($res)){
		return  $res->get_error_message();
	}
	$res = wp_remote_retrieve_body($res);
	$res_xml = simplexml_load_string( $res );
	$elm = $res_xml->children( 'soapenv', true );
	$elm = $elm->children( 'http://webservice2.jana.justsystems.com' );
	$elm = $elm->children( '' );
	$elm = $elm->children( '' );

	$site_id = strval( $elm->siteId );
	$response = '';
	if( $elm->statusCode == 0 ) {
		$response = 'サイトをかんたんアクセス解析の解析対象に設定しました。<a href="https://kantan-access.com/jana_webapp/login.do" target="_blank">アクセス解析ページを表示</a>';
	} else if( $elm->statusCode == 20303 || $elm->statusCode == 20304 ) {
		$statusCode = 0;
		$site_id = hpb_get_site_id( $id_aa, get_bloginfo( 'url' ), $ErrorMessage, $statusCode );
		if( $site_id  == '' ) {
			if ( $statusCode == -1 || $statusCode == 100 || $statusCode == 101 ) {
				return $ErrorMessage;
			}
			return '同じサイト名のサイトがすでに登録されています。サイト名を変更するか、登録されているサイトの登録を解除してください。';
		} else {
			$response = 'サイトをかんたんアクセス解析の解析対象に設定しました。<a href="https://kantan-access.com/jana_webapp/login.do" target="_blank">アクセス解析ページを表示</a>';
		}
	} else  {
		$response = hpb_get_statusmessage( $elm->statusCode );
	} 
	return $response;
}

function hpb_get_access_analysis_id( &$ErrorMessage, &$statusCode ) {
	$hpb_access_acount = get_option('hpb_access_acount');
	$hpb_access_password = get_option('hpb_access_password');
	$uri = 'http://webservice2.jana.justsystems.com';
	$stm_location = 'https://kantan-access.com/jana_webservice2/services/ServiceTicketManager';
	$request = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<soapenv:Body>
		<getAllServiceTickets xmlns="http://webservice2.jana.justsystems.com">
		<accessInfo>
			<loginName>'.$hpb_access_acount.'</loginName>
			<password>'.$hpb_access_password .'</password>
		</accessInfo>
		</getAllServiceTickets>
	</soapenv:Body>
</soapenv:Envelope>';
	$res = wp_remote_post( $stm_location, 
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'text/xml', 'SOAPAction' => 'getAllServiceTickets',),
					'body' => $request,
					));
	if(is_wp_error($res)){
		$ErrorMessage = $res->get_error_message();
		$statusCode = 100;
		return  '';
	}
	$res = wp_remote_retrieve_body($res);
	$res_xml = simplexml_load_string( $res );
	$elm = $res_xml->children( 'soapenv', true );
	$elm = $elm->children( 'http://webservice2.jana.justsystems.com' );
	$elm = $elm->children( '' );
	$elm = $elm->children( '' );
	$statusCode = $elm->statusCode;
	if ( $statusCode != 0 ) {
		$ErrorMessage = hpb_get_statusmessage( $statusCode );
		return '';
	}
	foreach ( $elm->serviceTickets->item as $item ) {
		if( $item->status != 0 && $item->status != 1 && $item->status != 2 ) {
			continue;
		}
		if( hpb_is_access_analysis( $item->id, $ErrorMessage, $statusCode ) == true ) {
			$id = $item->id;
			break;
		}
		if( $statusCode != 0 ) {
			return '';
		}
		if( intval( $item->siteCount ) < intval( $item->maxSiteCount ) ){
			$id = $item->id;
		}
	}
	if( $id == '' ) {
		$ErrorMessage = 'サービス利用権は存在しません。';
	}
	return $id; 
}

function hpb_get_statusMessage( $statusCode ) {
	if( $statusCode == -1 ){
		return '通信エラーが発生しました。しばらく待ってから再度お試しください。';
 	} else if( $statusCode == 100 ) {
		return '内部的なエラーが発生しました。';
	} else if( $statusCode == 101 ) {
		return 'サービスはメンテナンス中です。';
	} else if( $statusCode == 200 || $statusCode == 201 || $statusCode == 301 || $statusCode == 300 ) {
		return 'Just アカウントまたはパスワードが正しくありません。';
	} else if( $statusCode == 302 ) {
		return 'そのアカウントは「かんたんアクセス解析」の会員ではありません。';
	} else if( $statusCode == 303 ) {
		return 'そのアカウントは利用停止状態になっています。';
	} else if( $statusCode == 304 ) {
		return 'そのアカウントは退会しています。';
	} else if( $statusCode == 20200 ) {
		return 'パラメータの形式が不正です（サービス利用権ID）。';
	} else if( $statusCode == 20201 ) {
		return 'パラメータの形式が不正です（サイトの名称）。';
	} else if( $statusCode == 20202 ) {
		return 'パラメータの形式が不正です（サイトのURL）。';
	} else if( $statusCode == 20202 ) {
		return 'パラメータの形式が不正です（サイトID）。';
	}else if( $statusCode == 20300 ) {
		return '指定のサイトは存在しません';
	} else if( $statusCode == 20301 ) {
		return '指定のサービス利用権は利用期限を過ぎています。';
	} else if( $statusCode == 20302 ) {
		return 'サービス利用権の最大サイト数を越えています。';
	} else if( $statusCode == 20303 ) {
		return '同じ名前のサイトが既に登録されています。';
	} else if( $statusCode == 20304 ) {
		return '同じURLのサイトが既に登録されています。';
	} else if( $statusCode == 20305 ) {
		return 'サービス利用権は存在しません。';
	} else if( $statusCode == 20306 ) {
		return 'サービス利用権に課金エラーが発生しています。';
	} else if( $statusCode == 20307 ) {
		return 'サービス利用権は解約されています。';
	}
}

function hpb_is_access_analysis( $id_aa, &$ErrorMessage, &$statusCode ) {
	if( $id_aa == '' ) {
		return false;
	}
	$site_id = hpb_get_site_id( $id_aa, get_bloginfo( 'url' ), $ErrorMessage, $statusCode );
	return $site_id != '';
}

function hpb_get_site_id( $id_aa, $url, &$ErrorMessage, &$statusCode ) {
	if( $id_aa == '' ) {
		return;
	}
	$uri = 'http://webservice2.jana.justsystems.com';
	$sm_location = 'https://kantan-access.com/jana_webservice2/services/SiteManager';
	$request = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<soapenv:Body>
		<getAllSites xmlns="http://webservice2.jana.justsystems.com">
		<accessInfo>
			<loginName>'.get_option('hpb_access_acount').'</loginName>
			<password>'.get_option('hpb_access_password').'</password>
		</accessInfo>
		<serviceTicketId>'.$id_aa.'</serviceTicketId>
		</getAllSites>
	</soapenv:Body>
</soapenv:Envelope>';
	$res = wp_remote_post( $sm_location, 
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'text/xml', 'SOAPAction' => 'getAllSite',),
					'body' => $request,
					));
	if(is_wp_error($res)){
		$statusCode = -1;
		$ErrorMessage = hpb_get_statusmessage( $statusCode );
		return  '';
	}
	$res = wp_remote_retrieve_body($res);
	$res_xml = simplexml_load_string( $res );
	$elm = $res_xml->children( 'soapenv', true );
	$elm = $elm->children( 'http://webservice2.jana.justsystems.com' );
	$elm = $elm->children( '' );
	$elm = $elm->children( '' );
	$statusCode = $elm->statusCode;
	if ( $statusCode != 0 ) {
		$ErrorMessage = hpb_get_statusmessage( $statusCode );
		return '';
	}
	foreach( $elm->sites->item as $item ){
		if( $item->url == $url ){
			return $item->id;
		}
	}
}

?>