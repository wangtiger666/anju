<?php

class ToolboxAction extends MemberAction {

    public function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();
        $this->tmap = $maprow["open"];

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->tmaps = $maprow["open"];

        $this->display();
    }

}

?>
