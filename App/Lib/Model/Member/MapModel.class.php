<?php

class MapModel extends Model{
    //put your code here
    protected $tableName = "pano_map";

    public function GetOne($map_id,$member_id){
        $where = array(
            "id" => $map_id,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
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
