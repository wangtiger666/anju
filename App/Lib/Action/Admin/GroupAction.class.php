<?php

class GroupAction extends AdminAction {

    function index() {
        cookie("back", __SELF__);
        $User = M('Admin_group'); // 实例化User对象
        import('ORG.Util.Page'); // 导入分页类
        $count = $User->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    function add() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);
        if ($_POST['dopost'] == "save") {
            $groupname = trim($_POST['groupname']);
            if ($groupname == "") {
                $this->error("VIP分组名称不可为空！");
                exit();
            }

            $rankid = $_POST['rankid'];
            if ($rankid == "") {
                //$this->error("级别值不可为空！");
                //exit();
            } else if (!ereg("^[0-9]+", $rankid)) {
                $this->error("级别值必须为数字！");
                exit();
            } else {
                /*$numrow = M("Admin_group")->where("id=$rankid")->find();
                if (is_array($numrow)) {
                    $this->error("级别值已经被占用！");
                    exit();
                }*/
            }

            if ($_POST['allpow'] == 0) {
                $tags = explode("*", $_POST['pow_k']);
                $pow = "";
                foreach ($tags as $tag) {
                    if ($_POST[$tag] == 1) {
                        if ($pow != "") {
                            $pow .= ",";
                        }
                        $pow .= $tag;
                    }
                }
            } else {
                $pow = "All_power";
            }

            $map = array(
                //"id" => $rankid,
                "groupname" => $groupname,
                "grouppower" => $pow,
                "groupinfo" => $_POST['groupinfo'],
                "creat_time" => time(),
                "updata_time" => time()
            );
            M("Admin_group")->add($map);
            $this->redirect("admin/group/index");
            exit();
        }

        $haverow = M("Admin_group")->select();
        if (is_array($haverow)) {
            $i = 0;
            foreach ($haverow as $have) {
                if ($i > 0) {
                    $have_num .= '、';
                }
                $have_num .= '<font color="red">' . $have['id'] . '</font>';
                $i++;
            }
        } else {
            $have_num = "无";
        }
        $this->assign("have_num", "$have_num");

        //获取所有定义的权限
        $start = 0;
        $k = 0;
        $head = "";
        $body = "";
        $pow_k = "";
        $power = file(APP_PATH . 'Lib/Inc/power.txt');
        foreach ($power as $line) {
            $line = trim($line);
            if ($line == "")
                continue;
            if (preg_match("#^>>#", $line)) {
                if ($start > 0) {
                    $body .= "</div>";
                }
                $start++;
                $headname = str_replace('>>', '', $line);
                if ($start == 1) {
                    $head .= "<div class=\"cube me\">$headname</div>\r\n";
                    $body .= "<div class=\"cube me\">\r\n";
                } else {
                    $head .= "<div class=\"cube\">$headname</div>\r\n";
                    $body .= "<div class=\"cube\">\r\n";
                }
            } else if (preg_match("#^>#", $line)) {
                $ls = explode('>', $line);
                $tag = $ls[1];
                $ps = explode("&&", $ls[2]);

                if ($pow_k != "") {
                    $pow_k .= "*";
                }
                $pow_k .= $tag;
                $body .= "<div class=\"toolline\">\r\n";
                $body .= "<div class=\"toolname\">{$ps[0]}：</div>\r\n";
                $body .= "<div class=\"tooloff\">\r\n";
                $body .= "<span class=\"onoff\" target=\"$tag\" value=\"0\"></span>";
                $body .= "<input type=\"hidden\" id=\"$tag\" name=\"$tag\" value=\"0\" />\r\n";
                $body .= "</div>\r\n";
                $body .= "<div class=\"toolinfo\">{$ps[1]}</div>\r\n";
                $body .= "</div>\r\n";

                $k++;
            }
        }

        $this->assign("pow_k", $pow_k);
        $this->assign("head", $head);
        $this->assign("body", $body);
        $this->display();
    }

    function edit() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if ($_POST['dopost'] == "save") {
            $groupname = trim($_POST['groupname']);
            if ($groupname == "") {
                $this->error("VIP分组名称不可为空！");
                exit();
            }

            $id = $_POST['id'];

            if ($_POST['allpow'] == 0) {
                $tags = explode("*", $_POST['pow_k']);
                $pow = "";
                foreach ($tags as $tag) {
                    if ($_POST[$tag] == 1) {
                        if ($pow != "") {
                            $pow .= ",";
                        }
                        $pow .= $tag;
                    }
                }
            } else {
                $pow = "All_power";
            }

            $map = array(
                "groupname" => $groupname,
                "grouppower" => $pow,
                "groupinfo" => $_POST['groupinfo'],
                "updata_time" => time()
            );
            M("Admin_group")->where("id=$id")->save($map);
            $this->redirect("admin/group/index");
            exit();
        }

        if ($_GET['id'] != "") {
            $row = M("Admin_group")->where("id=" . $_GET['id'])->find();
            $this->assign("row", $row);

            $mypow = $row['grouppower'];
            if (TestGroupPower("All_power", $mypow)) {
                $allpower = 1;
            } else {
                $allpower = 0;
            }
            $this->assign("allpower", $allpower);

            $this->assign("id", $_GET['id']);

            //获取所有定义的权限
            $start = 0;
            $k = 0;
            $head = "";
            $body = "";
            $pow_k = "";
            $power = file(APP_PATH . 'Lib/Inc/power.txt');
            foreach ($power as $line) {
                $line = trim($line);
                if ($line == "")
                    continue;
                if (preg_match("#^>>#", $line)) {
                    if ($start > 0) {
                        $body .= "</div>";
                    }
                    $start++;
                    $headname = str_replace('>>', '', $line);
                    if ($start == 1) {
                        $head .= "<div class=\"cube me\">$headname</div>\r\n";
                        $body .= "<div class=\"cube me\">\r\n";
                    } else {
                        $head .= "<div class=\"cube\">$headname</div>\r\n";
                        $body .= "<div class=\"cube\">\r\n";
                    }
                } else if (preg_match("#^>#", $line)) {
                    $ls = explode('>', $line);
                    $tag = $ls[1];
                    $ps = explode("&&", $ls[2]);

                    if ($pow_k != "") {
                        $pow_k .= "*";
                    }
                    $pow_k .= $tag;

                    $body .= "<div class=\"toolline\">\r\n";
                    $body .= "<div class=\"toolname\">{$ps[0]}：</div>\r\n";
                    $body .= "<div class=\"tooloff\">\r\n";
                    if (!TestGroupPower($tag, $mypow)) {
                        $body .= "<span class=\"onoff\" target=\"$tag\" value=\"0\"></span>";
                        $body .= "<input type=\"hidden\" id=\"$tag\" name=\"$tag\" value=\"0\" />\r\n";
                    } else {
                        $body .= "<span class=\"onoff\" target=\"$tag\" value=\"1\"></span>";
                        $body .= "<input type=\"hidden\" id=\"$tag\" name=\"$tag\" value=\"1\" />\r\n";
                    }
                    $body .= "</div>\r\n";
                    $body .= "<div class=\"toolinfo\">{$ps[1]}</div>\r\n";
                    $body .= "</div>\r\n";

                    $k++;
                }
            }

            $this->assign("pow_k", $pow_k);
            $this->assign("head", $head);
            $this->assign("body", $body);
            $this->display();
        }
    }

    function del() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);
        if ($_GET['id'] != "") {
            M("Admin_group")->where("id=" . $_GET['id'])->delete();
        }
    }

}

?>
