<?php
class logUtils
{
    /**
     * 日志记录工具
     * @param string $file 日志名称
     * @param string $message 日志描述
     * @param string $context 日志内容
     * @param string $dir 日志路径
     * @return int
     */
    public static function log($file,$message,$context,$dir='logs/'){
        $rootDir=_BASE_PATH.'/';
        $dirTmp=$rootDir.$dir;
        if(!is_dir($dirTmp)){
            mkdir($dirTmp,0777,true);
        }
        $dirTemp=$dirTmp.date('Ymd').'/';
        if(!is_dir($dirTemp)){
            mkdir($dirTemp,0777,true);
        }
        $fileName=$dirTemp.$file;
        $date=date('Y-m-d H:i:s');
        $log='['.$date.'] - '.$message.' - '.$context."\r\n\r\n";
        return file_put_contents($fileName, $log,FILE_APPEND);
    }
}