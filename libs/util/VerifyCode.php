<?php

/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/5/18
 * Time: 15:08
 */
namespace util;
class VerifyCode
{
    //验证码生成类

    public static function yzCode(){
//        session_start();
        $image=imagecreatetruecolor(100,30);
        $bgcolor=imagecolorallocate($image,255,255,255);//设置图片背景白色
        imagefill($image,0,0,$bgcolor);
        //生产的验证码信息2
        //保存到session的验证码信息
        $captch_code='';
        for($i=0;$i<4;$i++){
            $fontsize=6;
            $fontcolor=imagecolorallocate($image,rand(0,150),rand(0,150),rand(0,150));
            $data='0123456789';
            $font_content=substr($data,rand(0,strlen($data)),1);
            $captch_code.=$font_content;
            $x=($i*100/4)+rand(5,10);
            $y=rand(5,10);
            imagestring($image,$fontsize,$x,$y,$font_content,$fontcolor);
        }
//        $_SESSION['authcode']=$captch_code;

        //干扰的点点
        for($i=0;$i<200;$i++){
            $point_color=imagecolorallocate($image,rand(50,200),rand(50,200),rand(50,200));
            imagesetpixel($image,rand(1,99),rand(1,29),$point_color);
        }

        //干扰的线
        for($i=0;$i<4;$i++){
            $link_color=imagecolorallocate($image,rand(80,220),rand(80,200),rand(20,200));
            imageline($image,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$link_color);
        }

        header("Content-type:image/png;");
        imagepng($image);
        $res=imagedestroy($image);
        return $res;
    }

    /**
     * 生成验证码随机数
     * @param int $length 长度
     * @return string 返回的字符串
     */
    private static function getCode($length){
        $pattern='0123456789';//字符池
        $key='';
        for($i=0;$i<$length;$i++) {
            $key .= $pattern{mt_rand(0,9)};
        }
        return $key;
    }

    /**
     * @param int $width 验证码宽度
     * @param int $height 验证码高度
     * @param int $length 验证码长度
     * @param string $yzmKey 验证码session key
     * @return bool
     */
    public static function yzmCode($width=100,$height=30,$length=4,$yzmKey='yzm_code'){
        if(!isset($_SESSION)){
            session_start();
        }
        $code=self::getCode($length);
        $_SESSION[$yzmKey]=$code;
        $img=imagecreate($width,$height);
        //画背景
        imagecolorallocate($img,255,255,255);
        //画边框
//        $borderColor=imagecolorallocate($img,rand(0,150),rand(0,150),rand(0,150));
//        imagerectangle($img,1,1,$width-1,$height-1,$borderColor);
        //循环写字
        for($i=0;$i<strlen($code);$i++){
            $code_color=imagecolorallocate($img,mt_rand(50,200),mt_rand(50,128),mt_rand(50,200));
            $charX=(($i*$width)/4)+rand(3,8);
            $charY=rand(3,8);
            imagestring($img,5,$charX,$charY,$code[$i],$code_color);
        }

        //干扰的点点
        for($i=0;$i<200;$i++){
            $pointColor=imagecolorallocate($img,rand(50,200),rand(50,200),rand(50,200));
            imagesetpixel($img,rand(1,99),rand(1,29),$pointColor);
        }

        //干扰的线
        for($i=0;$i<4;$i++){
            $linkColor=imagecolorallocate($img,rand(80,220),rand(80,200),rand(20,200));
            imageline($img,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$linkColor);
        }
        ob_clean();
        header("Content-type:image/png;");
        imagepng($img);
        imagedestroy($img);
        return true;
    }
}