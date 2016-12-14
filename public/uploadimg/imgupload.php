<?php

	class uploadFile {
		private $imgupload;
		private $fileType;		// 文件类型
		private $fileTypeArr = array('png','jpg','gif');		// 支持的文件类型
		private $data;
		private $successMsg = "图片上传成功";
		private $failMsg = "图片上传失败";
		private $failTypeMsg = "上传文件格式错误";
		private $path = '';	// 上传文件路径


		/**
		 * @param array $fileTypeArr; defaults to an empty array
		 */
		public function __construct($path = null) {
			if(isset($path) && !empty($path)) {
	    		$this->setOption('path',$path);
	    	}else {
	    		return array("retCode" => 2,"msg" => "path is empty");
	    	}
			$this->setOption('data',$_REQUEST);
			$fileType = substr(strstr($this->data['data'],'/'),1,3);
			$this->setOption('fileType',$fileType);
		}

		private function toBytes($str){
	        $val = trim($str);
	        $last = strtolower($str[strlen($str)-1]);
	        switch($last) {
	            case 'g': $val *= 1024;
	            case 'm': $val *= 1024;
	            case 'k': $val *= 1024;        
	        }
	        return $val;
	    }

		private function setOption($key, $val) {
            $this->$key = $val;
        }

        /* 设置随机文件名 */
        private function setName() {
            //$fileName = date('YmdHis')."_".rand(100,99999);
            $fileName = $this->createGuidMd5();
            return $fileName.'.'.$this->fileType;
        }

        /**
         * 32位GUID生成方法，算法为md5
         * 自定义guid生成规则: uniqid(prefix + mt_rand , true)
         *   碰撞测试：用for循环获取了100万个guid，没有出现重复情况  @20150412
         * $prefix 前缀混淆串，能有效增加GUID的唯一性。一般传入当前记录的唯一归属ID、所属类型等
         *
         * @param string $prefix
         * @return string
         */
        private function createGuidMd5($prefix=null){
            // mt_rand() 马特赛特旋转演算法，可以快速产生高质量的伪随机数，修正了古老随机数产生算法的很多缺陷
            return strtolower(md5(uniqid($prefix . mt_rand(), true)));
        }

        /* 创建文件夹 */
        private function createFolder($path,$date) {
            $realPath = $path.'/'.$date.'/';
            if (!file_exists($realPath)){
                mkdir($realPath, 0777,true);
                chmod($realPath,0777);
            }
            return $realPath;
        }

        public function upload() {
        	try {
	        	$date = date("ymd");
	        	$base64_url = $this->data['data'];
	        	$base64_body = substr(strstr($base64_url,','),1);
	        	$data= base64_decode($base64_body);
	        	$fileName = $this->setName();
	        	$path = $this->createFolder($this->path,$date) . $fileName;
				$a = file_put_contents($path, $data);	//返回的是字节数
	        	return array("retCode" => 1,"msg" => $this->successMsg , "fileName" => $fileName,'path' =>'/'.$date.'/'.$fileName);
        	}catch(\Exception $e) {
        		return array("retCode" => 0,"msg" => $this->failMsg);
        	}
        }

	}
 ?>