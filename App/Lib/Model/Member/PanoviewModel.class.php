<?php

class PanoviewModel extends Model{
    protected $tableName = "pano_view";

    public function GetOne($view_id,$member_id){
        $where = array(
            "id" => $view_id,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($view_id,$member_id,$spot_v){
        $this->GetOne($view_id,$member_id);
        return $this->row[$spot_v];
    }

    public function GetList($pano_id,$member_id,$order="sort"){
        $where = array(
            "pano_id" => $pano_id,
            "member_id" => $member_id
        );
        $this->list = $this->where($where)->order($order)->select();
        return $this->list;
    }
}

?>
