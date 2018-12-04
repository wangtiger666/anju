<?php

/**
 * 房源信息接口
 * Class HouseResourcesAction
 */
class HouseResourcesAction extends PublicAction
{

    //获取父节点信息
    function getParentAndChildInfo(){
        $name = I('post.name');
        $show_parent = I('post.show_parent', 1);
        $show_childList = I('post.show_childList', 1);

        $result = [];
        if(empty($name)){
            $result['code'] = 300;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }


        //获取父节点数据
        $region = M('region r')
                        ->join('left join pano_region pr on r.pid = pr.id')
                        ->where(['r.name' => $name])
                        ->field('pr.id as pid, pr.center as pcenter, pr.name as pname, r.id, r.center, r.name')
                        ->find();
//        OutputDebugString_DB('getParentAndChildInfo');
        if(empty($region)){
            $result['code'] = 400;
            $result['msg'] = '数据不存在';
            $this->ajaxReturn($result);
        }

        if($show_parent == 2){
            //封装父节点数据
            $weidu = explode(',', $region['pcenter']);
            $pdata = [
                'id' => $region['pid'],
                'name' => empty($region['pname'])?'无':$region['pname'],
                'longitude' => $weidu[0],
                'latitude' => $weidu[1],
            ];
            $result['parent_data'] = $pdata;
        }

        //封装节点
        $weidu = explode(',', $region['center']);
        $data = [
            'id' => $region['id'],
            'name' => empty($region['name'])?'无':$region['name'],
            'longitude' => $weidu[0],
            'latitude' => $weidu[1],
        ];

        if($show_childList == 2){
            //查询子节点
            $regionList = M('region r')
                ->join('left join pano_region pr on r.pid = pr.id')
                ->where(['pr.name' => $name])
                ->field('r.id, r.center, r.name')
                ->select();
            $childList = [];
            foreach ($regionList as $reg){
                $weidu = explode(',', $reg['center']);
                $child = [
                    'id' => $reg['id'],
                    'name' => empty($reg['name'])?'无':$reg['name'],
                    'longitude' => $weidu[0],
                    'latitude' => $weidu[1],
                ];
                $childList[] = $child;
            }
            $result['child_data'] = $childList;
        }
//        echo M()->getLastSql();exit();


        $result['code'] = 200;
        $result['data'] = $data;
        $this->ajaxReturn($result);
    }


    //获取子节点数据信息
    function getChildrenInfo(){
        $pid = I('post.pid');
        $name = I('post.name');

        $result = [];
        if(empty($pid)){
            $result['code'] = 300;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $where = [];
        $where['r.pid'] = $pid;

        if(!empty($name)){
            $where['r.name' ] = $name;
        }

        //查询子节点数据
        $childList =  M('region r')->where($where)->select();
//        echo M()->getLastSql();exit();
        if(empty($childList)){
            $result['code'] = 400;
            $result['msg'] = '数据不存在';
            $this->ajaxReturn($result);
        }

        //遍历子节点，查询子节点数据
        $dataList = [];
        foreach ($childList as $k => $v){

            $weidu = explode(',', $v['center']);
            $datas = [
                'id' => $v['id'],
                'name' => empty($v['name'])?'无':$v['name'],
                'longitude' => $weidu[0],
                'latitude' => $weidu[1],
            ];

            $dataList['regionList'][] = $datas;

            $regionList = M('region r')
                        ->where(['r.pid' => $v['id']])
                        ->select();
//            echo M()->getLastSql();exit();
            if(!empty($regionList)){

                foreach ($regionList as $key => $value){

                    $weidu = explode(',', $value['center']);
                    $data = [
                        'id' => $value['id'],
                        'pid' => $v['id'],
                        'name' => empty($value['name'])?'无':$value['name'],
                        'longitude' => $weidu[0],
                        'latitude' => $weidu[1],
                        'house_num' => 0,
                    ];

                    //查询该区域下房源数量
                    if(in_array($value['level'], ['province','city','district','uptown'])){
                        $searKey = $value['level'] . '_id';
                        $houseNum = M('house_resources')
                            ->where(['status' => array('neq', '5'), "{$searKey}" => $value['id'],'type'=>2])
                            ->count('id');

                        $data['house_num'] = $houseNum;
                    }

                    $dataList['childList'][] = $data;
                }
            }
        }

        $result['code'] = 200;
        $result['dataList'] = $dataList;
        $this->ajaxReturn($result);
    }


    //获取详细的房信息
    function getHouseList(){
            $pid = I('post.pid');//获取父节点
            $name = I('post.name');//小区名称
            $uptown_show = I('post.uptown_show', 1);//是否返回小区信息 1:不返回小区信息  2：返回小区信息
            $house_type = I('post.house_type', 0);//几房几厅 -- 单个查找 类型id
            $lease_money = I('post.lease_money', 0);//租金  -- 范围查找 格式：0-500
            $lease_type = I('post.lease_type', 0);//租赁方式 -- 单个查找 类型id
            $uptown_type = I('post.uptown_type', 0);//小区类型 -- 多选 格式：类型id-类型id
            $house_direction = I('post.house_direction', 0);//朝向 -- 多选 格式：类型id-类型id
            $house_equip = I('post.house_equip', 0);//房屋设施 -- 多选  格式：冰箱-洗衣机

            $result = [];
            if(empty($name) || empty($pid)){
                $result['code'] = 300;
                $result['msg'] = '参数错误';
                $this->ajaxReturn($result);
            }

            $result['code'] = 200;


            //获取小区信息
            $region = M('region')->where(['pid' => $pid, 'name' => $name, 'level' => 'uptown'])->find();
//        echo M()->getLastSql();exit();
            if($uptown_show == 2){
                $uptownData = [
                    'id' => $region['id'],
                    'name' => $region['name'],
                    'intro' => $region['content'],
                    'img' => $region['img'],
                    'house_num' => 0
                ];
                $pano = M('house_resources')->field('pano_id,member_id')->where(["uptown_id"=>$region['id']])->find();
                $uptownData['pano_id'] = $pano['pano_id'];
                $uptownData['member_id'] = $pano['member_id'];

                //查询房源数
                $house_num_total = M('house_resources')
                    ->where(['status' => array('neq', '5'), "uptown_id" => $region['id'], 'type' => '2'])
                    ->count('id');
                $house_num1 = M('house_resources')
                    ->where(['status' => array('neq', '5'), "uptown_id" => $region['id'], 'type' => '2','house_type'=>4])
                    ->count('id');
                $house_num2 = M('house_resources')
                    ->where(['status' => array('neq', '5'), "uptown_id" => $region['id'], 'type' => '2','house_type'=>5])
                    ->count('id');
                $house_num3 = M('house_resources')
                    ->where(['status' => array('neq', '5'), "uptown_id" => $region['id'], 'type' => '2','house_type'=>6])
                    ->count('id');

//            echo M()->getLastSql();exit();

                //查询guid
                $hr = M('house_resources')->field('pano_guid')->where(["uptown_id" => $region['id'], 'type' => '1'])->find();
                $nk = M('house_resources')->field('pano_guid,pano_id,member_id')->where(["uptown_id" => $region['id'], 'type' => '3'])->find();
                $uptownData['pano_guid'] = $hr['pano_guid'];
                $uptownData['pano_nk_guid'] = $nk['pano_guid'];
                $uptownData['house_num_total'] = $house_num_total;
                $uptownData['house_num1'] = $house_num1;
                $uptownData['house_num2'] = $house_num2;
                $uptownData['house_num3'] = $house_num3;
                $uptownData['house_num4'] = $house_num_total - $house_num1 - $house_num2 - $house_num3;
                $result['uptown_data'] = $uptownData;
                $nkData['pano_id'] = $nk['pano_id'];
                $nkData['member_id'] = $nk['member_id'];
                $result['nk_data'] = $nkData;
            }

            $where = [
                'status' => array('neq', '5'),
                "uptown_id" => $region['id'],
                "type" => '2',
            ];

            //查询条件过滤
            if($house_type){
                $where['house_type'] = $house_type;
            }
            if($lease_money){
                $arr = explode('-', $lease_money);
                $where['lease_money'] = array('between', "{$arr[0]}, {$arr[1]}");
            }
            if($lease_type){
                $where['lease_type'] = $lease_type;
            }
            if($uptown_type){
                $arr = explode('-', $uptown_type);
                $inarr = [];
                foreach ($arr as $ak => $av){
                    $inarr[] = $av;
                }
                $where['uptown_type'] = array('in', $inarr);
            }

            if($house_direction){
                $arr = explode('-', $house_direction);
                $inarr[] = 'in';
                foreach ($arr as $ak => $av){
                    $inarr[] = $av;
                }
                $where['house_direction'] = array('in', $inarr);
            }

            //获取具体的房源查询
            $query = $list = M('house_resources')->where($where);

            if($house_equip){
                $insetStr = '';
                $whereFindInSet = [];
                $arr = explode('-', $house_equip);
                foreach ($arr as $ak => $av){
                    $whereFindInSet[] = 'find_in_set("' . $av . '", house_equip)';
                }
                $insetStr = implode(' and ', $whereFindInSet);

                //获取具体的房源信息
                $query = $query->where($insetStr);

            }

            //获取具体的房源信息
            $list = $query->select();

            // echo M()->getLastSql();
            //获取类型数组
            $houseTypeArr = $this->getCommonalityArr(self::$HOUSE_TYPE);
            $houseDirectionArr = $this->getCommonalityArr(self::$HOUSE_DIRECTION);
            $leaseTypenArr = $this->getCommonalityArr(self::$LEASE_TYPE);


            $houseList = [];
            foreach ($list as $key => $value){
                $house = [
                    'house_id' => $value['id'],
                    'uptown_name' => $name,
                    'pano_guid' => $value['pano_guid'],
                    'house_title' => $value['house_title'],
                    'house_img' => $value['house_img'],
                    'house_type' => $houseTypeArr[$value['house_type']],
                    'house_direction' => $houseDirectionArr[$value['house_direction']],
                    'lease_type' => $leaseTypenArr[$value['lease_type']],
                    'lease_money' => $value['lease_money'],
                    'house_no'=>$value['house_no'],
                    'detail_img'=>$value['detail_img'],
                    'pano_id'=>$value['pano_id'],
                    'member_id'=>$value['member_id']
                ];
                $houseList[] = $house;
            }
            $result['house_list'] = $houseList;
			$this->ajaxReturn($result);
        }

        

    //获取小区楼号及房源数
    public function getBuildingData(){
        $pid = I('post.pid');//获取父节点
        $name = I('post.name');//小区名称

        $result = [];
        if(empty($name) || empty($pid)){
            $result['code'] = 300;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $result['code'] = 200;

        $region = M('region')->where(['pid' => $pid, 'name' => $name, 'level' => 'uptown'])->find();

        $list = M('house_resources')
                        ->where(['status' => array('neq', '5'), "uptown_id" => $region['id']])
                        ->field('count(building_no) as count, building_no, center')
                        ->group('building_no')
                        ->select();
//        echo M()->getLastSql();exit();

        $dataList = [];
        foreach ($list as $key => $value) {

            $weidu = explode(',', $value['center']);
            $data = [
                'building_no' => $value['building_no'],
                'longitude' => $weidu[0],
                'latitude' => $weidu[1],
                'house_num' => $value['count'],
            ];
            $dataList[] = $data;
        }
        $result['data_list'] = $dataList;
        $this->ajaxReturn($result);

    }

    //获取房屋信息类型
    public function getHouseInfoAttr(){

        require_once APP_NAME . '/../App/Common/Constants.php';

        $key = 'qianduan_house_info_attr';
        $houseInfoList = M('commonality as com')//->cache($key, 3600)
                        ->join('left join pano_commonality as com_parent on com.pid = com_parent.id')//关联父节点
                        ->where(['com_parent.name' => Constants::$HOUSER_INFO_ATTR])
                        ->field('com.id, com.name')->select();
        $houseAttrList = [];
        foreach ($houseInfoList as $key => $value){

            $attrList = M('commonality as com')//->cache($key, 3600)
                            ->join('left join pano_commonality as com_child on com_child.pid = com.id')//关联子节点
                            ->where(['com.id' => $value['id']])
                            ->field('com_child.id, com_child.name')->select();

//            echo M()->getLastSql();exit();
            $houseAttr = [
                'pid' => $value['id'],
                'pname' => $value['name'],
            ];

            foreach ($attrList as $k => $v){

                $attr = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                ];
                $houseAttr['childList'][] = $attr;
            }

            $houseAttrList[] = $houseAttr;

        }

        $this->ajaxReturn($houseAttrList);

    }

    // 根据搜索条件获取相关的房源信息
    public function getUptownInfo(){
        $city_id = I('post.city_id');// 城市id
        $quyu_id = I('post.district_id');//区域id
        $page = I('post.page', 1);//默认是第一页
        $page_size = I('post.page_size', 5);
        $uptown_show = I('post.uptown_show', 1);//是否返回小区信息 1:不返回小区信息  2：返回小区信息
        $house_type = I('post.house_type', 0);//几房几厅 -- 单个查找 类型id
        $lease_money = I('post.lease_money', 0);//租金  -- 范围查找 格式：0-500
        $lease_type = I('post.lease_type', 0);//租赁方式 -- 单个查找 类型id
        $uptown_type = I('post.uptown_type', 0);//小区类型 -- 多选 格式：类型id-类型id
        $house_direction = I('post.house_direction', 0);//朝向 -- 多选 格式：类型id-类型id
        $house_equip = I('post.house_equip', 0);//房屋设施 -- 多选  格式：冰箱-洗衣机

        $result = [];
        if(empty($city_id)){
            $result['code'] = 300;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $result['code'] = 200;

        //获取区域信息
        $district = M('region')->where(['pid' => $city_id,'level'=>'district'])->select();

        if($uptown_show == 2){
            foreach ($district as $ak => $av){
                $district_id[] = $av['id'];
            }
            $where['pid'] = ['in',$district_id];
            $uptown = M('region')->where($where)->where(['level'=>'uptown'])->limit($page_size)->page($page)->select();

           // echo M()->getLastSql();exit();
            $house_where = self::getHouseWhere($quyu_id,$lease_money,$lease_type,$house_type,$house_direction,$uptown_type,$house_equip);
            foreach ($uptown as $ak => $av){
                $houseNum = M('house_resources')
                    ->where(['status' => array('neq', '5'), "uptown_id" => $av['id'],"type"=>2])
                    ->where($house_where)
                    ->count('id');
                if ($houseNum > 0){
                    $uptownData = [
                        'id' => $av['id'],
                        'name' => $av['name'],
                        'intro' => $av['content'],
                        'img' => $av['img'],
                        'house_num' => $houseNum,
                    ];
                    $house_resources = M('house_resources')
                        ->where(['status' => array('neq', '5'), "uptown_id" => $av['id'],"type"=>2])
                        ->where($house_where)
                        ->select();
                    $houseTypeArr = $this->getCommonalityArr(self::$HOUSE_TYPE);
                    $houseDirectionArr = $this->getCommonalityArr(self::$HOUSE_DIRECTION);
                    $leaseTypenArr = $this->getCommonalityArr(self::$LEASE_TYPE);
//                echo M()->getLastSql();exit();
                    foreach ($house_resources as $ak=>$av){
                        $house = [
                            'house_id' => $av['id'],
                            'pano_guid' => $av['pano_guid'],
                            'house_title' => $av['house_title'],
                            'house_img' => $av['house_img'],
                            'house_type' => $houseTypeArr[$av['house_type']],
                            'house_direction' => $houseDirectionArr[$av['house_direction']],
                            'lease_type' => $leaseTypenArr[$av['lease_type']],
                            'lease_money' => $av['lease_money'],
                        ];

                        $uptownData['house_list'][] = $house;
                    }

                    $result['uptown_data'][] = $uptownData;
                }
            }

            if (empty($result['uptown_data'])){
                $result['code'] = 400;
                $result['msg'] = '暂无数据';
                $this->ajaxReturn($result);
            }
        }
        $this->ajaxReturn($result);
    }

    public function getHouseWhere($quyu_id,$lease_money,$lease_type,$house_type,$house_direction,$uptown_type,$house_equip){
        //查询条件过滤
        if ($quyu_id){
            $where['district_id'] = $quyu_id;
        }

        if($lease_money){
            $arr = explode('-', $lease_money);
            $where['lease_money'] = array('between', "{$arr[0]}, {$arr[1]}");
        }

        if($lease_type){
            $where['lease_type'] = $lease_type;
        }

        if($house_type){
            $where['house_type'] = $house_type;
        }

        if($house_direction){
            $arr = explode('-', $house_direction);
            $inarr[] = 'in';
            foreach ($arr as $ak => $av){
                $inarr[] = $av;
            }
            $where['house_direction'] = array('in', $inarr);
        }

        if($uptown_type){
            $arr = explode('-', $uptown_type);
            $inarr = [];
            foreach ($arr as $ak => $av){
                $inarr[] = $av;
            }
            $where['uptown_type'] = array('in', $inarr);
        }

        if($house_equip){
            $arr = explode('-', $house_equip);
            $inarr = [];
            foreach ($arr as $ak => $av){
                $inarr[] = $av;
            }
            $where['house_equip'] = array('in', $inarr);
        }
        return $where;
    }

    // 根据小区名称获取相关信息
    public function getUptownInfoById(){
        $city_id = I('post.city_id');// 城市id
        $name = I('post.name');// 小区名称
        $result = [];
        if (empty($city_id) || empty($name)){
            $result['code'] = 300;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }
        $result['code'] = 200;
        //获取区域信息
        $district = M('region')->where(['pid' => $city_id,'level'=>'district'])->select();
        foreach ($district as $ak => $av){
            $district_id[] = $av['id'];
        }
        $where['pid'] = ['in',$district_id];
        $where['name'] = array('like',"%".$name."%");
        $uptown = M('region')->where($where)->where(['level'=>'uptown'])->select();
        foreach ($uptown as $val){
            $arr = explode(',',$val['center']);
            $area_id = $val['pid'];
            $area_name = M('region')->where(['id'=>$area_id])->find();
            $area_name = $area_name['name'];
            $houseNum = M('house_resources')
                ->where(['status' => array('neq', '5'), "uptown_id" => $val['id'],"type"=>2])
                ->count('id');

            $uptownData = [
                'name'=>$val['name'],
                'longitude'=>$arr[0],
                'latitude'=>$arr[1],
                'house_num'=>$houseNum,
                'area_name'=>$area_name,
                'area_id'=>$area_id,
            ];
            $result['uptown_data'][] = $uptownData;
        }

        $this->ajaxReturn($result);
    }

    // 根据经纬度获取小区相关信息
    public function getUptownInfoByCircular(){
        $lng = I('longitude');// 圆中心点经度
        $lat = I('latitude');// 圆中心的纬度
        $radius = I('radius');// 半径
        $sql = "SELECT *,latitude,longitude,GETDISTANCE(latitude,longitude,".$lat.",".$lng.") AS distance FROM  pano_region where level='uptown' HAVING distance < ".$radius." ORDER BY distance ASC";
        $data = M()->query($sql);
       // echo M()->getLastSql();exit();
//        print_r($data);
        $result['code'] = 200;
        if (empty($data)){
            $result['code'] = 400;
            $result['msg'] = '暂无数据';
            $this->ajaxReturn($result);
        }
        foreach ($data as $val){
            $name = $val['name'];
            $longitude = $val['longitude'];
            $latitude = $val['latitude'];
            $area_id = $val['pid'];
            $area_name = M('region')->where(['id'=>$area_id])->find();
            $area_name = $area_name['name'];
            $houseNum = M('house_resources')
                ->where(['status' => array('neq', '5'), "uptown_id" => $val['id'],"type"=>2])
                ->count('id');
            $town_data = [
                'name'=>$name,
                'longitude'=>$longitude,
                'latitude'=>$latitude,
                'house_num'=>$houseNum,
                'area_id'=>$area_id,
                'area_name'=>$area_name
            ];
            $result['town_data'][] = $town_data;
        }
        $this->ajaxReturn($result);

    }

    // 房屋详情信息
    public function house_detail_infos(){
        import('@.Model.Region');
        $result['code'] = 200;
        $id = I('post.id');
        if (empty($id)){
            $result['code'] = 401;
            $result['msg'] = '参数错误';
        }
        $row = M('house_resources')->where(['id'=>$id])->find();
        if (empty($row)){
            $result['code'] = 400;
            $result['msg'] = '暂无数据';
        }


        $info = Region::get_region_info($row['uptown_id'],'uptown');
        $area_name = Region::get_region_info($info['pid'],'district');
        $houseNum = M('house_resources')
            ->where(['status' => array('neq', '5'), "uptown_id" => $row['uptown_id'],"type"=>2])
            ->count('id');
        $tel = substr_replace($row['tel'], '****', 3, 4);
        $data = [
            'name'=>$info['name'],
            'house_num'=>$houseNum,
            'house_type'=>Region::commonality_type($row['house_type']),
            'house_direction'=>Region::commonality_type($row['house_direction']),
            'house_size'=>$row['house_size'],
            'lease_type'=>Region::commonality_type($row['lease_type']),
            'area_name'=>$area_name['name'],
            'detail_address'=>$row['detail_address'],
            'house_equip'=>$row['house_equip'],
            'tel'=>$tel
        ];
        $result['house_detail'] = $data;
        $this->ajaxReturn($result);
    }

    // 手机端精选作品
    public function pano_img(){
        $result['code'] = 200;
        $category = I('post.category');
        if (empty($category)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
        }
        $list = M('pano p')
            ->field('p.id,p.title,hr.category,pv.thumb')
            ->join('left join pano_house_resources hr on hr.pano_id = p.id')
            ->join('left join pano_pano_view pv ON p.id = pv.pano_id')
            ->where(['hr.category'=>$category])
            ->group('p.id')
            ->select();
        if (empty($list)){
            $result['code'] = 400;
            $result['msg'] = '暂无数据';
        }
        foreach ($list as $val){
            $data = [
                'pano_id'=>$val['id'],
                'image'=>$val['thumb'],
                'title'=>$val['title']
            ];
            $result['data'][] = $data;
        }
        $this->ajaxReturn($result);
    }

}