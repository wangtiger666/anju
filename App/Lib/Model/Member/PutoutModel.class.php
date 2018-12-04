<?php

class PutoutModel extends Model{
    protected $tableName = "pano_putout";

    public function GetOne($putout_id){
        $member_pix = C("SESSION_MEMBERID");
        $member_id = $_SESSION[$member_pix];
        $where = array(
            "id" => $putout_id,
            "member_id" => $member_id
        );
        $this->row = $this->where($where)->find();
        return $this->row;
    }

    public function GetValue($putout_id,$spot_v){
        $this->GetOne($putout_id);
        return $this->row[$spot_v];
    }
}

?>
