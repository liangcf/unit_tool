<?php
/**
 * 下载工具类
 * @author wds
 *
 */
namespace util;
class DownloadUtils {
	/**
	 * @filename 下载文件全路径名
	 * @showname 下载显示的文件名
	 * @content  下载的内容
	 * @expire   下载内容浏览器缓存时间
	 * @buffer   设置大小输出
	 */
	function download ($filename, $showname='',$content='',$expire=180,$buffer=1024) {
		$filename = iconv("utf-8", "gb2312", $filename);
		if(is_file($filename)) {
			$length = filesize($filename);
		}elseif($content != '') {
			$length = strlen($content);
		}else {
			die('找不到文件！');
		}
		if(empty($showname)) {
			$showname = $filename;
		}
		$showname = basename($showname);
		//Http Header
		header("Pragma: public");
		header("Cache-control: max-age=".$expire);
		//header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
		header("Content-Disposition: attachment; filename=".$showname);
		header("Content-Length: ".$length);
		header("Content-type: application/octet-stream");
		header('Content-Encoding: none');
		header("Content-Transfer-Encoding: binary" );
		$fp=fopen($filename,"r");
		$file_size=filesize($filename);
		$file_count=0;
		//向浏览器返回数据
		while(!feof($fp) && $file_count<$file_size){
			$file_con=fread($fp,$buffer);
			$file_count+=$buffer;
			echo $file_con;
		}
		fclose($fp);
		exit();
	}
}
