<?php

class UactionModel extends Model{
    protected $tableName = "ui_action";

    var $chu=array(
        "click" => "鼠标点击",
        "over" => "鼠标经过",
        "out" => "鼠标移开",
        "down"=>"鼠标按下",
        "up" => "鼠标弹起"
    );

    public function GetOne($aid){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "id" => $aid,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($aid,$spot_v){
        $this->GetOne($aid);
        return $this->row[$spot_v];
    }

    public function GetList($cid){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "member_id" => $member_id,
            "cid" => $cid
        );
        $this->list = $this->where($where)->select();

        return $this->list;
    }
}

?>
