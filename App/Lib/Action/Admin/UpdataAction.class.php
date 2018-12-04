<?php

class UpdataAction extends AdminAction {

    public function index() {
        $url = getUpdataLink();
        $json = json_decode(file_get_contents($url));
        if ($json->result == 1) {
            $word = $json->info;
            $word1 = str_replace("&lt;", "<", $word);
            $word2 = str_replace("&gt;", ">", $word1);
            $res = array(
                "result" => 1,
                "nowban" => getBan(),
                "ban" => $json->ban,
                "date" => $json->date,
                "size" => $json->size,
                "info" => $word2
            );
        } else {
            $res = array(
                "result" => 0,
                "nowban" => getBan()
            );
        }
        $this->assign("res", $res);
        $this->display();
    }

    public function done() {
        $this->display();
    }

    public function ajax() {
        if (I("post.dopost") == "step1") {
            if (!is_dir(APP_ROOT . "/updata")) {
                mkdir(APP_ROOT . "/updata");
            }
            $url = getUpdataLink();
            $json = json_decode(file_get_contents($url));
            if ($json->result == 1) {
                if ($json->ban == getBan()) {
                    $res = array(
                        "result" => 3,
                        "ban" => $json->ban,
                        "file" => $json->url,
                        "size" => $json->size,
                        "date" => $json->date
                    );
                    echo json_encode($res);
                    exit();
                }
                $res = array(
                    "result" => 1,
                    "ban" => $json->ban,
                    "file" => $json->url,
                    "size" => $json->size,
                    "date" => $json->date
                );
                cookie("ban", $json->ban);
                cookie("file", $json->url);
                cookie("date", $json->date);

                if (!is_dir(APP_ROOT . "/updata/" . $json->date)) {
                    mkdir(APP_ROOT . "/updata/" . $json->date);
                }

                $savefile = APP_ROOT . "/updata/" . cookie("date") . "/" . basename(cookie("file"));
                if (is_file($savefile)) {
                    unlink($savefile);
                }
                echo json_encode($res);
            } else {
                $res = array(
                    "result" => 0
                );
                echo json_encode($res);
            }
        } else if (I("post.dopost") == "step2_down") {
            $bag = cookie("file");
            import("@.Class.Httpdownload");
            $dhd = new Httpdownload();
            $downfile = C("UPDATA_URL") . $bag;
            $savefile = APP_ROOT . "/updata/" . cookie("date") . "/" . basename(cookie("file"));

            $dhd->OpenUrl($downfile);
            $dhd->SaveToBin($savefile);
            $dhd->Close();
            echo "1";
        } else if (I("post.dopost") == "step2_clean") {
            $actionfile = APP_ROOT . "/updata/action";
            RemoveDirFiles($actionfile);
            mkdir($actionfile);
            echo "1";
        } else if (I("post.dopost") == "step2_putout") {
            $zipfile = APP_ROOT . "/updata/" . cookie("date") . "/" . basename(cookie("file"));
            if (is_file($zipfile)) {
                $actionfile = APP_ROOT . "/updata/action";
                import("@.Class.Pclzip");
                $archive = new PclZip($zipfile);
                $list = $archive->extract(PCLZIP_OPT_PATH, $actionfile, PCLZIP_OPT_REMOVE_PATH, "");
                echo "1";
            }
        } else if (I("post.dopost") == "step2_readfile") {
            $actionfile = APP_ROOT . "/updata/action";
            $readbag = $actionfile . "/main";

            include_once ($actionfile . "/config.php");

            $filelink = getfile($readbag);
            $filebag = getbag($readbag);
            $filearr = explode("<br/>", $filelink);
            $bagarr = explode("<br/>", $filebag);

            foreach ($bagarr as $key => $value) {
                if ($value != "") {
                    $showv = explode("action/main", $value);
                    $str_len = strlen($showv[1]);
                    if ($str_len > 36) {
                        $str = "..." . substr($showv[1], -36);
                    } else {
                        $str = $showv[1];
                    }
                    echo "$(\"#dirbox\").append('<div mk=\"{$value}\" class=\"dircube\"><b>文件夹：</b>{$str}</div>');";
                }
            }

            foreach ($filearr as $key => $value) {
                if ($value != "") {
                    $showv = explode("action/main", $value);
                    $str_len = strlen($showv[1]);
                    if ($str_len > 36) {
                        $str = "..." . substr($showv[1], -36);
                    } else {
                        $str = $showv[1];
                    }
                    echo "$(\"#filebox\").append('<div mk=\"{$value}\" class=\"filecube\"><b>文件：</b>{$str}</div>');";
                }
            }
            foreach ($File_Del as $key => $value) {
                if ($value != "") {
                    $str_len = strlen($value);
                    if ($str_len > 36) {
                        $str = "..." . substr($value, -36);
                    } else {
                        $str = $value;
                    }
                    echo "$(\"#delbox\").append('<div mk=\"{$value}\" class=\"delcube\"><b>文件：</b>{$str}</div>');";
                }
            }
            echo '$(".step2").show();';
        } else if (I("post.dopost") == "step3_dir") {
            $n = I("post.n");
            $path = I("post.path");
            $patharr = explode("action/main", $path);
            $targetpath = APP_ROOT . $patharr[1];

            if (!is_dir($targetpath)) {
                createFolder($targetpath);
            }
            usleep(150000);
            $n++;
            echo "step3_dir($n);";
        } else if (I("post.dopost") == "step3_file") {

            $n = I("post.n");
            $path = I("post.path");
            $patharr = explode("action/main", $path);
            $targetpath = APP_ROOT . $patharr[1];

            if (is_file($targetpath)) {
                unlink($targetpath);
            }
            copy($path, $targetpath);
            usleep(150000);
            $n++;
            echo "step3_file($n);";
        } else if (I("post.dopost") == "step3_del") {
            $n = I("post.n");
            $path = I("post.path");
            $targetpath = APP_ROOT . $path;

            if (is_file($targetpath)) {
                unlink($targetpath);
            }
            usleep(300000);
            $n++;
            echo "step3_del($n);";
        } else if (I("post.dopost") == "step4_go") {
            $actionfile = APP_ROOT . "/updata/action";
            if (!is_file($actionfile . "/config.php")) {
                echo "1";
                exit();
            }
            include_once ($actionfile . "/config.php");
            if ($ConfigAction['mysql_have_do'] == 1) {
                $cfg_dbhost = C("DB_HOST");
                $cfg_dbuser = C("DB_USER");
                $cfg_dbpwd = C("DB_PWD");
                $cfg_dbname = C("DB_NAME");

                $conn = mysql_connect($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd);
                mysql_select_db($cfg_dbname);
                mysql_query("set names utf8");
                $query = '';
                $sql_file = fopen($actionfile . "/sql.txt", 'r');
                while (!feof($sql_file)) {
                    $line = rtrim(fgets($sql_file, 1024));
                    if (preg_match("#;$#", $line)) {
                        $query .= $line . "\n";
                        $query = str_replace('###_', C("DB_PREFIX"), $query);
                        if ($mysqlVersion < 4.1) {
                            $rs = mysql_query($query, $conn);
                        } else {
                            if (preg_match('#CREATE#i', $query)) {
                                $rs = mysql_query(preg_replace("#TYPE=MyISAM#i", $sql4tmp, $query), $conn);
                            } else {
                                $rs = mysql_query($query, $conn);
                            }
                        }
                        $query = '';
                    } else if (!preg_match("#^(\/\/|--)#", $line)) {
                        $query .= $line;
                    }
                }
                fclose($sql_file);
                echo "1";
            } else {
                echo "1";
            }
        } else if (I("post.dopost") == "step4_ban") {
            $ban = cookie("ban");
            saveBan($ban);
            $url = getUpdataOkLink();
            $json = json_decode(file_get_contents($url));
            echo 1;
        }
    }

}

?>
