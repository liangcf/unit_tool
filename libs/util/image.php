<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2016/3/1
 * Time: 17:15
 */

namespace util;
class  Image{

    //图片基本信息
    private $info;
    //内存中的图片
    private $image;

    /**
     * 打开一张图片，读取到内存中
     * Image constructor.
     * @param $src
     */
    public function __construct($src)
    {
        $info=getimagesize($src);
        $this->info=array(
            'width'=>$info[0],
            'height'=>$info[1],
            'type'=>image_type_to_extension($info[2],false),
            'mime'=>$info['mime']
        );
        $fun="imagecreatefrom{$this->info['type']}";
        $this->image=$fun($src);
    }

    /**
     * 文字水印
     * @param $content
     * @param $font
     * @param $size
     * @param $color 012 reg 3 透明度
     * @param $local
     * @param $angle
     */
    public function font_mark($content,$font,$size,$color,$local,$angle){
        $col=imagecolorallocatealpha($this->image,$color[0],$color[1],$color[2],$color[3]);
        imagettftext($this->image,$size,$angle,$local['x'],$local['y'],$col,$font,$content);
    }

    /**
     * 图片水印
     * @param $source
     * @param $local
     * @param $alpha
     */
    public function image_mark($source,$local,$alpha){
        $info2=getimagesize($source);
        $type2=image_type_to_extension($info2[2],false);
        $fun2="imagecreatefrom{$type2}";
        $water=$fun2($source);
        imagecopymerge($this->image,$water,$local['x'],$local['y'],0,0,$info2[0],$info2[1],$alpha);
        imagedestroy($water);
    }

    /**
     * 压缩操作图片
     * @param $width
     * @param $height
     */
    public function thumb($width,$height){
        $image_thumb=imagecreatetruecolor($width,$height);
        imagecopyresampled($image_thumb,$this->image,0,0,0,0,$width,$height,$this->info['width'],$this->info['height']);
        imagedestroy($this->image);
        $this->image=$image_thumb;
    }

    /**
     * 比例压缩或者扩大
     * @param $width
     */
    public function thumbWidth($width){
        $tmpWidth=$this->info['width'];
        $tmpHeight=$this->info['height'];
        if($tmpWidth>$width){
            $tempWidth=(float)($tmpWidth/$width);
            $height=(int)($tmpHeight/$tempWidth);
        }else{
            $tempWidth=(float)($width/$tmpWidth);
            $height=(int)($tmpHeight*$tempWidth);
        }
        $image_thumb=imagecreatetruecolor($width,$height);
        imagecopyresampled($image_thumb,$this->image,0,0,0,0,$width,$height,$tmpWidth,$tmpHeight);
        imagedestroy($this->image);
//        imagejpeg($image_thumb);
        $this->image=$image_thumb;
    }
    /**
     * 浏览器输出图片
     */
    public function show(){
        header("Content-type:".$this->info['mime']);
        $funs="image{$this->info['type']}";
        $funs($this->image);
    }

    /**
     * 原格式保存图片到硬盘中
     * @param $new_name
     */
    public function save($new_name){
        $funSave="image{$this->info['type']}";
        $funSave($this->image,$new_name.'.'.$this->info['type']);
    }

    /**
     *  jpeg保存图片到硬盘中
     * @param $new_name
     * @return bool
     */
    public function saveJpeg($new_name){
        return imagejpeg($this->image,$new_name.'.jpeg');
    }

    /**
     * png保存图片到硬盘中
     * @param $new_name
     * @return bool
     */
    public function savePng($new_name){
        return imagepng($this->image,$new_name.'.png');
    }

    /**
     * 销毁图片
     */
    public function __destruct()
    {
        imagedestroy($this->image);
    }
}