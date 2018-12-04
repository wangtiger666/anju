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


//2017.8.22
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


function creatMenuFather($menuData){
    import("@.Class.mytag");
    global $menuArray;
    $dtp = new MyTagParse();
    $dtp->SetNameSpace('menu','<','>');
    $dtp->LoadSource($menuData);

    $father = array();
    $r = "";
    foreach($dtp->CTags as $k=>$val){
        if($val->GetName()=='father' && ($val->GetAtt('rank')=='' || TestPower($val->GetAtt('rank')) )){
            $num = $val->GetAtt('id');
            $txt = $val->GetAtt('name');
            $father[$num] = $txt;
        }
    }

    $menuArray['total'] = count($father);
    for($u=1;$u<=$menuArray['total'];$u++){
        if($u%2==0){
            $r .= "<li class=\"rightb\">{$father[$u]}</li>\r\n";
        }else{
            $r .= "<li>{$father[$u]}</li>\r\n";
        }
    }
    return $r;
}

function creatMenuList($menuData){
    import("@.Class.mytag");
    global $menuArray;
    $dtp = new MyTagParse();
    $dtp->SetNameSpace('menu','<','>');
    $dtp->LoadSource($menuData);
    $dtp2 = new MyTagParse();
    $dtp2->SetNameSpace('menu','<','>');
    $baseurl = CC("web_root");
    $topos = "main";

    foreach($dtp->CTags as $k=>$val){
        if($val->GetName()=='top' && ($val->GetAtt('rank')=='' || TestPower($val->GetAtt('rank')) )){
            $num = $val->GetAtt('id');
            $menuListTittle = $val->GetAtt('name');
            if($val->GetAtt('display')=="none"){
                $menuDisplay = "none";
            }else{
                $menuDisplay = "block";
            }
            $menuArray['list'][$num] .= "\r\n<div class=\"cube\">\r\n";
            $menuArray['list'][$num] .= "<div class=\"title\">$menuListTittle</div>\r\n<div class=\"body\" style=\"display:$menuDisplay\">\r\n";

            $dtp2->LoadSource($val->InnerText);
            $pid = 0;
            foreach($dtp2->CTags as $k2=>$val2){
                if($val2->GetName()=='item' && ($val2->GetAtt('rank')=='' || TestPower($val2->GetAtt('rank')) )){
                    if($val2->GetAtt('link') == ""){
                        $linkto = CC("web_root")."/";
                    }else{
                        $linkto = U($val2->GetAtt('link'));
                    }
                    $menuArray['list'][$num] .= "<div class=\"link\"><a href=\"{$linkto}\" target=\"$topos\">{$val2->GetAtt('name')}</a></div>\r\n";
                }
                $pid ++;
            }
            $menuArray['list'][$num] .="</div>\r\n<div class=\"shadow\">\r\n</div></div>\r\n";
        }
    }
    $r = "";
    for($u=1;$u<=$menuArray['total'];$u++){
        $r .= "<div class=\"menubox\">{$menuArray['list'][$u]}</div>";
    }
    return $r;
}
//-----------------------Index------------------------End

//-----------------------Filemaster------------------------
function cheakfiletype($string, $file) {
    $arr = explode(".", $file);
    $num = count($arr) - 1;
    if ($string == $arr[$num] && count($arr) > 1) {
        return TRUE;
    } else {
        return FALSE;
    }
}
//-----------------------Filemaster------------------------End


//-----------------------Database------------------------
function TjCount($tbname, &$mydb) {
    $row = $mydb->GetOne("SELECT COUNT(*) AS dd FROM $tbname");
    return $row['dd'];
}
function RpLine($str) {
    $str = str_replace("\r", "\\r", $str);
    $str = str_replace("\n", "\\n", $str);
    return $str;
}

//-----------------------Database------------------------End

//-----------------------Group------------------------
//验证权限（生成功能列表时候用到）
function TestGroupPower($n, $power) {
    $res = FALSE;
    $myPower = $power;

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
//-----------------------Group------------------------End

//-----------------------Updata------------------------
function getUpdataLink() {
    $host = $_SERVER['HTTP_HOST'];
    $http_host = explode(".", $host);
    $http_host = array_reverse($http_host);
    if (count($http_host) > 2) {
        $domain_arr = array("com", "net", "org", "gov");
        if (in_array($http_host[1], $domain_arr)) {
            $host = $http_host[2] . "." . $http_host[1] . "." . $http_host[0];
        } else {
            $host = $http_host[1] . "." . $http_host[0];
        }
    }
    $ip = get_client_ip();

    $ban = getBan();
    $pro_code = C("UPDATA_CODE");
    $url = C("UPDATA_URL") . "/updata/json/index/pro_code/{$pro_code}/ban/{$ban}/host/$host/ip/$ip";
    return $url;
}
function getUpdataOkLink(){
    $host = $_SERVER['HTTP_HOST'];
    $ip = get_client_ip();

    $ban = getBan();
    $pro_code = C("UPDATA_CODE");
    $url = C("UPDATA_URL") . "/updata/json/ok/pro_code/{$pro_code}/ban/{$ban}/host/$host/ip/$ip";
    return $url;
}
function getBan(){
    $ban_file = APP_PATH . 'Lib/Inc/ban.txt';
    if(!is_file($ban_file)){
        WriteFile($ban_file, "0");
    }
    $ban_text = file($ban_file);
    $ban = $ban_text[0];
    return $ban;
}
function saveBan($text){
    $ban_file = APP_PATH . 'Lib/Inc/ban.txt';
    WriteFile($ban_file, $text);
}
//获取文件夹里的所有文件路径
function getfile($inpath) {
    $linkdata = "";
    $dh = dir($inpath);
    $files = $dirs = array();
    while (($file = $dh->read()) !== false) {
        if ($file != "." && $file != ".." && !is_dir("$inpath/$file")) {
            $linkdata .= $inpath . "/" . $file . "<br/>";
        } else if ($file != "." && $file != ".." && is_dir("$inpath/$file")) {
            $inpath2 = $inpath . "/" . $file;
            $linkdata .= getfile($inpath2);
        }
    }
    return $linkdata;
}

//获取文件夹里的所有文件夹路径
function getbag($inpath) {
    $linkdata = "";
    $dh = dir($inpath);
    $files = $dirs = array();
    while (($file = $dh->read()) !== false) {
        if ($file != "." && $file != ".." && is_dir("$inpath/$file")) {
            $linkdata .= $inpath . "/" . $file . "<br/>";
            $inpath2 = $inpath . "/" . $file;
            $linkdata .= getbag($inpath2);
        }
    }
    return $linkdata;
}

/**
 * 写入文件
 * @param type $file 文件名称
 * @param type $text 写入内容
 */
function WriteFile($file, $text) {
    $fp_conf = fopen($file, "w");
    fwrite($fp_conf, $text);
    fclose($fp_conf);
}

//-----------------------Updata------------------------End
?>
