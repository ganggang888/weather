<?php
class encry
 {
 	private $keyOne = null;
 	private $keyTwo = null;
 	public function __construct($argv)
 	{

 		$this->keyOne = $this->RandAbc();
 		$this->keyTwo = $this->RandAbc();
 		echo $this->checkFile($argv);
 	}
 	/**
 	 * 返回随机字符串
 	 */
 	public function RandAbc()
 	{
 		return str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"); 
 	}


 	/*
    * 遍历文件夹下所有文件
    *
    */
   function getfiles($path) {
        $arr = [];
        foreach (scandir($path) as $afile) {
            if ($afile == '.' || $afile == '..')
                continue;
            if (is_dir($path . '/' . $afile)) {
                self::getfiles($path . '/' . $afile);
            } else {
                $arr[] = $path . '/' . $afile;
            }
        }
        return $arr;
    }

    /**
     * 
     */
    public function checkFile($argv)
    {
    	if (count($argv) != 2) {
    		return "缺少参数";
    	} else {
    		//开始处理数据
    		$arr = $this->getfiles($argv[1]);
    		$arr = array_filter($arr,function($vo){ return strstr($vo,'.php');});
    		if (!$arr) {
    			return "目录下文件为空";
    		} else {
    			//开始处理php文件，在目录下生成
    			foreach ($arr as $vo) {
    				$this->doEncry($vo);
    			}
    		}
    	}
    }

    /**
     * 加密文件
     */
    public function doEncry($filename)
    {
 		 $vstr = file_get_contents($filename);
		 $v1 = base64_encode($vstr);  
		 $c = strtr($v1, $this->keyOne, $this->keyTwo); //根据密匙替换对应字符。  
		 $c = $this->keyOne.$this->keyTwo.$c;  
		 $q1 = "O00O0O";  
		 $q2 = "O0O000";  
		 $q3 = "O0OO00";  
		 $q4 = "OO0O00";  
		 $q5 = "OO0000";  
		 $q6 = "O00OO0";  
		 $s = '$'.$q6.'=urldecode("%6E1%7A%62%2F%6D%615%5C%76%740%6928%2D%70%78%75%71%79%2A6%6C%72%6B%64%679%5F%65%68%63%73%77%6F4%2B%6637%6A");$'.$q1.'=$'.$q6.'{3}.$'.$q6.'{6}.$'.$q6.'{33}.$'.$q6.'{30};$'.$q3.'=$'.$q6.'{33}.$'.$q6.'{10}.$'.$q6.'{24}.$'.$q6.'{10}.$'.$q6.'{24};$'.$q4.'=$'.$q3.'{0}.$'.$q6.'{18}.$'.$q6.'{3}.$'.$q3.'{0}.$'.$q3.'{1}.$'.$q6.'{24};$'.$q5.'=$'.$q6.'{7}.$'.$q6.'{13};$'.$q1.'.=$'.$q6.'{22}.$'.$q6.'{36}.$'.$q6.'{29}.$'.$q6.'{26}.$'.$q6.'{30}.$'.$q6.'{32}.$'.$q6.'{35}.$'.$q6.'{26}.$'.$q6.'{30};eval($'.$q1.'("'.base64_encode('$'.$q2.'="'.$c.'";eval(\'?>\'.$'.$q1.'($'.$q3.'($'.$q4.'($'.$q2.',$'.$q5.'*2),$'.$q4.'($'.$q2.',$'.$q5.',$'.$q5.'),$'.$q4.'($'.$q2.',0,$'.$q5.'))));').'"));'; 
          $s = '<?php '."\n".$s."\n".' ?>'; 
          $newFile = str_replace('.php', '_temp.php', $filename);
		  $fpp1 = fopen($newFile, 'w');  
 		  fwrite($fpp1, $s);
    }
 }
 new encry($argv);