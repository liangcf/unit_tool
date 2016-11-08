<?php
/**
 * 创建二维码工具
 * @author JYao
 *
 */

require '../lib/phpqrcode.php';

class qrCodeUtils {

    /**
     * @param string $qrCodeName 二维码名称
     * @param string $content 二维码内容
     * @param string $codePatch 二维码路径
     * @param bool|false $forceFlush 是否强制覆盖
     * @param string $logo 二维码logo
     * @param string $level
     * @param int $size
     * @param int $margin
     * @return string
     */
    public static function createQRCode($qrCodeName,$content,$codePatch,$forceFlush=false,$logo='',$level='L',$size=4,$margin=1){
        //判断文件目录是否存在
        if (!file_exists($codePatch)){
            mkdir($codePatch,0777,true);
        }
        //二维码完整路径
        $returnImg=$codePatch.'/'.trim($qrCodeName).".png";
        $patch=$_SERVER['DOCUMENT_ROOT'];
        $imgPatch=$patch.'/'.$codePatch.'/'.trim($qrCodeName).".png";
        if(file_exists($imgPatch)) {
            if(!$forceFlush){
                return $returnImg;
            }else{
                unlink($imgPatch);//删除掉该二维码，然后再生成
            }
        }
        $content=trim($content);
        try{
            \QRcode::png($content,$imgPatch,$level,$size,$margin);//创建二维码
            if(!empty($logo)){
                $logo=trim($logo);
                $logo=$patch.'/'.$logo;
                $QR_stream=imagecreatefromstring(file_get_contents($imgPatch));
                $logo=imagecreatefromstring(file_get_contents($logo));
                $QR_width=imagesx($QR_stream);
                $QR_height=imagesy($QR_stream);
                $logo_width=imagesx($logo);
                $logo_height=imagesy($logo);
                $logo_qr_width=$QR_width / 5;
                $scale=$logo_width/$logo_qr_width;
                $logo_qr_height=$logo_height/$scale;
                $from_width=($QR_width-$logo_qr_width)/2;
                imagecopyresampled($QR_stream,$logo,$from_width,$from_width,0,0,$logo_qr_width,$logo_qr_height,$logo_width,$logo_height);
                ImagePng($QR_stream,$imgPatch);
            }
            return $returnImg;
        }catch (\Exception $e){
            exit("二维码生成失败，错误信息为：".$e->getMessage());
        }
    }

    /**
     * curl请求
     * @param $url
     * @return mixed
     */
    private static function http($url){
        $ch = curl_init();
        if (stripos ( $url, "http://" ) !== FALSE ||
            stripos ( $url, "https://" ) !== FALSE) {
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 判断是否是url
     * @param $url
     * @return bool
     */
    private static function isUrl($url) {
        $allow = array('http', 'https');
        if (preg_match('!^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?!',
            $url, $matches)){
            $scheme = $matches[2];
            if (in_array($scheme, $allow)){
                return true;
            }
        }
        return false;
    }


}