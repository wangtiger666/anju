<?php

//解决不同服务器HTML转义的BUG
if (get_magic_quotes_gpc()) {

    function stripslashes_deep($value) {
        $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

function CC($word) {
    $readfile = APP_ROOT . "/App/Common/setting.php";

    if (is_file($readfile)) {
        require ($readfile);
        return $sysrow[$word];
    } else {
        freshsetting();
        require ($readfile);
        return $sysrow[$word];
    }
}

function freshsetting() {
    $readfile = APP_ROOT . "/App/Common/setting.php";
    $sysconfig = M("sysconfig")->select();
    $sysrow = array();
    foreach ($sysconfig as $sys) {
        $sysrow[$sys['varname']] = $sys['value'];
    }
    $text = "";
    $text .= '<?php' . "\r\n";
    $text .= '$sysrow = array();' . "\r\n";
    foreach ($sysrow as $sk => $sv) {
        $text .= '$sysrow["' . $sk . '"] = "' . $sv . '";' . "\r\n";
    }
    $text .= '?>' . "\r\n";
    writeinFile($readfile, $text);
}

/**
 * 写入文件
 * @param type $file 文件名称
 * @param type $text 写入内容
 */
function writeinFile($file, $text) {
    $fp_conf = fopen($file, "w");
    fwrite($fp_conf, $text);
    fclose($fp_conf);
}

function createFolder($path) {
    if (!file_exists($path)) {
        createFolder(dirname($path));
        mkdir($path, 0777);
    }
}
/**
 * 复制文件夹
 * @param type $src
 * @param type $dst
 */
function copyFolder($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                copyFolder($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    } closedir($dir);
}

/**
 * 删除目录
 *
 * @param unknown_type $indir
 */
function RemoveDirFiles($indir) {
    if (!is_dir($indir)) {
        return;
    }
    $dh = dir($indir);
    while ($filename = $dh->read()) {
        if ($filename == "." || $filename == "..") {
            continue;
        } else if (is_file("$indir/$filename")) {
            @unlink("$indir/$filename");
        } else {
            RemoveDirFiles("$indir/$filename");
        }
    }
    $dh->close();
    @rmdir($indir);
}

//select函数
function CteatSelect($n, $arr) {
    $r = "";
    foreach ($arr as $h => $v) {
        $k = explode("|", $v);
        if (($n != "") && $n == $k[0]) {
            $r .= "<option value=\"{$k[0]}\" selected>{$k[1]}</option>";
        } else if ($n == "" && $h == 0) {
            $r .= "<option value=\"{$k[0]}\" selected>{$k[1]}</option>";
        } else {
            $r .= "<option value=\"{$k[0]}\">{$k[1]}</option>";
        }
    }
    return $r;
}

function Checked($t1,$t2){
    $r = "";
    if($t1 == $t2){
        $r = ' checked="true"';
    }
    return $r;
}

function Selected($t1,$t2){
    $r = "";
    if($t1 == $t2){
        $r = ' selected="true"';
    }
    return $r;
}

function download($file_dir,$file_name)
 //参数说明：
 //file_dir:文件所在目录
 //file_name:文件名
 {
     $file_dir = chop($file_dir);//去掉路径中多余的空格
     //得出要下载的文件的路径
     if($file_dir != '')
     {
         $file_path = $file_dir;
         if(substr($file_dir,strlen($file_dir)-1,strlen($file_dir)) != '/')
             $file_path .= '/';
         $file_path .= $file_name;
     }
    else
         $file_path = $file_name;

    //判断要下载的文件是否存在
     if(!file_exists($file_path))
     {
         echo '对不起,你要下载的文件不存在。';
         return false;
     }
     $file_size = filesize($file_path);

    header("Content-type: application/octet-stream");
     header("Accept-Ranges: bytes");
     header("Accept-Length: $file_size");
     header("Content-Disposition: attachment; filename=".$file_name);

    $fp = fopen($file_path,"r");
     $buffer_size = 1024;
     $cur_pos = 0;

    while(!feof($fp)&&$file_size-$cur_pos>$buffer_size)
     {
         $buffer = fread($fp,$buffer_size);
         echo $buffer;
         $cur_pos += $buffer_size;
     }

    $buffer = fread($fp,$file_size-$cur_pos);
     echo $buffer;
     fclose($fp);
     return true;
 }
 
 
 function readPost($postarr, $delkey = array("dopost")) {
    $newarr = array();
    foreach ($postarr as $key => $value) {
        if (!in_array($key, $delkey)) {
            $newarr[$key] = $value;
        }
    }
    return $newarr;
}
function SpHtml2Text($str){
	$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
	$alltext = "";
	$start = 1;
	for($i=0;$i<strlen($str);$i++){
	if($start==0 && $str[$i]==">") $start = 1;
	else if($start==1){
	  if($str[$i]=="<"){ $start = 0; $alltext .= " "; }
	  else if(ord($str[$i])>31) $alltext .= $str[$i];
	}
	}
	$alltext = str_replace("　"," ",$alltext);
	$alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
	$alltext = preg_replace("/[ ]+/s"," ",$alltext);
	return $alltext;
}
function substring($str, $start, $length)
{
	$len = $length;
	if($length < 0){
		$str = strrev($str); 
		$len = -$length;
	}
	$len= ($len < strlen($str)) ? $len : strlen($str);
	for ($i= $start; $i < $len; $i ++){
		   if (ord(substr($str, $i, 1)) > 0xa0){
			 $tmpstr .= substr($str, $i, 2);
			 $i++;
		   }else {
			 $tmpstr .= substr($str, $i, 1);
		   }
	}
	if($length < 0) $tmpstr = strrev($tmpstr);
	return $tmpstr;
}
function ThumbWh($imgwidth,$imgheight,$width=200,$height=200)
{
	if($imgwidth>0 && $imgheight>0){
		if($imgwidth/$imgheight>=$width/$height){
			if($imgwidth>$width){
				$twidth = $width;
				$theight = ($imgheight*$width)/$imgwidth;
			}else{
				$twidth = $imgwidth;
				$theight = $imgheight;
			}
		}
		else
		{
			if($imgheight>$height){
				$theight = $height;
				$twidth = ($imgwidth*$height)/$imgheight;
			}else{
				$twidth = $imgwidth;
				$theight = $imgheight;
			}
		}
	}
	$imgarr['twidth'] = $twidth;
	$imgarr['theight'] = $theight;
	return $imgarr;
}

function read_file($filename)
{
    $fp = fopen($filename, "r") or die("couldn't open $filename");
    $read = fread($fp, filesize($filename));
    fclose($fp);
    return $read;
}

//2017.8.22
function panoViewlist($pano_id)
{
	if($pano_id<>0)
	{
		$viewlist = M("Pano_view")->where(array("pano_id"=>$pano_id))->order("sort")->select();
		return $viewlist;
	}
}



//2017.8.25
function guid($length = 32){
     mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
     $charid = strtolower(md5(uniqid(rand(), true)));
     if($length){
		$charid = substr($charid,8,$length);
     }
	 return $charid;
}

function file_get($url)
{
	if(function_exists('file_get_contents'))
	{  
		$response = file_get_contents($url);
	}
	else
	{
		$ch = curl_init();
		$timeout = 30;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$response = curl_exec($ch);
		curl_close($ch);
	}
	return $response;
}
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}


//$local_file 本地文件夹或文件 必须是全路径 /www/web/..../xx.jpg
//$origin_file 远程的文件   176/works/
function qiniu_upload_file($local_file , $origin_file)
{
	if (empty($local_file)||empty($origin_file)) {
		return false;
	}
	$qn_config = F("qn_config","","App/Common/");	
	require_once APP_ROOT."/source/qiniu/cls_qiniu.php";	
}

function getspotbyid($id)
{
	if(!$id) return false;
	$info = M("Pano_yunspot")->where(array("id"=>$id))->find();
	return $info;
}

function sendSms($phone,$code)
{
	if(empty($phone)||empty($code)) return false;
	$dxconfig = M("Dxconfig")->where(array("id"=>1))->find();
	if(empty($dxconfig['key'])||empty($dxconfig['mobanid'])){
		return false;
	}
	//发送验证码
	$appkey = $dxconfig['key']; #通过聚合申请到数据的appkey
	$tpl_id = $dxconfig['mobanid'];
	$url ='http://v.juhe.cn/sms/send'; #请求的数据接口URL
	$tpl_value = urlencode("#code#=".$code);

	$params ="key=".$appkey."&mobile=".$phone."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value;
	$content = juhecurl($url,$params,0);
	if($content){
		$result =json_decode($content,true);
		#错误码判断
		$error_code = $result['error_code'];
		if($error_code==0)
		{
			M("Yun_phonecode")->data(array("phone"=>$phone,"code"=>$code,"addtime"=>time()))->add();
			return true;
		}
		else
		{
			return false;
		}
	}
}
/*
	***请求接口，返回JSON数据
	***@url:接口地址
	***@params:传递的参数
	***@ispost:是否以POST提交，默认GET
*/
function juhecurl($url,$params=false,$ispost=0)
{
	$httpInfo = array();
	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_0 );
	curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
	curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
	if( $ispost )
	{
		curl_setopt( $ch , CURLOPT_POST , true );
		curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
		curl_setopt( $ch , CURLOPT_URL , $url );
	}
	else
	{
		if($params){
			curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
		}else{
			curl_setopt( $ch , CURLOPT_URL , $url);
		}
	}
	$response = curl_exec( $ch );
	if ($response === FALSE) {
		#echo "cURL Error: " . curl_error($ch);
		return false;
	}
	$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
	$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
	curl_close( $ch );
	return $response;
}


//支持手机 邮箱 用户名登录  如果是手机或邮箱注册  用户名自动 account 自动生成一个 
function getAccount($username)
{
	//根据用户名、邮箱、手机
	$user = "";
	if(preg_match("/^1[34578]\\d{9}$/", $username)){ //手机
		$user = get_user_by_phone($username);
	} elseif(strlen($username) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $username)){//邮箱
		$user = get_user_by_email($username);
	} else{
		$user = get_user_by_username($username);
	}
	return $user;
}
function get_user_by_phone($phone)
{
	$arr = M("Member")->where(array("phone"=>$phone))->find();
	return $arr;
}
function get_user_by_email($email)
{
	$arr = M("Member")->where(array("email"=>$email))->find();
	return $arr;
}
function get_user_by_username($account)
{
	$arr = M("Member")->where(array("account"=>$account))->find();
	return $arr;
}

function OutputDebugString($tag,$loginfo){
    $data = array(
		'tag'=>$tag,
        'logstring'=>$loginfo,
        'logtime'=>date('Y-m-d H:i:s'));
    M('debug_sql')->add($data);
}

function OutputDebugString_DB($tag){
    $last_sql = M()->getLastSql();
    OutputDebugString($tag,$last_sql);
}
?>
