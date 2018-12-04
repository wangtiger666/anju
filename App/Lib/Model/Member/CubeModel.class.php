<?php

class CubeModel extends Model{
    protected $tableName = "pano_cube";

    /**
     * 获得单个鼠标样式
     * @param type $spot_id
     * @return type
     */
    public function GetOne($cube_id,$member_id){
        $where = array(
            "id" => $cube_id,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($cube_id,$member_id,$v){
        $this->GetOne($cube_id,$member_id);
        return $this->row[$v];
    }
    
    public function GetList($view_id,$member_id,$order="id"){
        $where = array(
            "view_id" => $view_id,
            "member_id" => $member_id
        );
        $this->list = $this->where($where)->order($order)->select();
        return $this->list;
    }
    
    public function DelOne($cube_id,$member_id){
        $where = array(
            "id" => $cube_id,
            "member_id" => $member_id
        );
        $this->where($where)->delete();
    }
}

?>
