<?php
/**
 * Created by PhpStorm.
 * User: chao you 02
 * Date: 2018/9/9
 * Time: 1:12
 */
class Region{
    public static function commonality_type($id){
        $commonality_type_row = M('commonality')->where(['id'=>$id])->find();
        $commonality_type = $commonality_type_row['name'];
        return $commonality_type;
    }

    public static function get_region_info($id,$level){
        $region_info_row = M('region')->where(['id'=>$id,'level'=>$level])->find();
        $region_info = [
            'name'=>$region_info_row['name'],
            'pid'=>$region_info_row['pid']
        ];
        return $region_info;
    }
}