<?php

class ToolmapAction extends MemberAction {

    public function index() {
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");

            $open = I('post.open');
            $mapimg = I('post.mapimg');

            if (is_file(APP_ROOT . $mapimg)) {
                if (substr_count($mapimg, "station") > 0) {
                    $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
                    $mapdir = $panodir . "/toolbox/map";
                    if (!is_dir(APP_ROOT . $mapdir)) {
                        createFolder(APP_ROOT . $mapdir);
                    }
                    $newimg = $mapdir . "/" . basename($mapimg);
                    rename(APP_ROOT . $mapimg, APP_ROOT . $newimg);
                    $mapimg = $newimg;
                    $mapwhere = array(
                        "member_id" => $this->member_id,
                        "pano_id" => $pano_id
                    );
                    $gdata = array(
                        "map_x" =>0,
                        "map_y" =>0
                    );
                    M("Pano_toolbox_map_point")->where($mapwhere)->save($gdata);
                }
            } else {
                $open = 0;
            }

            $mapwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id
            );
            $data = array(
                "open" => $open,
                "mapimg" => $mapimg
            );
            M("Pano_toolbox_map")->where($mapwhere)->save($data);
            $this->redirect("index", array("pano_id" => $pano_id));
            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        if (!is_array($maprow)) {
            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/toolbox/map";
            createFolder(APP_ROOT . $mapdir);

            $pointimg = "/Public/member/images/pano/mappoint.png";
            $thepointimg = "/Public/member/images/pano/mappointactive.png";
            $newimg = $mapdir . "/" . basename($thepointimg);
            copy(APP_ROOT . $thepointimg, APP_ROOT . $newimg);
            $thepointimg = $newimg;
            $newimg = $mapdir . "/" . basename($pointimg);
            copy(APP_ROOT . $pointimg, APP_ROOT . $newimg);
            $pointimg = $newimg;

            $mapbtnimg = "/Public/member/images/pano/mapbtn.png";
            $mapbtnoffimg = "/Public/member/images/pano/mapbtnoff.png";
            $newimg = $mapdir . "/" . basename($mapbtnimg);
            copy(APP_ROOT . $mapbtnimg, APP_ROOT . $newimg);
            $mapbtnimg = $newimg;
            $newimg = $mapdir . "/" . basename($mapbtnoffimg);
            copy(APP_ROOT . $mapbtnoffimg, APP_ROOT . $newimg);
            $mapbtnoffimg = $newimg;

            $addsql = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                'thepointimg' => $thepointimg,
                'pointimg' => $pointimg,
                "openmapimg" => $mapbtnimg,
                "hidemapimg" => $mapbtnoffimg
            );

            M("Pano_toolbox_map")->add($addsql);
            $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        }
        $this->assign("maprow", $maprow);

        if (is_file(APP_ROOT . $maprow['mapimg'])) {
            $this->maphave = 1;
        } else {
            $this->maphave = 0;
        }

        $this->display();
    }

    function point() {
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
            $thepointimg = I('post.thepointimg');
            $pointimg = I('post.pointimg');
            $radersize = I('post.radersize');
            $radercolor = I('post.radercolor');
            $raderalpha = I('post.raderalpha');
            $raderlinewidth = I('post.raderlinewidth');
            $radarlinecolor = I('post.radarlinecolor');
            $raderlinealpha = I('post.raderlinealpha');

            $mapwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id
            );
            $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();

            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = $panodir . "/toolbox/map";
            if (!is_dir(APP_ROOT . $mapdir)) {
                createFolder(APP_ROOT . $mapdir);
            }

            if (is_file(APP_ROOT . $thepointimg) && substr_count($thepointimg, "station") > 0) {
                if (!substr_count($thepointimg, "Public") > 0) {
                    @unlink(APP_ROOT . $maprow['thepointimg']);
                }
                $newimg = $mapdir . "/" . basename($thepointimg);
                rename(APP_ROOT . $thepointimg, APP_ROOT . $newimg);
                $thepointimg = $newimg;
            } elseif (substr_count($thepointimg, "Public") > 0) {
                $newimg = $mapdir . "/" . basename($thepointimg);
                copy(APP_ROOT . $thepointimg, APP_ROOT . $newimg);
                $thepointimg = $newimg;
            }

            if (is_file(APP_ROOT . $pointimg) && substr_count($pointimg, "station") > 0) {
                if (!substr_count($thepointimg, "Public") > 0) {
                    @unlink(APP_ROOT . $maprow['pointimg']);
                }
                $newimg = $mapdir . "/" . basename($pointimg);
                rename(APP_ROOT . $pointimg, APP_ROOT . $newimg);
                $pointimg = $newimg;
            } elseif (substr_count($pointimg, "Public") > 0) {
                $newimg = $mapdir . "/" . basename($pointimg);
                copy(APP_ROOT . $pointimg, APP_ROOT . $newimg);
                $pointimg = $newimg;
            }

            $data = array(
                'thepointimg' => $thepointimg,
                'pointimg' => $pointimg,
                'radersize' => $radersize,
                'radercolor' => $radercolor,
                'raderalpha' => $raderalpha,
                'raderlinewidth' => $raderlinewidth,
                'radarlinecolor' => $radarlinecolor,
                'raderlinealpha' => $raderlinealpha
            );

            M("Pano_toolbox_map")->where($mapwhere)->save($data);
            $this->redirect("point", array("pano_id" => $pano_id));
            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $this->display();
    }

    function faceto() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $viewwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        foreach ($viewlist as $vk => $vrow) {
            $vid = $vrow['id'];
            $vwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "view_id" => $vid
            );
            $vfind = M("Pano_toolbox_map_point")->where($vwhere)->find();
            if (!is_array($vfind)) {
                $data = array(
                    "member_id" => $this->member_id,
                    "pano_id" => $pano_id,
                    "view_id" => $vid
                );
                M("Pano_toolbox_map_point")->add($data);
                $vfind = M("Pano_toolbox_map_point")->where($vwhere)->find();
            }
            $viewlist[$vk]['maparr'] = $vfind;
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function mapxy() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $poid = I('get.poid');
        $this->assign('poid', $poid);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $xmlurl = U('mapxyxml', array('pano_id' => $pano_id, 'poid' => $poid));
        $xmlscript = "embedpano({id:\"krpano\",swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\",wmode:\"transparent\"});";
        $this->assign('xmlscript', $xmlscript);

        $this->display();
    }

    function mapxyxml() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $poid = I('get.poid');
        $this->assign('poid', $poid);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);
        $maparr = getimagesize(APP_ROOT . $maprow["mapimg"]);
        $this->assign("maparr", $maparr);

        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_map_point")->where($powhere)->find();
        $this->assign('porow', $porow);
        $sx = $porow["map_x"];
        $sy = $porow["map_y"];
        $this->mapx = $maparr[0] / 2 - $sx;
        $this->mapy = $maparr[1] / 2 - $sy;

        $viewwhere = array(
            "id" => $porow['view_id'],
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display('./App/Tpl/Member/Toolmap/mapxy.xml', 'utf-8', 'text/xml');
    }

    function mapface() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $poid = I('get.poid');
        $this->assign('poid', $poid);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_map_point")->where($powhere)->find();
        $this->assign('porow', $porow);
        $headingoffset = $porow["heading"];
        $this->assign("headingoffset", $headingoffset);

        $xmlurl = U('mapfacexml', array('pano_id' => $pano_id, 'poid' => $poid));
        $xmlscript = "embedpano({id:\"krpano\",swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\",wmode:\"transparent\"});";
        $this->assign('xmlscript', $xmlscript);

        $this->display();
    }

    function mapfacexml() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $poid = I('get.poid');
        $this->assign('poid', $poid);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);
        $maparr = getimagesize(APP_ROOT . $maprow["mapimg"]);
        $this->assign("maparr", $maparr);

        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_map_point")->where($powhere)->find();
        $this->assign('porow', $porow);
        $headingoffset = $porow["heading"];
        $this->assign("headingoffset", $headingoffset);

        $viewwhere = array(
            "id" => $porow['view_id'],
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display('./App/Tpl/Member/Toolmap/mapface.xml', 'utf-8', 'text/xml');
    }

    function setpos() {
        $pano_id = I('post.pano_id');
        $poid = I('post.poid');
        $map_x = I('post.map_x');
        $map_y = I('post.map_y');
        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $data = array(
            'map_x' => $map_x,
            'map_y' => $map_y
        );
        $one = M("Pano_toolbox_map_point")->where($powhere)->save($data);
        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
    }

    function setopen() {
        $pano_id = I('post.pano_id');
        $poid = I('post.poid');
        $open = I('post.open');
        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $data = array(
            'open' => $open
        );
        $one = M("Pano_toolbox_map_point")->where($powhere)->save($data);

        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
    }

    function setheading() {
        $pano_id = I('post.pano_id');
        $poid = I('post.poid');
        $heading = I('post.heading');
        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $data = array(
            'heading' => $heading
        );
        $one = M("Pano_toolbox_map_point")->where($powhere)->save($data);
        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
    }

    function pointactivexml() {
        $this->display('./App/Tpl/Member/Toolmap/pointactive.xml', 'utf-8', 'text/xml');
    }

    function pointxml() {
        $this->display('./App/Tpl/Member/Toolmap/point.xml', 'utf-8', 'text/xml');
    }

    function control() {
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
            $openmapimg = I('post.openmapimg');
            $hidemapimg = I('post.hidemapimg');
            $conrtolmap_center = I('post.conrtolmap_center');
            $conrtolmap_x = I('post.conrtolmap_x');
            $conrtolmap_y = I('post.conrtolmap_y');

            $mapwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id
            );
            $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();

            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = $panodir . "/toolbox/map";
            if (!is_dir(APP_ROOT . $mapdir)) {
                createFolder(APP_ROOT . $mapdir);
            }

            if (is_file(APP_ROOT . $openmapimg) && substr_count($openmapimg, "station") > 0) {
                if (!substr_count($openmapimg, "Public") > 0) {
                    @unlink(APP_ROOT . $maprow['openmapimg']);
                }
                $newimg = $mapdir . "/" . basename($openmapimg);
                rename(APP_ROOT . $openmapimg, APP_ROOT . $newimg);
                $openmapimg = $newimg;
            } elseif (substr_count($openmapimg, "Public") > 0) {
                $newimg = $mapdir . "/" . basename($openmapimg);
                copy(APP_ROOT . $openmapimg, APP_ROOT . $newimg);
                $openmapimg = $newimg;
            }

            if (is_file(APP_ROOT . $hidemapimg) && substr_count($hidemapimg, "station") > 0) {
                if (!substr_count($hidemapimg, "Public") > 0) {
                    @unlink(APP_ROOT . $maprow['hidemapimg']);
                }
                $newimg = $mapdir . "/" . basename($hidemapimg);
                rename(APP_ROOT . $hidemapimg, APP_ROOT . $newimg);
                $hidemapimg = $newimg;
            } elseif (substr_count($hidemapimg, "Public") > 0) {
                $newimg = $mapdir . "/" . basename($hidemapimg);
                copy(APP_ROOT . $hidemapimg, APP_ROOT . $newimg);
                $hidemapimg = $newimg;
            }

            $data = array(
                'hidemapimg' => $hidemapimg,
                'openmapimg' => $openmapimg,
                'conrtolmap_center' => $conrtolmap_center,
                'conrtolmap_x' => $conrtolmap_x,
                'conrtolmap_y' => $conrtolmap_y
            );

            M("Pano_toolbox_map")->where($mapwhere)->save($data);
            $this->redirect("control", array("pano_id" => $pano_id));
            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $poid = I('get.poid');
        $this->assign('poid', $poid);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $this->display();
    }

    function positionxml() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $poid = I('get.poid');
        $this->assign('poid', $poid);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $viewwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display('./App/Tpl/Member/Toolmap/position.xml', 'utf-8', 'text/xml');
    }

}

?>
