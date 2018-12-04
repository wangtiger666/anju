<?php

//-----------------------Index------------------------
/**
 * 判断权限
 * @param string $n
 * @return boolean
 */
function TestPower($n) {
    $res = FALSE;
    $myPower = session(C("SESSION_POWER"));
    if (preg_match('/All_power/i', $myPower)) {
        return TRUE;
    }
    if ($n == "") {
        return TRUE;
    }
    $Powers = explode(",", $myPower);
    $ns = explode(',', $n);
    foreach ($ns as $n) {
        if ($n == '') {
            continue;
        }
        if (in_array($n, $Powers)) {
            $res = TRUE;
            break;
        }
    }
    return $res;
}

function creatMenuList($menuData, $topos='main') {
    $menuArray = array();
    $result = "";
    $opendata = "";
    $openid = 0;
    $dex = 1;
    import("@.Class.mytag");
    $dtp = new MyTagParse();
    $dtp->SetNameSpace('menu', '<', '>');
    $dtp->LoadSource($menuData);
    $dtp2 = new MyTagParse();
    $dtp2->SetNameSpace('menu', '<', '>');
    foreach ($dtp->CTags as $k => $val) {
        if ($val->GetName() == 'top' && ($val->GetAtt('rank') == '' || TestPower($val->GetAtt('rank')) )) {
            $menuListTittle = $val->GetAtt('name');
            $menuOpen = $val->GetAtt('open');
            $ico = $val->GetAtt('ico');
            if ($menuOpen == 1) {
                if ($opendata != "") {
                    $opendata .= ",";
                }
                $opendata .= '"' . $openid . '"';
            }
            $menuArray[$dex] .= "<div class=\"cubebox\">\r\n";
            $menuArray[$dex] .= "<div class=\"cube_parent\">\r\n";
            $icourl = __PUBLIC__ . "/member/images/index/";
            if ($ico == "") {
                $icourl .= "auto.png";
            } else {
                $icourl .= $ico;
            }
            $menuArray[$dex] .= "<div class=\"icon\" title=\"$menuListTittle\" style=\"background:url($icourl) center center no-repeat;\"></div>\r\n<div class=\"icontitle\">$menuListTittle</div>\r\n";
            $menuArray[$dex] .= "</div>\r\n";
            $menuArray[$dex] .= "<div class=\"cube_child\">\r\n";
            $dtp2->LoadSource($val->InnerText);
            foreach ($dtp2->CTags as $k2 => $val2) {
                if ($val2->GetName() == 'item' && ($val2->GetAtt('rank') == '' || TestPower($val2->GetAtt('rank')) )) {
                    $linkto = U($val2->GetAtt('link'));
                    $menuArray[$dex] .= "<div class=\"cube_chbox\"><a href=\"" . "{$linkto}\" target=\"$topos\">{$val2->GetAtt('name')}</a></div>\r\n";
                }
            }
            $menuArray[$dex] .= "</div>\r\n";
            $menuArray[$dex] .= "</div>\r\n";
            $openid++;
        } else {
            $openid = $val->GetAtt('id');
            $menuArray[$dex] .= "";
        }
        $dex++;
    }
    for ($u = 1; $u <= count($menuArray); $u++) {
        $result .= $menuArray[$u];
    }
    $result .= '<script>' . "var openchannel = new Array($opendata);makemenu();" . '</script>';
    return $result;
}

//-----------------------Index------------------------END

/**
 * 快速添加上传
 * @param string $action_temp 触发的id或class
 * @param string $inputdata 返回input的id或class
 * @param string $imgdata 返回图片img的id或class
 */
function uploadSend($action_temp, $inputdata, $imgdata="") {
    $html = "";
    $func_name = str_replace(".", "", str_replace("#", "", $inputdata));
    $html.= "<script type=\"text/javascript\">\r\n";
    $html.= "$(function(){\r\n";
    $html.= "$(\"{$action_temp}\").uploader(\"image\", \"{$func_name}\");\r\n";
    $html.= "})\r\n";
    $html.= "function {$func_name}(data){\r\n";
    $html.= "$(\"{$inputdata}\").val(data);\r\n";
    if ($imgdata != "") {
        $web_root = CC('web_root');
        $html.= "$(\"{$imgdata}\").attr(\"src\",\"$web_root\"+data);\r\n";
    }
    $html.= "}\r\n";
    $html.= "</script>";
    echo $html;
}

/**
 * 快速添加上传（填入图片）
 * @param string $action_temp 触发的id或class
 * @param string $inputdata 返回input的id或class
 * @param string $imgdata 填入图片img的id或class
 */
function uploadFill($action_temp, $inputdata, $imgdata="", $width="100", $height="100") {
    $html = "";
    $func_name = str_replace(".", "", str_replace("#", "", $inputdata));
    $html.= "<script type=\"text/javascript\">\r\n";
    $html.= "$(function(){\r\n";
    $html.= "$(\"{$action_temp}\").uploader(\"image\", \"{$func_name}\");\r\n";
    $html.= "})\r\n";
    $html.= "function {$func_name}(data){\r\n";
    $html.= "$(\"{$inputdata}\").val(data);\r\n";
    if ($imgdata != "") {
        $web_root = CC('web_root');
        $html.= "$(\"{$imgdata}\").html('<img src=\"{$web_root}'+data+'\" onload=\"photocenterin(this,$width,$height)\" />');\r\n";
    }
    $html.= "}\r\n";
    $html.= "</script>";
    echo $html;
}

function MoveThumbPhoto($file, $member_id, $pano_id, $filedir_base, $rename="thumb") {
    $weburl = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    if (!is_dir($filedir)) {
        createFolder($filedir);
    }
    $oldname = basename($file);

    if ($rename == "") {
        $newname = $oldname;
    } else {
        $arr = explode(".", $oldname);
        $newname = $rename . "." . $arr[1];
    }
    if (is_file($filedir . "/" . $newname)) {
        unlink($filedir . "/" . $newname);
    }
    rename(APP_ROOT . $file, $filedir . "/" . $newname);
    return $weburl . "/" . $newname;
}

function MoveThumbPhotovideo($file, $member_id, $pano_id, $filedir_base) {
    $weburl = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    if (!is_dir($filedir)) {
        createFolder($filedir);
    }
    $oldname = basename($file);

    if ($rename == "") {
        $newname = $oldname;
    } else {
        $arr = explode(".", $oldname);
        $newname = "." . $arr[1];
    }
    if (is_file($filedir . "/" . $newname)) {
        unlink($filedir . "/" . $newname);
    }
    rename(APP_ROOT . $file, $filedir . "/" . $newname);
    return $weburl . "/" . $newname;
}

function CopyThumbPhoto($file, $member_id, $pano_id, $filedir_base, $rename="thumb") {
    $weburl = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    if (!is_dir($filedir)) {
        createFolder($filedir);
    }
    $oldname = basename($file);

    if ($rename == "") {
        $newname = $oldname;
    } else {
        $arr = explode(".", $oldname);
        $newname = $rename . "." . $arr[1];
    }
    if (is_file($filedir . "/" . $newname)) {
        unlink($filedir . "/" . $newname);
    }
    copy(APP_ROOT . $file, $filedir . "/" . $newname);
    return $weburl . "/" . $newname;
}


function CopyThumbPhotovideo($file, $member_id, $pano_id, $filedir_base, $rename="") {
    $weburl = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    if (!is_dir($filedir)) {
        createFolder($filedir);
    }
    $oldname = basename($file);

    if ($rename == "") {
        $newname = $oldname;
    } else {
        $arr = explode(".", $oldname);
        $newname = $rename . "." . $arr[1];
    }
    if (is_file($filedir . "/" . $newname)) {
        unlink($filedir . "/" . $newname);
    }
    copy(APP_ROOT . $file, $filedir . "/" . $newname);
    return $weburl . "/" . $newname;
}


function MoveViewPhoto($file, $member_id, $pano_id, $filedir_base, $rename="") {
    $weburl = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base . "/view";
    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base . "/view";
    if (!is_dir($filedir)) {
        createFolder($filedir);
    }
    $oldname = basename($file);

    if ($rename == "") {
        $newname = $oldname;
    } else {
        $arr = explode(".", $oldname);
        $newname = "pano_" . substr($rename, 0, 1) . "." . $arr[1];
    }
    if (is_file($filedir . "/" . $newname)) {
        unlink($filedir . "/" . $newname);
    }

    import("@.Class.BoluoGD");
    $filename = APP_ROOT . $file;
    $fileData = getimagesize($filename);
    if ($fileData[0] <= 512) {
        $H = 512;
    } else if ($fileData[0] <= 1024) {
        $H = 1024;
    } else {
        $H = 2048;
    }
    $BG = new BoluoGD();
    $BG->creatImg($H, $H, "#FFFFFF");

    $BG->copyImg($filename, array(0, 0, 0, 0), array(0, 0, $H, $H));
    $BG->save($filedir . "/" . $newname, "jpg");

//    rename(APP_ROOT . $file, $filedir . "/" . $newname);
    return $weburl . "/" . $newname;
}

//3D全景图转化   2018.6.20
function MoveView3dPhoto($file,$member_id) {
	$filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base . "/view";
    import("@.Class.BoluoGD");
    $filename = $file;
    $fileData = getimagesize($filename);	
    if ($fileData[0] != 6000) {       
        $H = 6000;        
        $BG = new BoluoGD();
        $BG->creatImg($H, $H, "#FFFFFF");

        $BG->copyImg($filename, array(0, 0, 0, 0), array(0, 0, $H, $H));
        unlink($filename);
        $BG->save($filename, "jpg");
        return $filename;
    } 
}
//3D全景图转化   2018.6.20 end


function checkPano($file) {
    import("@.Class.BoluoGD");
    $filename = APP_ROOT . $file;
    $fileData = getimagesize($filename);
    if ($fileData[0] % 256 != 0) {
        if ($fileData[0] <= 512) {
            $H = 512;
        } else if ($fileData[0] <= 1024) {
            $H = 1024;
        } else {
            $H = 2048;
        }
        $BG = new BoluoGD();
        $BG->creatImg($H, $H, "#FFFFFF");

        $BG->copyImg($filename, array(0, 0, 0, 0), array(0, 0, $H, $H));
        unlink($filename);
        $BG->save($filename, "jpg");
        return 1;
    }else{
        return 0;
    }
}

function CopyImage($oldimg, $newimg, $w, $h, $k) {
    if (is_file($newimg)) {
        unlink($newimg);
    }
    $arr = getimagesize($oldimg);
    $width = $arr[0];
    $height = $arr[1];
    $oldtype = $arr[2];
    if ($w > $width) {
        $w = $width;
    }
    if ($h > $height) {
        $h = $height;
    }
    $testpicture = imagecreatetruecolor($w, $h);
    if ($oldtype == 1) {
        $gettestimg = imagecreatefromgif($oldimg);
    } else if ($oldtype == 2) {
        $gettestimg = imagecreatefromjpeg($oldimg);
    } else {
        $gettestimg = imagecreatefrompng($oldimg);
    }
    imagecopyresized($testpicture, $gettestimg, 0, 0, 0, 0, $w, $h, $width, $height);
    imagejpeg($testpicture, $newimg, $k);
}

function ViewDir($member_id, $view_id) {
    $viewwhere = array(
        "id" => $view_id
    );
    $viewrow = M("Pano_view")->where($viewwhere)->find();
    $filedir_base = $viewrow['filedir'];
    $pano_id = $viewrow['pano_id'];

    $filedir = "/uploads/user/" . substr(md5($member_id), 5, 15) . "/" . $pano_id . "/" . $filedir_base;
    return $filedir;
}

function get_member_id() {
    $member_pix = C("SESSION_MEMBERID");
    $member_id = $_SESSION[$member_pix];
    return $member_id;
}

function getFirstScene($pano_id) {
    $member_id = get_member_id();
    $where = array(
        "first_read" => 1,
        "member_id" => $member_id,
        "pano_id" => $pano_id
    );
    $row = M("Pano_view")->where($where)->select();
    $len = count($row);
    if ($len != 1) {
        $where1 = array(
            "member_id" => $member_id,
            "pano_id" => $pano_id
        );
        $data1 = array(
            "first_read" => 0
        );
        M("Pano_view")->where($where1)->save($data1);

        $where2 = array(
            "member_id" => $member_id,
            "pano_id" => $pano_id,
            "sort" => 1
        );
        $data2 = array(
            "first_read" => 1
        );
        M("Pano_view")->where($where2)->save($data2);
        $therow = M("Pano_view")->where($where2)->find();
        return $therow;
    } else {
        $therow = M("Pano_view")->where($where)->find();
        return $therow;
    }
}

function CreatPhoto($action_chu, $action_to, $action_key, $photoarr) {
    if (!is_array($photoarr)) {
        $photoarr = array();
    }
    include (APP_ROOT . "/" . APP_TMPL_PATH . "/commontemp/photo.html");
}

function ExecUpload($photo, $oldphoto, $dir="") {
    if ($photo != "") {
        if ($photo == $oldphoto) {
            return $oldphoto;
        } else {
            @unlink(APP_ROOT . $oldphoto);
            $oldname = APP_ROOT . $photo;
            if (!is_dir(APP_ROOT . $dir)) {
                createFolder(APP_ROOT . $dir);
            }
            $newname = APP_ROOT . $dir . "/" . basename($photo);
            if (substr_count($oldname, "station") > 0) {
                rename($oldname, $newname);
            } else {
                copy($oldname, $newname);
            }
            return $dir . "/" . basename($photo);
        }
    }
}

function savePhotoStore($imgurlarr, $imgidarr, $imgtextarr, $member_id, $pano_id, $type, $from_id) {
    $havewhere = array(
        "pano_id" => $pano_id,
        "from_id" => $from_id,
        "type" => $type
    );
    $haverow = M("Imagestore")->where($havewhere)->select();
    $haveid = array();
    foreach ($haverow as $k => $hrow) {
        $haveid[$hrow["id"]] = 1;
    }

    $total = count($imgurlarr);
    for ($i = 0; $i < $total; $i++) {
        $ppa = $i + 1;
        if ($imgidarr[$i] == "") {
            $fileurl = "/uploads/user/" . substr(md5($member_id), 5, 15) . "/store/photo";
            $img = ExecUpload($imgurlarr[$i], "", $fileurl);
            $data = array(
                "member_id" => $member_id,
                "pano_id" => $pano_id,
                "from_id" => $from_id,
                "type" => $type,
                "imagename" => $imgtextarr[$i],
                "imageurl" => $img,
                "sort" => $ppa
            );
            M("Imagestore")->add($data);
        } else {
            $data = array(
                "imagename" => $imgtextarr[$i],
                "sort" => $ppa
            );
            $res = M("Imagestore")->where(array("id" => $imgidarr[$i]))->save($data);
            unset($haveid[$imgidarr[$i]]);
        }
    }

    foreach ($haveid as $hi => $value) {
        M("Imagestore")->where(array("id" => $hi))->delete();
    }
}

function getScene($scene_id, $key="sort") {
    $row = M("Pano_view")->where(array("id" => $scene_id))->find();
    if ($key == "sort") {
        return "scene" . $row[$key];
    } else {
        return $row[$key];
    }
}

function zhutext($text) {
    $text = str_replace("\r\n", "[br]", $text);
    return $text;
}

function clearPian($src) {
    $a = array(
        1 => "01",
        2 => "02",
        3 => "03",
        4 => "04",
        5 => "05",
        6 => "06",
        7 => "07",
        8 => "08",
        9 => "09"
    );
    $dir = opendir($src);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                $olddir = $src . '/' . $file;
                $newdir = $file;
                foreach ($a as $k => $v) {
                    $newdir = str_replace($v, $k, $newdir);
                }
                $newdir = $src . '/' . $newdir;
                if ($olddir != $newdir) {
                    rename($olddir, $newdir);
                }
                clearPian($newdir);
            } else {
                $olddir = $src . '/' . $file;
                $newdir = $file;
                foreach ($a as $k => $v) {
                    $newdir = str_replace($v, $k, $newdir);
                }
                $newdir = $src . '/' . $newdir;
                if ($olddir != $newdir) {
                    rename($olddir, $newdir);
                }
            }
        }
    }
    closedir($dir);
}

function clearHtml($content) {
//    $content = preg_replace("/\r\n/i", "", $content);
//    $content = preg_replace("/<br[^>]*\/>/i", "\r\n", $content);


    $content = preg_replace("/<div[^>]*>/i", "", $content);
    $content = preg_replace("/<\/div>/i", "", $content);

    $content = preg_replace("/<p[^>]*>/i", "[p]", $content);
    $content = preg_replace("/<\/p>/i", "[/p]", $content);

    $content = preg_replace("/<strong[^>]*>/i", "[b]", $content);
    $content = preg_replace("/<\/strong>/i", "[/b]", $content);

    $content = preg_replace("/</i", "[", $content);
    $content = preg_replace("/>/i", "]", $content);

    $content = preg_replace("/\"/i", "'", $content);

    $content = preg_replace("/&nbsp;/i", " ", $content);


    return $content;
}

function lfthumb($srcfile,$tofile,$width="500",$height="500")
{
	if(empty($srcfile)) return false;
	copy($srcfile,$tofile);
	$imagesize = getimagesize($srcfile);
	$imgwidth = $imagesize[0];
	$imgheight = $imagesize[1];
	$thumb = ThumbWh($imgwidth,$imgheight,$width,$height);
	$twidth = $thumb['twidth'];
	$theight = $thumb['theight'];
	import("@.Class.image");
	$image = new image($tofile);
	$image->thumb(ceil($twidth), ceil($theight));
}

function videothumb($srcfile,$tofile,$width="800",$height="400")
{
	if(empty($srcfile)) return false;
	copy($srcfile,$tofile);
	$imagesize = getimagesize($srcfile);
	$imgwidth = $imagesize[0];
	$imgheight = $imagesize[1];
	$thumb = ThumbWh($imgwidth,$imgheight,$width,$height);
	$twidth = $thumb['twidth'];
	$theight = $thumb['theight'];
	import("@.Class.image");
	$image = new image($tofile);
	$image->thumb(ceil($twidth), ceil($theight));
}

	/**
     * 十六进制 转 RGB  
	 2018.3.28     许峰
     */
	function hex2rgb( $colour ) { 
    if ( $colour[0] == '#' ) { 
        $colour = substr( $colour, 1 ); 
    } 
    if ( strlen( $colour ) == 6 ) { 
        list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] ); 
    } elseif ( strlen( $colour ) == 3 ) { 
        list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] ); 
    } else { 
        return false; 
    } 
    $r = hexdec( $r ); 
    $g = hexdec( $g ); 
    $b = hexdec( $b ); 
	$colour =$r.",".$g.",".$b;
    return $colour;
} 

/**
     * RGB转 十六进制
     * @param $rgb RGB颜色的字符串 如：rgb(255,255,255);
     * @return string 十六进制颜色值 如：#FFFFFF
	 2018.3.28     许峰
     */
    function RGBToHex($rgb){
    $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
    $re = preg_match($regexp, $rgb, $match);
    $re = array_shift($match);
    $hexColor = "#";
    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    for ($i = 0; $i < 3; $i++) {
		$r = null;
		$c = $match[$i];
		$hexAr = array();
		while ($c > 16) {
			$r = $c % 16;
			$c = ($c / 16) >> 0;
			array_push($hexAr, $hex[$r]);
		}
		array_push($hexAr, $hex[$c]);
		$ret = array_reverse($hexAr);
		$item = implode('', $ret);
		$item = str_pad($item, 2, '0', STR_PAD_LEFT);
		$hexColor .= $item;
    }
    return $hexColor;
}
?>
