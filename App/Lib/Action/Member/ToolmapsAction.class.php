<?php

class ToolmapsAction extends MemberAction {

    public function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapswhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $mapsrow = M("Pano_toolbox_maps")->where($mapswhere)->find();
        if (!is_array($mapsrow)) {
            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/toolbox/maps";
            createFolder(APP_ROOT . $mapdir);

            $pointimg = "/Public/member/images/pano/mappoint.png";
            $thepointimg = "/Public/member/images/pano/mappointactive.png";
            $newimg = $mapdir . "/" . basename($thepointimg);
            copy(APP_ROOT . $thepointimg, APP_ROOT . $newimg);
            $thepointimg = $newimg;
            $newimg = $mapdir . "/" . basename($pointimg);
            copy(APP_ROOT . $pointimg, APP_ROOT . $newimg);
            $pointimg = $newimg;

            $addsql = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                'thepointimg' => $thepointimg,
                'pointimg' => $pointimg
            );

            M("Pano_toolbox_maps")->add($addsql);
            $mapsrow = M("Pano_toolbox_maps")->where($mapswhere)->find();
        }

        $this->assign("mapsrow", $mapsrow);

        $maplist = M("Pano_toolbox_maps_map")->where($mapswhere)->order("sort")->select();
        $this->assign('maplist', $maplist);

        $this->display();
    }

    function openmap(){
        $pano_id = I('post.pano_id');
        $open = I('post.open');
        $where = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $data = array(
            'open' => $open
        );
        $one = M("Pano_toolbox_maps")->where($where)->save($data);

        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
    }
	
	function open_map(){
        $pano_id = I('post.pano_id');
        $open = I('post.open');
        $where = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $data = array(
            'map_open' => $open
        );
        $one = M("Pano_toolbox_maps")->where($where)->save($data);

        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
    }

    function addmap() {
        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = $panodir . "/toolbox/maps";

            $mapname = I('post.mapname');
            $mapimg = I('post.mapimg');

            if (is_file(APP_ROOT . $mapimg)) {
                if (!is_dir(APP_ROOT . $mapdir)) {
                    createFolder(APP_ROOT . $mapdir);
                }
                $newimg = $mapdir . "/" . basename($mapimg);
                rename(APP_ROOT . $mapimg, APP_ROOT . $newimg);
                $mapimg = $newimg;
            }

            $where = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );

            $maplist = M("Pano_toolbox_maps_map")->where($where)->select();
            $num = count($maplist);
            $sort = $num + 1;

            $data = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id,
                'mapname' => $mapname,
                'mapurl' => $mapimg,
                "sort" => $sort
            );

            M("Pano_toolbox_maps_map")->where($where)->add($data);
            $this->redirect("index", array("pano_id" => $pano_id));

            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);


        $this->display();
    }

    function editmap() {
        if (I("post.dopost") == "save") {

            $pano_id = I('post.pano_id');
            $maps_id = I("post.maps_id");

            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = $panodir . "/toolbox/maps";

            $mapname = I('post.mapname');
            $mapimg = I('post.mapimg');

            $mapwhere = array(
                "id" => $maps_id,
                "member_id" => $this->member_id
            );
            $maprow = M("Pano_toolbox_maps_map")->where($mapwhere)->find();

            if (substr_count($mapimg, "station") > 0) {
                if (is_file(APP_ROOT . $mapimg)) {
                    if (!is_dir(APP_ROOT . $mapdir)) {
                        createFolder(APP_ROOT . $mapdir);
                    }
                    $newimg = $mapdir . "/" . basename($mapimg);
                    rename(APP_ROOT . $mapimg, APP_ROOT . $newimg);
                    $mapimg = $newimg;
                }
            }

            $data = array(
                'mapname' => $mapname,
                'mapurl' => $mapimg
            );
            M("Pano_toolbox_maps_map")->where($mapwhere)->save($data);
            $this->redirect("index", array("pano_id" => $pano_id));
            exit();
        }

        $maps_id = I("get.maps_id");
        $this->assign('maps_id', $maps_id);
        $mapwhere = array(
            "id" => $maps_id,
            "member_id" => $this->member_id
        );
        $maprow = M("Pano_toolbox_maps_map")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);
        $pano_id = $maprow['pano_id'];
        $this->assign('pano_id', $pano_id);
        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $this->display();
    }

    function delmap(){
        $maps_id = I("get.maps_id");
        $mapwhere = array(
            "id" => $maps_id,
            "member_id" => $this->member_id
        );
        $maprow = M("Pano_toolbox_maps_map")->where($mapwhere)->find();
        $pano_id = $maprow['pano_id'];
        @unlink(APP_ROOT.$maprow["mapurl"]);
        M("Pano_toolbox_maps_map")->where($mapwhere)->delete();

        $allwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $allrow = M("Pano_toolbox_maps_map")->where($allwhere)->order("sort")->select();
        $k = 1;
        foreach ($allrow as $arow) {
            $data = array(
                "sort" => $k
            );
            $where = array(
                "id" => $arow["id"]
            );
            M("Pano_toolbox_maps_map")->where($where)->save($data);
            $k++;
        }

        $this->redirect("index", array("pano_id" => $pano_id));
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
            $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();

            $panodir = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id;
            $mapdir = $panodir . "/toolbox/maps";
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

            M("Pano_toolbox_maps")->where($mapwhere)->save($data);
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $this->display();
    }

    function pointactivexml() {
        $this->display('./App/Tpl/Member/Toolmaps/pointactive.xml', 'utf-8', 'text/xml');
    }

    function pointxml() {
        $this->display('./App/Tpl/Member/Toolmaps/point.xml', 'utf-8', 'text/xml');
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $viewwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();

        $firstmapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $firstmap = M("Pano_toolbox_maps_map")->where($firstmapwhere)->order("sort")->find();

        $maplist = M("Pano_toolbox_maps_map")->order("sort")->select();

        $mapslist = array();
        foreach ($maplist as $mapl) {
            $mapslist[$mapl['id']] = $mapl;
        }
        $this->assign('mapslist', $mapslist);

        foreach ($viewlist as $vk => $vrow) {
            $vid = $vrow['id'];
            $vwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "view_id" => $vid
            );
            $vfind = M("Pano_toolbox_maps_point")->where($vwhere)->find();
            if (!is_array($vfind)) {
                $data = array(
                    "member_id" => $this->member_id,
                    "pano_id" => $pano_id,
                    "view_id" => $vid,
                    "maps_id" => $firstmap["id"]
                );
                M("Pano_toolbox_maps_point")->add($data);
                $vfind = M("Pano_toolbox_maps_point")->where($vwhere)->find();
            }
            $viewlist[$vk]['maparr'] = $vfind;
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function getmaps(){
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);
        $maplist = M("Pano_toolbox_maps_map")->where($mapwhere)->order("sort")->select();
        $this->assign('maplist', $maplist);

        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_map_point")->where($powhere)->find();
        $this->assign('porow', $porow);

        $maps_id = $porow["maps_id"];
        $this->display();
    }

    function setmapid(){
        $pano_id = I('post.pano_id');
        $poid = I('post.poid');
        $maps_id = I('post.maps_id');
        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $data = array(
            'maps_id' => $maps_id
        );
        $one = M("Pano_toolbox_maps_point")->where($powhere)->save($data);
        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);


        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_maps_point")->where($powhere)->find();
        $this->assign('porow', $porow);

        $maps_id = $porow["maps_id"];
        $mapswhere = array(
            "id" => $maps_id
        );
        $mapsrow = M("Pano_toolbox_maps_map")->where($mapswhere)->find();
        $this->assign('mapsrow', $mapsrow);

        $maparr = getimagesize(APP_ROOT . $mapsrow["mapurl"]);
        $this->assign("maparr", $maparr);

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

        $this->display('./App/Tpl/Member/Toolmaps/mapxy.xml', 'utf-8', 'text/xml');
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
        $one = M("Pano_toolbox_maps_point")->where($powhere)->save($data);
        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_maps_point")->where($powhere)->find();
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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);

        $powhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "id" => $poid
        );
        $porow = M("Pano_toolbox_maps_point")->where($powhere)->find();
        $this->assign('porow', $porow);
        $headingoffset = $porow["heading"];
        $this->assign("headingoffset", $headingoffset);

        $maps_id = $porow["maps_id"];
        $mapswhere = array(
            "id" => $maps_id
        );
        $mapsrow = M("Pano_toolbox_maps_map")->where($mapswhere)->find();
        $this->assign('mapsrow', $mapsrow);

        $maparr = getimagesize(APP_ROOT . $mapsrow["mapurl"]);
        $this->assign("maparr", $maparr);

        $viewwhere = array(
            "id" => $porow['view_id'],
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display('./App/Tpl/Member/Toolmaps/mapface.xml', 'utf-8', 'text/xml');
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
        $one = M("Pano_toolbox_maps_point")->where($powhere)->save($data);

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
        $one = M("Pano_toolbox_maps_point")->where($powhere)->save($data);
        if ($one > 0) {
            echo "showMsg('设置成功！',1);";
        }
    }

    function position(){
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
            $map_align = I('post.map_align');
            $map_w = I('post.map_w');
            $map_h = I('post.map_h');
            $map_x = I('post.map_x');
            $map_y = I('post.map_y');

            $mapwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id
            );

            $data = array(
                'map_align' => $map_align,
                'map_w' => $map_w,
                'map_h' => $map_h,
                'map_x' => $map_x,
                'map_y' => $map_y
            );

            M("Pano_toolbox_maps")->where($mapwhere)->save($data);
            $this->redirect("position", array("pano_id" => $pano_id));

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
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
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

        $this->display('./App/Tpl/Member/Toolmaps/position.xml', 'utf-8', 'text/xml');
    }

}

?>
