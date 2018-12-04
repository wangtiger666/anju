<?php

class MapAction extends MemberAction {

    public function index($pano_id) {
        cookie("mapback", __SELF__);
        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->panorow = $panorow;

        $mapwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
        );
        $maplist = M("Pano_map")->where($mapwhere)->select();
        $this->maplist = $maplist;
        $this->pano_id = $pano_id;
        $this->display();
    }

    public function add($pano_id) {
        $backurl = cookie("mapback");
        $this->backurl = $backurl;
        if (I("post.dopost") == "save") {
            $title = I('post.title');
            $pano_id = I('post.pano_id');

            $data = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "title" => $title
            );
            $newid = M("Pano_map")->add($data);

            $viewlist = D("Panoview")->getList($pano_id, $this->member_id);
            foreach ($viewlist as $key => $value) {
                $data = array(
                    "map_id" => $newid,
                    "view_id" => $value['id'],
                    "open" => 0,
                    "scale" => 1,
                    "alpha" => 1,
                    "x" => 0,
                    "y" => 0
                );
                M("Pano_map_node")->add($data);
            }
            $this->success("添加成功！", U("edit", array("map_id" => $newid)));

            exit();
        }
        $this->pano_id = $pano_id;
        $this->display();
    }

    public function edit($map_id) {
        $backurl = cookie("mapback");
        $this->backurl = $backurl;
        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $title = I('post.title');
            $map_url = I('post.map_url');
            $align = I('post.align');
            $x = I('post.x');
            $y = I('post.y');
            $scale = I('post.scale') / 100;
            $alpha = I('post.alpha') / 100;
            $radar_scale = I('post.radar_scale') / 100;
            $radar_fillcolor = I('post.fillcolor');
            $radar_fillalpha = I('post.fillalpha') / 100;
            $radar_linecolor = I('post.linecolor');
            $radar_linealpha = I('post.linealpha') / 100;
//            $rotate = I('post.rotate');
//            $crop = I('post.crop');
//            $over_open = I('post.over_open');
//            $onovercrop = I('post.onovercrop');
//            $down_open = I('post.down_open');
//            $ondowncrop = I('post.ondowncrop');

            $where = array(
                "member_id" => $this->member_id,
                "id" => $map_id
            );
            $data = array(
                "title" => $title,
                "map_url" => $map_url,
                "align" => $align,
                "x" => $x,
                "y" => $y,
                "scale" => $scale,
                "alpha" => $alpha,
                "radar_scale" => $radar_scale,
                "radar_fillcolor" => $radar_fillcolor,
                "radar_fillalpha" => $radar_fillalpha,
                "radar_linecolor" => $radar_linecolor,
                "radar_linealpha" => $radar_linealpha,
//                "rotate" => $rotate,
//                "crop" => $crop,
//                "over_open" => $over_open,
//                "onovercrop" => $onovercrop,
//                "down_open" => $down_open,
//                "ondowncrop" => $ondowncrop
            );
            M("Pano_map")->where($where)->save($data);
            $this->success("设置成功！", $backurl);
            exit();
        }
        $this->map_id = $map_id;

        $mapwhere = array(
            "member_id" => $this->member_id,
            "id" => $map_id
        );
        $map = M("Pano_map")->where($mapwhere)->find();
        $this->map = $map;
        $pano_id = $map["pano_id"];
        $this->pano_id = $pano_id;
        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->panorow = $panorow;

        $this->display();
    }

    public function del($map_id) {
        $backurl = cookie("mapback");
        $where = array(
            "member_id" => $this->member_id,
            "id" => $map_id
        );
        M("Pano_map")->where($where)->delete();
        $node = array(
            "map_id" => $map_id
        );
        M("Pano_map_node")->where($node)->delete();
        $this->success("删除成功！", $backurl);
    }

    public function node_create($map_id) {
        $Map_model = D("Map");
        $map = $Map_model->getOne($map_id, $this->member_id);
        $panorow = D("Pano")->getOne($map['pano_id'], $this->member_id);
        $viewlist = D("Panoview")->getList($map['pano_id'], $this->member_id);
        foreach ($viewlist as $key => $value) {
            $awhere = array(
                "view_id" => $value['id'],
                "map_id" => $map_id
            );
            $arow = M("Pano_map_node")->where($awhere)->find();
            $viewlist[$key]['open'] = empty($arow['open']) ? 0 : 1;
        }
        $this->panorow = $panorow;
        $this->pano_id = $map['pano_id'];
        $this->viewlist = $viewlist;
        $this->map_id = $map_id;
        $this->display();
    }

    public function node_edit($view_id, $map_id) {
        $this->view_id = $view_id;
        $this->map_id = $map_id;
        $where = array(
            "view_id" => $view_id,
            "map_id" => $map_id
        );
        if (I("post.dopost") == "save") {
            $data = array(
                "open" => I('post.open'),
                "node_url" => I('post.node_url'),
                "scale" => I('post.scale')/100,
                "alpha" => I('post.alpha')/100,
                "x" => I('post.x'),
                "y" => I('post.y'),
                "heading" => I('post.heading')
            );
            M("Pano_map_node")->where($where)->save($data);
//            echo M("Pano_map_node")->getLastSql();exit;
            $this->success("设置成功！", U("node_create", array("map_id" => $map_id)));
            exit();
        }
        $map = D("Map")->getOne($map_id, $this->member_id);
        $this->pano_id = $map['pano_id'];
        $this->node = M("Pano_map_node")->where($where)->find();
        $this->display();
    }

    function mapxml($map_id, $view_id = 0, $type = 'map') {

        $where = array(
            "member_id" => $this->member_id,
            "id" => $map_id
        );

        $row = M("Pano_map")->where($where)->find();
        if ($type == "node") {
            $viewwhere = array(
                "id" => $view_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();
            $node_wehre = array(
                "map_id" => $map_id,
                "view_id" => $view_id
            );
            $this->node = M("Pano_map_node")->where($node_wehre)->find();
            $this->assign("viewrow", $viewrow);
        }
//        $map_url = cookie("mapurl");
        $this->row = $row;
//        $this->map_url = empty($row['map_url'])?;
        $this->type = $type;
        $this->display('./App/Tpl/Member/Map/mapxml.xml', 'utf-8', 'text/xml');
    }

//    function mapxml($map_id) {
//
//        $where = array(
//            "member_id" => $this->member_id,
//            "id" => $map_id
//        );
//
//        $row = M("Pano_map")->where($where)->find();
//        $this->row = $row;
//
//        $this->display('./App/Tpl/Member/Map/mapnodexml.xml', 'utf-8', 'text/xml');
//    }
}
