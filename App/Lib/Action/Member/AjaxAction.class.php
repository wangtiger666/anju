<?php
class AjaxAction extends MemberAction {

    public function index() {
        if (I("post.dopost") == "editdb") {
            $w0 = trim($_POST['where']);
            $w1 = explode("{", $w0);
            $w2 = explode("}", $w1[1]);
            $w = explode(",", $w2[0]);
            $where = array();
            foreach ($w as $wv) {
                $wh = explode(":", $wv);
                $k = $wh[0];
                $v = ($wh[1] == "me") ? $this->member_id : $wh[1];
                $where[$k] = $v;
            }
            $updata = array();
            $dbkey = $_POST['dbkey'];
            $updata[$dbkey] = $_POST['dbdata'];
            $findrow = M($_POST['db'])->where($where)->find();
            if (!is_array($findrow)) {
                $data = array(
                    "tp" => 2,
                    "txt" => "很遗憾，修改失败！"
                );
                $this->ajaxReturn($data, 'JSON');
                exit();
            } else if ($findrow[$dbkey] == $_POST['dbdata']) {
                $data = array(
                    "tp" => 1,
                    "txt" => "恭喜你，已经修改成功！"
                );
                $this->ajaxReturn($data, 'JSON');
                exit();
            }
            $row = M($_POST['db'])->where($where)->save($updata);
            if ($row == 0) {
                $data = array(
                    "tp" => 0,
                    "txt" => "很遗憾，修改失败！"
                );
                $this->ajaxReturn($data, 'JSON');
            } else {
                $data = array(
                    "tp" => 1,
                    "txt" => "恭喜你，修改成功！"
                );
                $this->ajaxReturn($data, 'JSON');
            }
            exit();
        }
    }

    public function pano() {
        @set_time_limit(0);
        if (I("post.dopost") == "one2six") {
            $photofile = APP_ROOT . I("post.photofile");
            if (is_file($photofile)) {
                $arr = getimagesize($photofile);
                $width = $arr[0];
                $height = $arr[1];
                $imgype = $arr[2];

                $member_id = $this->member_id;
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station";

                if ($imgype == 1) {
                    $newimg = "pano.gif";
                } else if ($imgype == 2) {
                    $newimg = "pano.jpg";
                } else {
                    $newimg = "pano.png";
                }

                if ($width == $height * 6) {
                    RemoveDirFiles($filedir);
                    createFolder($filedir);
                    rename($photofile, $filedir . "/" . $newimg);
                    echo "one2sixdo($imgype,1,0);";
                } else if ($width * 6 == $height) {
                    RemoveDirFiles($filedir);
                    createFolder($filedir);
                    rename($photofile, $filedir . "/" . $newimg);
                    echo "one2sixdo($imgype,1,0);";
                } else {
                    unlink($photofile);
                    echo "showMsg('上传图片不是标准条形图！',2);";
                }
            } else {
                echo "showMsg('文件不存在！',2);";
            }
            exit();
        } else if (I("post.dopost") == "one2sixdo") {
            $imgtype = I('post.imgtype');
            $linetype = I('post.linetype');
            $n = I('post.n');
            $member_id = $this->member_id;

            $cutpano = array(
                "front", "right", "back", "left", "up", "down"
            );

            if ($n < 6) {
                if ($imgtype == 1) {
                    $imgfile = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station/pano.gif";
                    $getimg = @imagecreatefromgif($imgfile);
                } else if ($imgtype == 2) {
                    $imgfile = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station/pano.jpg";
                    $getimg = @imagecreatefromjpeg($imgfile);
                } else {
                    $imgfile = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station/pano.png";
                    $getimg = @imagecreatefrompng($imgfile);
                }

                $arr = getimagesize($imgfile);
                $width = $arr[0];
                $height = $arr[1];

                if ($linetype == 1) {
                    $targetimg = @imagecreatetruecolor($height, $height);
                    $dex = $height * $n;
                    imagecopy($targetimg, $getimg, 0, 0, $dex, 0, $height, $height);
                } else {
                    $targetimg = @imagecreatetruecolor($width, $width);
                    $dex = $width * $n;
                    imagecopy($targetimg, $getimg, 0, 0, 0, $dex, $width, $width);
                }

                $temp = $cutpano[$n];
                $outfile = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station/pano_{$temp}.jpg";
                imagejpeg($targetimg, $outfile, 100);

                $weburl = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station/pano_{$temp}.jpg?time=".time();
                $saveurl = "/uploads/user/" . substr(md5($member_id), 5, 15) . "/station/pano_{$temp}.jpg";

                $n++;
                $wo = 100 * $n / 6;
                echo "$(\"#{$temp}\").val('{$saveurl}');";
                echo "$(\"#{$temp}_pic\").html('');";
                echo "$(\"#{$temp}_pic\").html('<img src=\"{$weburl}\" onload=\"photocenterout(this,100,100);loadbar();\" />');";
                echo "one2sixdo($imgtype,$linetype,$n);";
                exit();
            } else {

            }
        } else if (I("post.dopost") == "ball2six") {
            $photofile = APP_ROOT . I("post.photofile");
            if (is_file($photofile)) {
                $actiondir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
                $balldir = APP_ROOT . "/Public/ball";
                RemoveDirFiles($actiondir);
                createFolder($actiondir);
                copyFolder($balldir, $actiondir);
                $bs = explode(".", basename($photofile));
                $file = $actiondir . "/pano." . $bs[1];
                rename($photofile, $file);
                if (exec($actiondir . '/sphere2cube.bat ' . $file, $output)) {
                    echo "ball2sixdo(0);";
                }
            } else {
                echo "showMsg('文件不存在!',2);";
            }
        } else if (I("post.dopost") == "ball2sixdo") {
            $actiondir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            $webdir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            $n = I('post.n');
            $member_id = $this->member_id;

            if ($n < 6) {
                $cutpano = array(
                    "front", "right", "back", "left", "up", "down"
                );
                $temp = $this->pano[$n];
                $saveurl = $webdir . "/pano_{$this->mbpano[$n]}.jpg";
                $weburl = CC("web_root") . $webdir . "/pano_{$this->mbpano[$n]}.jpg";

                $n++;
                echo "$(\"#{$temp}\").val('{$saveurl}');";
                echo "$(\"#{$temp}_pic\").html('');";
                echo "$(\"#{$temp}_pic\").html('<img src=\"{$weburl}\" onload=\"photocenterout(this,100,100);loadbar();\" />');";
                echo "ball2sixdo($n);";
                exit();
            } else {

            }
        } else if (I("post.dopost") == "creatpreview") {
            set_time_limit(0);
            $view_id = I("post.view_id");
            $n = I("post.n");

            $this->assign("view_id", $view_id);
            $viewwhere = array(
                "id" => $view_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();

            $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
            if ($os == "linux") {
                if ($n < 6) {
                    $thefile = APP_ROOT . $viewrow[$this->pano[$n]];
                    $targetfile = str_replace(basename($thefile), "mb_" . $this->mbpano[$n] . ".jpg", $thefile);
                    if (is_file($targetfile)) {
                        unlink($targetfile);
                    }
                    CopyImage($thefile, $targetfile, 400, 400, 100);
                    $n++;
                    echo "startDo($n);";
                } else if ($n == 6) {
                    $thefile = APP_ROOT . $viewrow[$this->pano[0]];
                    $onefile = str_replace(basename($thefile), "mb_" . $this->mbpano[0] . ".jpg", $thefile);
                    $targetfile = str_replace(basename($thefile), "preview.jpg", $thefile);
                    $imgdir = str_replace(basename($thefile), "", $thefile);
                    if (is_file($targetfile)) {
                        unlink($targetfile);
                    }

                    $arr = getimagesize($onefile);
                    $boxsize = $arr[0];
                    $dst_im = imagecreatetruecolor(400, 2400);

                    $src_l = @imagecreatefromjpeg($imgdir . "mb_l.jpg");
                    imagecopyresized($dst_im, $src_l, 0, 0, 0, 0, 400, 400, $boxsize, $boxsize);
                    $src_f = @imagecreatefromjpeg($imgdir . "mb_f.jpg");
                    imagecopyresized($dst_im, $src_f, 0, 400, 0, 0, 400, 400, $boxsize, $boxsize);
                    $src_r = @imagecreatefromjpeg($imgdir . "mb_r.jpg");
                    imagecopyresized($dst_im, $src_r, 0, 800, 0, 0, 400, 400, $boxsize, $boxsize);
                    $src_b = @imagecreatefromjpeg($imgdir . "mb_b.jpg");
                    imagecopyresized($dst_im, $src_b, 0, 1200, 0, 0, 400, 400, $boxsize, $boxsize);
                    $src_u = @imagecreatefromjpeg($imgdir . "mb_u.jpg");
                    imagecopyresized($dst_im, $src_u, 0, 1600, 0, 0, 400, 400, $boxsize, $boxsize);
                    $src_d = @imagecreatefromjpeg($imgdir . "mb_d.jpg");
                    imagecopyresized($dst_im, $src_d, 0, 2000, 0, 0, 400, 400, $boxsize, $boxsize);

                    imagejpeg($dst_im, $targetfile, 100);
                    $n++;
                    echo "startDo($n);";
                } else if ($n == 7) {
                    for ($i = 0; $i < 6; $i++) {
                        $thefile = APP_ROOT . $viewrow[$this->pano[$i]];
                        $targetfile = str_replace(basename($thefile), "mb_" . $this->mbpano[$i] . ".jpg", $thefile);
                    }
                    $n++;
                    echo "startDo($n);";
                } else {
                    echo "finishDo();";
                }
            } else {

                $actiondir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/cutstation/";
                $actionweb = CC("web_root") . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/cutstation";
                $cutdir = APP_ROOT . "/Public/cut";


                //2017.8.20  小唐  浩哥源码 start  全景视频 跳转 start
                if ($viewrow["openvideo"] == 1){
                    $n=8;
                }
                //2017.8.20  小唐  浩哥源码 start  全景视频 跳转 end
                //2018.3.1  小唐  浩哥源码 start  全景720环物 跳转 start
                if ($viewrow["open720ring"] == 1){
                    $n=8;
                }
                //2017.3.1  小唐  浩哥源码 start  全景720环物 跳转 start

                if ($n == 0) {
                    //RemoveDirFiles($actiondir);
                    createFolder($actiondir);
                    copyFolder($cutdir, $actiondir);

                    $n++;
                    echo "startDo($n);";
                    exit();
                } else if ($n == 1) {
                    for ($i = 0; $i < 6; $i++) {
                        copy(APP_ROOT . $viewrow[$this->pano[$i]], $actiondir . "/pano_" . $this->mbpano[$i] . ".jpg");
                    }
                    $n++;
                    echo "startDo($n);";
                    exit();
                } else if ($n == 2) {
                    $file = "";
                    $file .= "' {$actiondir}/pano_u.jpg'";
                    $file .= " '{$actiondir}/pano_d.jpg'";
                    $file .= " '{$actiondir}/pano_l.jpg'";
                    $file .= " '{$actiondir}/pano_r.jpg'";
                    $file .= " '{$actiondir}/pano_b.jpg'";
                    $file .= " '{$actiondir}/pano_f.jpg'";
                    $n++;

                    $filedir = $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/view";
                    $url = $actionweb . "/do.php?filedir=".$filedir;
                    //redirect($url);
                    echo "create_screen('http://".$_SERVER['HTTP_HOST'].$url."');";
                    sleep("10");
                    echo "startDo($n);";
                    exit();
                } else if ($n == 3) {
                    /*
                    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/view";
                    $prew = $filedir . "/preview.jpg";
                    if (is_file($prew)) {
                        unlink($prew);
                    }
                    copy($actiondir . "/scene/preview.jpg", $filedir . "/preview.jpg");
                    */
                    $n++;
                    echo "startDo($n);";
                    exit();
                } else if ($n == 4) {
                    clearPian($actiondir);
                    $n++;
                    echo "startDo($n);";
                    exit();
                } else if ($n == 5) {
                    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/scene";
                    RemoveDirFiles($filedir);

                    sleep("30");
                    $n++;
                    echo "startDo($n);";
                    exit();
                } else if ($n == 6) {


                    $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/scene";
                    //copyFolder($actiondir . "/scene", $filedir);
                    rename($actiondir . "/scene", $filedir);
                    $n++;
                    echo "startDo($n);";
                    exit();
                } else if ($n == 7) {

                    sleep("10");
                    RemoveDirFiles($actiondir);
                    $n++;
                    echo "startDo($n);";
                    exit();
                } else {
                    echo "finishDo();";
                    exit("结束");
                }
            }
        }
    }

    function view() {
        $dopost = I('post.dopost');

        if ($dopost == "changeview") {
            $a = I('post.va');
            $b = I('post.vb');
            $view_a = D("Panoview")->GetOne($a, $this->member_id);
            $view_b = D("Panoview")->GetOne($b, $this->member_id);
            if (is_array($view_a) && is_array($view_b)) {
                $sort_a = $view_a['sort'];
                $sort_b = $view_b['sort'];
                $newsort_a = $sort_b;
                $newsort_b = $sort_a;
                $a_where = array(
                    "member_id" => $this->member_id,
                    "id" => $a
                );
                $b_where = array(
                    "member_id" => $this->member_id,
                    "id" => $b
                );
                $a_data = array(
                    "sort" => $newsort_a
                );
                $b_data = array(
                    "sort" => $newsort_b
                );
                $ra = M("Pano_view")->where($a_where)->save($a_data);
                $rb = M("Pano_view")->where($b_where)->save($b_data);
                if ($ra == 1 && $rb == 1) {
                    echo "changehtml();";
                } else {
                    echo "showMsg('交换场景失败！',0);";
                }
            } else {
                echo "showMsg('交换场景失败！',0);";
            }
        } else if ($dopost == "firstview") {
            $view_id = I("post.theid");
            $pano_id = I("post.pano_id");
            $member_id = get_member_id();

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
                "id" => $view_id
            );
            $data2 = array(
                "first_read" => 1
            );
            $k = M("Pano_view")->where($where2)->save($data2);
            if ($k > 0) {
                echo "showMsg('初始场景设置成功！',1);";
                echo "$('.pano_cube').removeClass('me');";
                echo "$('#view_{$view_id}').addClass('me');";
            }
        }
    }

    function cube() {
        $dopost = I('post.dopost');

        if ($dopost == "cube") {
            $picdata = I('post.picdata');
            $picarr = explode("|", $picdata);
            $len = count($picarr);

            for ($i = 0; $i < $len; $i++) {
                $photofile = APP_ROOT . $picarr[$i];
                if ($i == 0) {
                    $arr = getimagesize($photofile);
                    $width = $arr[0];
                    $height = $arr[1];
                    $type = $arr[2];
                    $ow = $width;
                    $oh = $height;
                    $ot = $type;
                } else {
                    $arr = getimagesize($photofile);
                    $width = $arr[0];
                    $height = $arr[1];
                    $type = $arr[2];
                    if ($width != $ow || $height != $oh) {
                        echo "showMsg('您的图片尺寸大小不相同！',0);";
                        exit();
                    }
                    if ($type != $ot) {
                        echo "showMsg('您的图片格式不相同！',0);";
                        exit();
                    }
                }
            }

            $pano_id = I('post.pano_id');

            $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/cube/cube" . time();
            createFolder(APP_ROOT . $file);

            $data = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "title" => "暂无名称",
                "file" => $file,
                "width" => $ow,
                "height" => $oh,
                "imagetype" => $ot,
                "len" => $len
            );

            $backid = M("Pano_cube_store")->add($data);
            if ($backid) {
                echo "boluo_showloadbar('正在生成360物体');cubestart('$picdata',$backid,'$file',$ot,0);";
            }
            exit();
        } else if ($dopost == "cubedo") {
            $picdata = I('post.picdata');
            $picarr = explode("|", $picdata);
            $len = count($picarr);

            $cube_id = I('post.cube_id');
            $now = I('post.now');
            $dir = I('post.dir');
            $imgtype = I('post.tp');

            $nowfile = APP_ROOT . $picarr[$now];
            if ($imgtype == 1) {
                $tp = "gif";
            } else if ($imgtype == 2) {
                $tp = "jpg";
            } else {
                $tp = "png";
            }

            $myname = "cube" . $now . "." . $tp;
            rename($nowfile, APP_ROOT . $dir . "/" . $myname);
            $now++;
            if ($now < $len) {
                echo "cubestart('$picdata',$cube_id,'$dir',$imgtype,$now);boluo_loadbar($now,$len);";
                exit();
            } else {
                echo "boluo_loadbar($len,$len);cubecreat($cube_id);";
                exit();
            }
        }
    }

    function ui() {
        if (I("post.dopost") == "flv") {
            $pano_id = I('post.pano_id');
            $flvurl = I('post.flvurl');
            $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/ui";
            createFolder(APP_ROOT . $file);
            if (is_file(APP_ROOT . $flvurl)) {
                $filename = basename($flvurl);
                $filepath = $file . "/" . $filename;
                rename(APP_ROOT . $flvurl, APP_ROOT . $filepath);
                cookie("flvurl", $filepath);
                echo "var backfile = '{$filepath}';";
            }
        } elseif (I("post.dopost") == "image") {
            $pano_id = I('post.pano_id');
            $imgurl = I('post.imgurl');
            $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/ui";
            createFolder(APP_ROOT . $file);
            if (is_file(APP_ROOT . $imgurl)) {
                $filename = basename($imgurl);
                $filepath = $file . "/" . $filename;
                rename(APP_ROOT . $imgurl, APP_ROOT . $filepath);
                echo "var backfile = '{$filepath}';";
            }
        }
    }

    function map() {
        if (I("post.dopost") == "map") {
            $pano_id = I('post.pano_id');
            $imgurl = I('post.imgurl');
            $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/map";
            createFolder(APP_ROOT . $file);
            if (is_file(APP_ROOT . $imgurl)) {
                $filename = basename($imgurl);
                $filepath = $file . "/" . $filename;
                rename(APP_ROOT . $imgurl, APP_ROOT . $filepath);
                cookie("mapurl", $filepath);
                echo "var backfile = '{$filepath}';";
            }
        } elseif (I("post.dopost") == "node") {
            $pano_id = I('post.pano_id');
            $imgurl = I('post.imgurl');
            $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/map/node";
            createFolder(APP_ROOT . $file);
            if (is_file(APP_ROOT . $imgurl)) {
                $filename = basename($imgurl);
                $filepath = $file . "/" . $filename;
                rename(APP_ROOT . $imgurl, APP_ROOT . $filepath);
                echo "var backfile = '{$filepath}';";
            }
        }
    }

    function luopan() {
        $view_id = I("post.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $member_id = $this->member_id;
        $cube2ball = APP_ROOT . "/Public/cube2ball";
        $filedir = APP_ROOT . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/{$viewrow["pano_id"]}/station";
        $webdir = CC("web_root") . "/uploads/user/" . substr(md5($member_id), 5, 15) . "/{$viewrow["pano_id"]}/station";

        $dopost = I("post.dopost");
        if ($dopost == "start") {
            RemoveDirFiles($filedir);
            createFolder($filedir);
            copyFolder($cube2ball, $filedir);
            for ($i = 0; $i < 6; $i++) {
                copy(APP_ROOT . $viewrow[$this->pano[$i]], $filedir . "/pano_" . $this->mbpano[$i] . ".jpg");
            }
            $link = $webdir . "/do.php";
            redirect($link);
            exit();
        } else if ($dopost == "luopan") {
            import("@.Class.BoluoGD");
            $filename = $filedir . "/pano_sphere.jpg";
            $outfile = APP_ROOT . "/uploads/station/luopan" . time() . ".png";
            $bakfile = CC("web_root")."/uploads/station/luopan" . time() . ".png";
            $BG = new BoluoGD();
            $BG->loadImg($filename);
            $BG->polarImg(400);
            $BG->save($outfile);
            RemoveDirFiles($filedir);
            echo "backluopan('$bakfile');";
            exit();
        }
    }

}

?>