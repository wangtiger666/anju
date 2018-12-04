<?php


class IndexAction extends MemberAction{
    public function index(){
        $menuhtml = "";
        $menuhtml = creatMenuList($this->menuList);
        $this->assign("menuhtml", $menuhtml);
        $minfo = M("Member")->where(array("id" => $this->member_id))->find();
		$this->assign("minfo", $minfo);
        $this->display();
    }
}

?>
