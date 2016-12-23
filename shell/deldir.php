<?php
header("Content-Type: text/html; charset=UTF-8");
require "../lib/Run.php";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**********开始之处*****************************************************************************************************************************/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////循环删除目录和文件函数
function del_dir($dirName){
    if ($handle=opendir($dirName)){
        while (false!==($item=readdir($handle))){
            if ($item!="."&&$item!=".."){
                if (is_dir($dirName.'/'.$item)){
                    del_dir($dirName.'/'.$item);
                } else {
                    //unlink($dirName.'/'.$item);
                    echo $dirName.'/'.$item."\r\n\r\n";
                }
            }
        }
        closedir($handle);
        //rmdir($dirName);//删除文件夹
        echo "delete file".$dirName."\r\n\r\n";
    }
}

$cameraId=\util\UuidUtils::uuid();
$url=$cameraId;
$publicDir=__DIR__.'/public/qrcode/100';
if(!is_dir($publicDir)){
    mkdir($publicDir,0777,true);
}
$qrCode=new \util\QRCodeUtils();
$codeInfo=$qrCode->getQr($url,$publicDir,$cameraId);
p(strstr($codeInfo,'/qrcode'));
