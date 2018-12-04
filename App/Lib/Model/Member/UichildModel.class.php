<?php

class UichildModel extends Model{
    protected $tableName = "ui_child";
    
    public function GetOne($cid){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "id" => $cid,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($cid,$spot_v){
        $this->GetOne($cid);
        return $this->row[$spot_v];
    }
    
    public function GetList($uid,$order="id"){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "uid" => $uid,
            "member_id" => $member_id
        );
        $this->list = $this->where($where)->order($order)->select();
        return $this->list;
    }
    
}

?>
