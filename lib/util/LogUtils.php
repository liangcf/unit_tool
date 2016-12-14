<?php

namespace util;
class LogUtils
{
    /**
     * 日志记录工具
     * @param string $file 日志名称
     * @param string $message 日志描述
     * @param null $context 日志内容
     * @param string $dir 日志路径
     * @return int
     */
    public static function log($file,$message,$context,$dir){
        $dir=rtrim($dir,'/');
        $dir=$dir.'/'.date('Ymd').'/';
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        if(!is_string($context)){
            $context=json_encode($context);
        }
        $fileName=$dir.$file.'.log';
        $date=date('Y-m-d H:i:s');
        $log='['.$date.'] - '.$message.' - '.$context."\r\n\r\n";
        return file_put_contents($fileName, $log,FILE_APPEND);
    }
}