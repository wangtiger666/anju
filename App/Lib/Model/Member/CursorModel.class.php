<?php

class CursorModel extends Model{
    protected $tableName = "cursor";

    /**
     * 获得单个鼠标样式
     * @param type $spot_id
     * @return type
     */
    public function GetOne($spot_id){
        $where = array(
            "id" => $spot_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($spot_id,$spot_v){
        $this->GetOne($spot_id);
        return $this->row[$spot_v];
    }

}

?>
