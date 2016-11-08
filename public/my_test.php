<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/9/18
 * Time: 11:46
 */

function encrypt($data, $key) {
    $prep_code = serialize($data);
    $block = mcrypt_get_block_size('des', 'ecb');
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
        $prep_code .= str_repeat(chr($pad), $pad);
    }
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
    return base64_encode($encrypt);
}

function decrypt($str, $key) {
    $str = base64_decode($str);
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = ord($str[($len = strlen($str)) - 1]);
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
        $str = substr($str, 0, strlen($str) - $pad);
    }
    return unserialize($str);
}
$key = 'okyo.cn';
$data = array('id' => 100, 'username' => 'customer', 'password' => 'e10adc3949ba59abbe56e057f20f883e');
$snarr = serialize($data);
$en = encrypt($data, $key);
$de = decrypt($en, $key);
echo "加密原型：";
print_r($data);
echo "
密钥：$key

加密结果：$en

解密结果：";
print_r($de);