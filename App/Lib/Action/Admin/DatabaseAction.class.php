<?php

class DatabaseAction extends AdminAction {

    public function index() {
        import("@.Class.mysql");

        $mydb = new MySql();
        //获取系统存在的表信息
        $SysTables = Array();
        $otherTables = Array();
        $mydb->SetQuery("SHOW TABLES");
        $mydb->Execute('t');

        $cfg_dbprefix = C("DB_PREFIX");

        while ($row = $mydb->GetArray('t', MYSQL_BOTH)) {
            if (preg_match("#^{$cfg_dbprefix}#", $row[0])) {
                $SysTables[] = $row[0];
            } else {
                $otherTables[] = $row[0];
            }
        }

        $mysql_version = $mydb->GetVersion();

        $syshtml = '';
        $otherhtml = '';

        for ($i = 0; isset($SysTables[$i]); $i++) {
            $t = $SysTables[$i];
            if ($i % 2 == 0) {
                $syshtml .= '<tr align="center"  bgcolor="#FFFFFF" height="24">';
            } else {
                $syshtml .= '';
            }
            $syshtml .= '<td><input type="checkbox" name="tables" value="' . $t . '" class="np" checked /></td>';
            $syshtml .= '<td>' . $t . '</td>';
            $syshtml .= '<td>' . TjCount($t, $mydb) . '</td>';
            $syshtml .= '<td></td>';
            if ($i % 2 == 0) {
                $syshtml .= '';
            } else {
                $syshtml .= '</tr>';
            }
        }
        if ($i % 2 == 0) {
            $syshtml .= '';
        } else {
            $syshtml .= '<td></td>';
            $syshtml .= '<td></td>';
            $syshtml .= '<td></td>';
            $syshtml .= '<td></td>';
            $syshtml .= '</tr>';
        }

        for ($i = 0; isset($otherTables[$i]); $i++) {
            $t = $otherTables[$i];
            if ($i % 2 == 0) {
                $otherhtml .= '<tr align="center"  bgcolor="#FFFFFF" height="24">';
            } else {
                $otherhtml .= '';
            }
            $otherhtml .= '<td><input type="checkbox" name="tables" value="' . $t . '" class="np" /></td>';
            $otherhtml .= '<td>' . $t . '</td>';
            $otherhtml .= '<td>' . TjCount($t, $mydb) . '</td>';
            $otherhtml .= '<td></td>';
            if ($i % 2 == 0) {
                $otherhtml .= '';
            } else {
                $otherhtml .= '</tr>';
            }
        }
        if ($i % 2 == 0) {
            $otherhtml .= '';
        } else {
            $otherhtml .= '<td></td>';
            $otherhtml .= '<td></td>';
            $otherhtml .= '<td></td>';
            $otherhtml .= '<td></td>';
            $otherhtml .= '</tr>';
        }

        $this->assign("mysql_version", $mysql_version);
        $this->assign("syshtml", $syshtml);
        $this->assign("otherhtml", $otherhtml);
        $this->display();
    }

    function savedata() {
        $bkdir = APP_ROOT . '/databack/last';
        if (I("post.dopost") == "start") {
            $dbt = I("post.dbt");
            $dbnames = I("post.db");
            if ($dbnames == "") {
                echo "showMsg('没有选择数据表！',2);";
                exit();
            }
            if (!is_dir($bkdir)) {
                createFolder($bkdir);
            }

            $dh = dir($bkdir);
            while ($filename = $dh->read()) {
                if (!preg_match("#txt$#", $filename)) {
                    continue;
                }
                $filename = $bkdir . "/$filename";
                if (!is_dir($filename)) {
                    unlink($filename);
                }
            }
            $dh->close();
            echo "basetb();";
        } else if (I("post.dopost") == "tb") {
            $dbt = I("post.dbt");
            $dbnames = I("post.db");
            $tables = explode(',', $dbnames);

            import("@.Class.mysql");
            $mydb = new MySql();

            $bkfile = $bkdir . "/tables_struct_" . substr(md5(time() . mt_rand(1000, 5000)), 0, 16) . ".txt";
            $mysql_version = $mydb->GetVersion();
            $fp = fopen($bkfile, "w");

            foreach ($tables as $t) {
                fwrite($fp, "DROP TABLE IF EXISTS `$t`;\r\n\r\n");
                $mydb->SetQuery("SHOW CREATE TABLE " . $mydb->dbName . "." . $t);
                $mydb->Execute('me');
                $row = $mydb->GetArray('me', MYSQL_BOTH);

                //去除AUTO_INCREMENT
                $row[1] = preg_replace("#AUTO_INCREMENT=([0-9]{1,})[ \r\n\t]{1,}#i", "", $row[1]);

                //4.1以下版本备份为低版本
                if ($datatype == 4.0 && $mysql_version > 4.0) {
                    $eng1 = "#ENGINE=MyISAM[ \r\n\t]{1,}DEFAULT[ \r\n\t]{1,}CHARSET=utf8#i";
                    $tableStruct = preg_replace($eng1, "TYPE=MyISAM", $row[1]);
                }

                //4.1以下版本备份为高版本
                else if ($datatype == 4.1 && $mysql_version < 4.1) {
                    $eng1 = "#ENGINE=MyISAM DEFAULT CHARSET=utf8#i";
                    $tableStruct = preg_replace("TYPE=MyISAM", $eng1, $row[1]);
                }
                //普通备份
                else {
                    $tableStruct = $row[1];
                }
                fwrite($fp, '' . $tableStruct . ";\r\n\r\n");
            }
            fclose($fp);
            echo "basedb(0);";
            exit();
        } else if (I("post.dopost") == "db") {
            $dbt = I("post.dbt");
            $max = I("post.max");
            $nowid = I("post.now");
            $dbnames = I("post.db");
            $tables = explode(',', $dbnames);
            $nowtable = $tables[$nowid];

            import("@.Class.mysql");
            $mydb = new MySql();

            $j = 0;
            $fs = $bakStr = '';
            $bakStr = "TRUNCATE TABLE  `$nowtable`;\r\n";
            //分析表里的字段信息
            $mydb->GetTableFields($nowtable);
            $intable = "INSERT INTO `$nowtable` (";
            while ($r = $mydb->GetFieldObject()) {
                $fs[$j] = trim($r->name);
                if ($j > 0) {
                    $intable .= ",";
                }
                $intable .= "`" . trim($r->name) . "`";
                $j++;
            }
            $intable .= ") VALUES(";
            $fsd = $j - 1;

            //读取表的内容
            $mydb->SetQuery("SELECT * FROM `$nowtable` ");
            $mydb->Execute();
            $m = 0;

            $bakfilename = "$bkdir/{$nowtable}_" . substr(md5(time() . mt_rand(1000, 5000)), 0, 16) . ".txt";

            while ($row2 = $mydb->GetArray()) {
                $line = $intable;
                for ($j = 0; $j <= $fsd; $j++) {
                    if ($j < $fsd) {
                        $line .= "'" . RpLine(addslashes($row2[$fs[$j]])) . "',";
                    } else {
                        $line .= "'" . RpLine(addslashes($row2[$fs[$j]])) . "');\r\n";
                    }
                }
                $bakStr .= $line;
            }

            if ($bakStr != '') {
                $fp = fopen($bakfilename, "w");
                fwrite($fp, $bakStr);
                fclose($fp);
            }
            $nowid++;
            $ps = 100 * $nowid / $max;
            echo "$(\"#showbox\").html(\"成功备份数据表 <b>$nowtable</b>！完成度：{$ps}%\");";
            if ($nowid < $max) {
                echo "basedb($nowid);";
            } else {
                $newname = APP_ROOT . '/databack/' . time();
                rename($bkdir, $newname);
                echo "$(\"#showbox\").html(\"数据库备份完成！完成度：{$ps}%\");";
            }
        }
    }

    function bak() {
        $bkbgdir = APP_ROOT . '/databack';

        $dh = dir($bkbgdir);
        $mybg = "";
        while ($filename = $dh->read()) {
            if (!($filename == ".." || $filename == ".")) {
                if (is_dir($bkbgdir . "/" . $filename)) {
                    if ($mybg != "") {
                        $mybg = "|" . $mybg;
                    }
                    $mybg = $filename . $mybg;
                }
            }
        }
        $dh->close();
		if(!empty($mybg)){
			$bag = explode("|", $mybg);
			$this->assign('bag', $bag);
		}
        $this->display();
    }

    function baklist() {
        $dir = I("get.dir");
        if ($dir != "") {
            $bkdir = APP_ROOT . '/databack/' . $dir;

            $filelists = Array();
            $dh = dir($bkdir);
            $structfile = "没找到数据结构文件";

            while (($filename = $dh->read()) !== false) {
                if (!preg_match("#txt$#", $filename)) {
                    continue;
                }
                if (preg_match("#tables_struct#", $filename)) {
                    $structfile = $filename;
                } else if (filesize("$bkdir/$filename") > 0) {
                    $filelists[] = $filename;
                }
            }
            $dh->close();

            $backhtml = '';
            for ($i = 0; $i < count($filelists); $i++) {
                if ($i % 2 == 0) {
                    $backhtml .= '<tr class="tr_white" align="center" height="24">';
                } else {
                    $backhtml .= '';
                }

                $backhtml .= '<td width="10%"><input type="checkbox" name="bakfile" id="bakfile" value="' . $filelists[$i] . '" /></td>';
                $backhtml .= '<td width="40%">' . $filelists[$i] . '</td>';

                if ($i % 2 == 0) {
                    $backhtml .= '';
                } else {
                    $backhtml .= '</tr>';
                }
            }
            if ($i % 2 == 0) {
                $backhtml .= '';
            } else {
                $backhtml .= '<td></td>';
                $backhtml .= '<td></td>';
                $backhtml .= '</tr>';
            }

            $this->assign('dir', $dir);
            $this->assign('structfile', $structfile);
            $this->assign('backhtml', $backhtml);
            $this->display();
        }
    }

    function backdata() {
        if (I("post.dopost") == "tb") {
            $dir = I('post.dir');
            $db = I('post.db');
            $bkfile = APP_ROOT . '/databack/' . $dir . "/" . $db;
            $tbdata = "";
            $fp = fopen($bkfile, 'r');
            while (!feof($fp)) {
                $tbdata .= fgets($fp, 1024);
            }
            fclose($fp);

            import("@.Class.mysql");
            $mydb = new MySql();

            $querys = explode(';', $tbdata);
            foreach ($querys as $q) {
                $mydb->DoNotBack(trim($q) . ';');
            }
            echo "$(\"#showbox\").html(\"成功还原数据表结构！\");";
            echo "backdb(0);";
        } else if (I("post.dopost") == "db") {
            import("@.Class.mysql");
            $mydb = new MySql();
            
            $dir = I('post.dir');
            $db_str = I('post.db');

            $max = I('post.max');
            $nowid = I('post.now');

            $db_arr = explode(",", $db_str);
            $db = $db_arr[$nowid];

            $nowfile = APP_ROOT . '/databack/' . $dir . "/" . $db;

            $oknum = 0;
            if (filesize($nowfile) > 0) {
                $fp = fopen($nowfile, 'r');
                while (!feof($fp)) {
                    $line = trim(fgets($fp, 512 * 1024));
                    if ($line == "") {
                        continue;
                    }
                    $rs = $mydb->DoNotBack($line);
                    if ($rs) {
                        $oknum++;
                    }
                }
                fclose($fp);
            }
            $nowid++;
            $ps = $nowid/$max * 100;
            echo "$(\"#showbox\").html(\"成功还原了{$db}共{$oknum}条数据！{$ps}%\");";            
            if($nowid<$max){
                echo "backdb($nowid);";
            }else{
                echo "$(\"#showbox\").html(\"还原数据完成！\");";
            }            
        }
    }

}