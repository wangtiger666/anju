<?php

class PanoModel extends Model{
    //put your code here
    protected $tableName = "pano";

    /**
     * 获得单个鼠标样式
     * @param type $spot_id
     * @return type
     */
    public function GetOne($pano_id,$member_id){
        $where = array(
            "id" => $pano_id,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($spot_id,$member_id,$spot_v){
        $this->GetOne($spot_id,$member_id);
        return $this->row[$spot_v];
    }

    public function GetList($member_id,$order="id"){
        $where = array(
            "member_id" => $member_id
        );
        $this->list = $this->where($where)->order($order)->select();
        return $this->list;
    }
}

?>
