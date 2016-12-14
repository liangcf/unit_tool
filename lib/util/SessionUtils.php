<?php
/**
 * Created by PhpStorm.
 * User: SAMSUNG-R428
 * Date: 16-2-25
 */
namespace util;
class SessionUtils
{
	/**
	 * 在session节点下存值
	 * @param mixed $key
	 * @param mixed $value
	 */
	public static function setSessionValue($key, $value) {
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION[$key] = $value;
	}

	/**
	 * 获取session节点下存储的值
	 * @param mixed $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function getSessionValue($key, $default=null) {
		if(!isset($_SESSION)){
			session_start();
		}
		return (isset($_SESSION[$key]) ? $_SESSION[$key] : $default);
	}

	/**
	 * 删除某一个session的值
	 *
	 * @param $key
	 */
	public static function unsetSession($key) {
		if(!isset($_SESSION)){
			session_start();
		}
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}

	public static function setValAndExpire($key, $value, $expire) {
		ini_set('session.gc_maxlifetime', $expire);
		session_set_cookie_params($expire);
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION[$key] = $value;
	}
}