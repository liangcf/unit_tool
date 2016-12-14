<?php

/**
 * HTTP相关的功能工具
 * @author Jason
 *
 */
namespace util;
class HttpUtils {
	
	/**
	 * 发起GET请求
	 *
	 * @param string $url
	 * @return string content
	 */
	public static function http_get($url) {
		$oCurl = curl_init ();
		if (stripos ( $url, "http://" ) !== FALSE || stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return array(
					'status' => true,
					'content' => $sContent,
					'code' => $aStatus ["http_code"],
			);
		} else {
			return array(
					'status' => false,
					'content' => false,
					'code' => $aStatus ["http_code"],
			);
		}
	}
	
	/**
	 * 发起POST请求
	 *
	 * @param string $url
	 * @param array $param
	 * @return string content
	 */
	public static function http_post($url, $param) {
		$oCurl = curl_init ();
		if (stripos ( $url, "http://" ) !== FALSE || stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
		}
		if (is_string ( $param )) {
			$strPOST = $param;
		} else {
			$aPOST = array ();
			foreach ( $param as $key => $val ) {
				$aPOST [] = $key . "=" . urlencode ( $val );
			}
			$strPOST = join ( "&", $aPOST );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $oCurl, CURLOPT_POST, true );
		curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return array(
					'status' => true,
					'content' => $sContent,
					'code' => $aStatus ["http_code"],
			);
		} else {
			return array(
					'status' => false,
					'content' => false,
					'code' => $aStatus ["http_code"],
			);
		}
	}

	/**
	 * @param $url
	 * @param int $curlTimeout
	 * @return mixed
	 */
	public function http_get_time($url,$curlTimeout = 60){
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $curlTimeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}

	/**
	 * @param $url
	 * @param $param
	 * @param int $timeout
	 * @return array
	 */
	public function http_post_time($url, $param, $timeout = 5) {
		$oCurl = curl_init ();
		if (stripos ( $url, "http://" ) !== FALSE || stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
		}
		if (is_string ( $param )) {
			$strPOST = $param;
		} else {
			$aPOST = array ();
			foreach ( $param as $key => $val ) {
				$aPOST [] = $key . "=" . urlencode ( $val );
			}
			$strPOST = join ( "&", $aPOST );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $oCurl, CURLOPT_TIMEOUT, $timeout );
		curl_setopt ( $oCurl, CURLOPT_POST, true );
		curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return array(
				'status' => true,
				'content' => $sContent,
				'code' => $aStatus ["http_code"],
			);
		} else {
			return array(
				'status' => false,
				'content' => false,
				'code' => $aStatus ["http_code"],
			);
		}
	}
}
