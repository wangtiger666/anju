<?php

class UiModel extends Model{
    protected $tableName = "ui";
    
    public function GetOne($ui_id){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "id" => $ui_id,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($ui_id,$spot_v){
        $this->GetOne($ui_id);
        return $this->row[$spot_v];
    }
    
    public function GetList($cid,$order="id"){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "member_id" => $member_id,
            "cid" => $cid
        );
        $this->list = $this->where($where)->order($order)->select();
        return $this->list;
    }
    
}

?>
