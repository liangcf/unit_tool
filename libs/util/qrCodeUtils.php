<?php
/**
 * 创建二维码工具
 * @author JYao
 *
 */

namespace util;
include __DIR__.'/../lib/phpqrcode.php';

class QRCodeUtils {

    /**
     * @link http://phpqrcode.sourceforge.net/index.php
     * @param string $qrCodeName 二维码名称
     * @param string $content 二维码内容
     * @param string $codePatch 二维码路径
     * @param bool|false $forceFlush 是否强制覆盖
     * @param string $logo 二维码logo
     * @param string $level L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）
     * @param int $size
     * @param int $margin
     * @return string
     */
    public static function createQRCode($qrCodeName,$content,$codePatch,$forceFlush=false,$logo='',$level='H',$size=7,$margin=1){
        //$patch=$_SERVER['DOCUMENT_ROOT'];
        //判断文件目录是否存在
//        $codePatchTmp=$patch.$codePatch;
        $codePatch=rtrim($codePatch,'/');
        if (!is_dir($codePatch)){
            mkdir($codePatch,0777,true);
        }
        //二维码完整路径
        $returnImg=$codePatch.'/'.trim($qrCodeName).".png";
        //$imgPatch=$patch.'/'.$codePatch.'/'.trim($qrCodeName).".png";
        if(file_exists($returnImg)) {
            if(!$forceFlush){
                return $returnImg;
            }else{
                unlink($returnImg);//删除掉该二维码，然后再生成
            }
        }
        $content=trim($content);
        try{
            \QRcode::png($content,$returnImg,$level,$size,$margin);//创建二维码
            if(!empty($logo)){
                $logo=trim($logo);
                $qRStream=imagecreatefromstring(file_get_contents($returnImg));
                $logo=imagecreatefromstring(file_get_contents($logo));
                $qRWidth=imagesx($qRStream);
                $qRHeight=imagesy($qRStream);
                $logoWidth=imagesx($logo);
                $logoWidth=imagesy($logo);
                $logoQrWidth=$qRWidth / 5;
                $scale=$logoWidth/$logoQrWidth;
                $logo_qr_height=$logoWidth/$scale;
                $from_width=($qRWidth-$logoQrWidth)/2;
                imagecopyresampled($qRStream,$logo,$from_width,$from_width,0,0,$logoQrWidth,$logo_qr_height,$logoWidth,$logoWidth);
                \ImagePng($qRStream,$returnImg);
            }
            return $returnImg;
        }catch (\Exception $e){
            exit("二维码生成失败，错误信息为：".$e->getMessage());
        }
    }

    /**
     * 创建二维码
     *
     * @param string $content 需要生成二维码的内容
     * @param string $qrCodeLocalPath 二维码存放的路径
     * @param string $logo
     * @param int|string $margin 二维码的边框大小
     * @param int|string $matrixPointSize  二维码点矩阵点位大小
     * @param bool $forceFlush 是否可强制冲洗【当指定名称的二维码存在时，用新生成的二维码冲洗原来的二维码】
     * @return bool|string
     */
    public static function createQrCode1(
        $content,$qrCodeLocalPath,$logo = "",
        $margin = '1',$matrixPointSize = '6',$forceFlush = false){

        $qrInfo = self::getQr($content,$qrCodeLocalPath,
            UuidUtils::create_uuid('qr'),$logo,$margin,$matrixPointSize,$forceFlush);
        return $qrInfo;
    }


    /**
     *
     * 生成二维码函数
     *
     * @param   string     $content 需要生成二维码的内容
     * @param string $qrCodeLocalPath 二维码存放的路径
     * @param string $qrCodeName 唯一标识，如果该标识已经使用生成过二维码，则不会重新生成，直接返回
     * @param bool|string $logo 二维码logo 非必须
     * @param int|string $margin 二维码的边框大小
     * @param int|string $matrixPointSize 二维码点矩阵点位大小
     * @param bool $forceFlush 是否可强制冲洗【当指定名称的二维码存在时，用新生成的二维码冲洗原来的二维码】
     * @return bool|string
     */
    public static function getQr($content,$qrCodeLocalPath,$qrCodeName,
                                 $logo = false,$margin = '1',$matrixPointSize = '6',$forceFlush = false) {
        if(!isset($content) || trim($content) == ""
            || !isset($qrCodeName) || trim($qrCodeName) == "") {
            return false;
        }
        $qrCodeLocalPath = rtrim($qrCodeLocalPath,"/");

        //判断文件目录是否存在
        if (!file_exists($qrCodeLocalPath)){
            mkdir($qrCodeLocalPath,0777,true);
        }

        //二维码完整路径
        $reqImg = $qrCodeLocalPath."/".trim($qrCodeName).".png";
        if(file_exists($reqImg)) {
            if($forceFlush == false){
                return $reqImg;
            }else{
                unlink($reqImg);//删除掉该二维码，然后再生成
            }
        }

        $errorCorrectionLevel = "H";
        $logo = trim($logo) ? trim($logo) : false;
        $content = trim($content);
        try{
            \QRcode::png($content, $reqImg, $errorCorrectionLevel,
                $matrixPointSize,$margin);//创建二维码
            if($logo !== false)
            {
                $qRStream = imagecreatefromstring(file_get_contents($reqImg));
                if(self::isUrl($logo)) {
                    $logo = imagecreatefromstring(self::http($logo));
                } else {
                    $logo = imagecreatefromstring(file_get_contents($logo));
                }
                $QR_width = imagesx($qRStream);
                $QR_height = imagesy($qRStream);
                $logoWidth = imagesx($logo);
                $logoWidth = imagesy($logo);
                $logoQrWidth = $QR_width / 5;
                $scale = $logoWidth / $logoQrWidth;
                $logo_qr_height = $logoWidth / $scale;
                $from_width = ($QR_width - $logoQrWidth) / 2;
                imagecopyresampled($qRStream, $logo, $from_width, $from_width, 0, 0,
                    $logoQrWidth, $logo_qr_height, $logoWidth, $logoWidth);
                ImagePng($qRStream,$reqImg);
            }
            return $reqImg;
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