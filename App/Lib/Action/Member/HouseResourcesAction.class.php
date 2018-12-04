<?php

class HouseResourcesAction extends MemberAction
{
    //列表页
    public function index(){

    }

    //新增
    public function add(){
        if ($_POST['dopost'] == "save"){
            $data = I('post.');

            $result = [];
            $type = $data['type'];

            if( $type == '2'){
                foreach ($data as $key => $value){
                    if(empty($value) && $key != 'house_equip' && $key != 'img' && $key != 'category' && $key != 'content' && $key != 'detail_address'){
                        $result['code'] = 400;
                        $result['msg'] = '必填信息不能为空';
                        $this->ajaxReturn($result);
                    }
                }
            }
            if ($type == '3'){
                if(empty($data['type']) || empty($data['province_id']) ||
                    empty($data['city_id']) || empty($data['district_id'])
                    || empty($data['xiaoqu'])){
                    $result['code'] = 400;
                    $result['msg'] = '必填信息不能为空';
                    $this->ajaxReturn($result);
                }
            }


            //生成访问编号
            $house_no = $this->createHouseNo($data['district_id']);

            //判断小区是否存在
            $region = M('region')->where(['pid'=>$data['district_id'],'name'=>$data['xiaoqu']])
                            ->field('id')->find();
            OutputDebugString_DB('region');
            if(empty($region)){
                $arr = explode(',',$data['zuobiao']);
                $regionData = [
                    'name'=>$data['xiaoqu'],
                    'pid'=>$data['district_id'],
                    'center'=>empty($data['zuobiao']) ? '' : $data['zuobiao'],
                    'level'=>'uptown',
                    'longitude'=>empty($arr) ? 0 : $arr[0],
                    'latitude'=>empty($arr) ? 0 : $arr[1],
                    'img'=>empty($data['img']) ? '' : $data['img'],
                    'category'=>empty($data['category']) ? '' : $data['category'],
                    'content'=>empty($data['content']) ? '' : $data['content']
                ];
                M('region')->add($regionData);
                OutputDebugString_DB('region123');
                $region = M('region')->where(['pid'=>$data['district_id'],'name'=>$data['xiaoqu']])
                    ->field('id')->find();
                $uptown_id = $region['id'];
//                $uptown_id = M()->getLastInsID();
            }else{
                $uptown_id = $region['id'];
            }

            $nowtime = date('Y-m-d H:i:s');

            //查询guid
            $pano = M('pano')->where(['id' => $data['pano_id']])
                                        ->field('guid')->find();
            OutputDebugString('uptown_id_1',$uptown_id);
            if($type == '2'){
                OutputDebugString('uptown_id_2',$uptown_id);
                $house_equip = implode(',', $_POST['house_equip']);
                $addData = [
                    'house_title' => $data['house_title'],
                    'pano_id' => $data['pano_id'],
                    'pano_guid' => $pano['guid'],
                    'province_id' => $data['province_id'],
                    'city_id' => $data['city_id'],
                    'district_id' => $data['district_id'],
                    'uptown_id' => $uptown_id,
                    'building_no' => $data['lhbianhao'],
                    'door_card' => $data['menpai'],
                    'center' => $data['zuobiao'],
                    'detail_address' => $data['detail_address'],
                    'lease_money' => $data['lease_money'],
                    'house_desc' => $data['house_desc'],
                    'house_type' => $data['house_type'],
                    'house_direction' => $data['house_direction'],
                    'uptown_type' => $data['uptown_type'],
                    'member_id' => $this->member_id,
                    'lease_type' => $data['lease_type'],
                    'house_equip' => $house_equip,
                    'house_size' => $data['house_size'],
                    'house_img' => $data['house_img'],
                    'create_time' => $nowtime,
                    'modify_time' => $nowtime,
                    'house_no' => $house_no,
                    'type' => $type,
                    'category'=>$data['house_category'],
                    'tel'=>$data['tel'],
                    'detail_img'=>$data['detail_img']
                ];
            }else{
                OutputDebugString('uptown_id_3',$uptown_id);
                $addData = [
                    'pano_id' => $data['pano_id'],
                    'pano_guid' => $pano['guid'],
                    'province_id' => $data['province_id'],
                    'city_id' => $data['city_id'],
                    'district_id' => $data['district_id'],
                    'uptown_id' => $uptown_id,
                    'member_id' => $this->member_id,
                    'create_time' => $nowtime,
                    'modify_time' => $nowtime,
                    'type' => $type
                ];
            }


            $res = M("house_resources")->add($addData);
            OutputDebugString_DB('add123');
            if($res){
                $result['code'] = 200;
                $result['msg'] = '';
            }else{
                $result['code'] = 400;
                $result['msg'] = '房源信息保存失败';
            }
            $this->ajaxReturn($result);

        }
    }

    //修改
    public function edit(){
        if ($_POST['dopost'] == "edit"){
            $data = I('post.');

            $result = [];
            $type = $data['type'];
            if( $type == '2'){
                foreach ($data as $key => $value){
                    if(empty($value) && $key != 'house_equip' && $key != 'img' && $key != 'category' && $key != 'content' && $key != 'detail_address'){
                        $result['code'] = 400;
                        $result['msg'] = '必填信息不能为空';
                        $this->ajaxReturn($result);
                    }
                }
            }
            if ($type == '1'){
                if(empty($data['type']) || empty($data['province_id']) ||
                    empty($data['city_id']) || empty($data['district_id'])
                    || empty($data['xiaoqu'])){
                    $result['code'] = 400;
                    $result['msg'] = '必填信息不能为空';
                    $this->ajaxReturn($result);
                }
            }

            //判断小区是否存在
            $region = M('region')->where(['id'=>$data['region_id']])->find();
            $arr = explode(',',$data['center']);

            $regionData = [
                'name'=>$data['xiaoqu'],
                'center'=>empty($data['center']) ? '' : $data['center'],
                'longitude'=>empty($arr) ? 0 : $arr[0],
                'latitude'=>empty($arr) ? 0 : $arr[1],
                'img'=>empty($data['img']) ? '' : $data['img'],
                'category'=>empty($data['category']) ? '' : $data['category'],
                'content'=>empty($data['content']) ? '' : $data['content']
            ];

            $uptown_id = $region['id'];
            M('region')->where(['id'=>$uptown_id])->save($regionData);
            OutputDebugString_DB('edit');
            $nowtime = date('Y-m-d H:i:s');

            if($type == '2') {
                $house_equip = implode(',', $_POST['house_equip']);
                $addData = [
                    'house_title' => $data['house_title'],
                    'pano_id' => $data['pano_id'],
                    'pano_guid' => $data['guid'],
                    'province_id' => $data['province_id'],
                    'city_id' => $data['city_id'],
                    'district_id' => $data['district_id'],
                    'uptown_id' => $uptown_id,
                    'building_no' => $data['lhbianhao'],
                    'door_card' => $data['menpai'],
                    'center' => $data['center'],
                    'detail_address' => $data['detail_address'],
                    'lease_money' => $data['lease_money'],
                    'house_desc' => $data['house_desc'],
                    'house_type' => $data['house_type'],
                    'house_direction' => $data['house_direction'],
                    'uptown_type' => $data['uptown_type'],
                    'member_id' => $this->member_id,
                    'lease_type' => $data['lease_type'],
                    'house_equip' => $house_equip,
                    'house_size' => $data['house_size'],
                    'house_img' => $data['house_img'],
                    'create_time' => $nowtime,
                    'modify_time' => $nowtime,
                    'type' => $type,
                    'category'=>$data['house_category'],
                    'tel'=>$data['tel'],
                    'detail_img'=>$data['detail_img']
                ];
            }else{
                $addData = [
                    'pano_id' => $data['pano_id'],
                    'pano_guid' => $data['guid'],
                    'province_id' => $data['province_id'],
                    'city_id' => $data['city_id'],
                    'district_id' => $data['district_id'],
                    'uptown_id' => $uptown_id,
                    'member_id' => $this->member_id,
                    'create_time' => $nowtime,
                    'modify_time' => $nowtime,
                    'type' => $type
                ];
            }

            $res = M("house_resources")->where(['id'=>$data['detail_id']])->save($addData);

            if($res){
                $result['code'] = 200;
                $result['msg'] = '';
            }else{
                $result['code'] = 400;
                $result['msg'] = '房源信息保存失败';
            }
            $this->ajaxReturn($result);
        }
    }

    //修改房源信息
    public function addOrEdit(){
        $pano_id = intval($_REQUEST['pano_id']);

        //查询房屋类型
        require_once APP_PATH . 'Common/Constants.php';
        $key = 'admin_house_type';
        $houseTypeList = M('commonality as com1')//->cache($key, 3600)
                            ->join('left join pano_commonality as com2 on com1.pid = com2.id')
                            ->where(['com2.name' => Constants::$HOUSER_TYPE])
                            ->field('com1.id, com1.name')->select();

        $this->assign('houseTypeList', $houseTypeList);

        $key = 'admin_house_desc';
        $houseDescList = M('commonality as com1')//->cache($key, 3600)
                            ->join('left join pano_commonality as com2 on com1.pid = com2.id')
                            ->where(['com2.name' => Constants::$HOUSER_DESC])
                            ->field('com1.id, com1.name')->select();
        $this->assign('houseDescList', $houseDescList);

        $key = 'admin_house_direction';
        $houseDirectionList = M('commonality as com1')//->cache($key, 3600)
                            ->join('left join pano_commonality as com2 on com1.pid = com2.id')
                            ->where(['com2.name' => Constants::$HOUSER_DIRECTION])
                            ->field('com1.id, com1.name')->select();
        $this->assign('houseDirectionList', $houseDirectionList);

        $key = 'admin_lease_method';
        $leaseMethodList = M('commonality as com1')//->cache($key, 3600)
                            ->join('left join pano_commonality as com2 on com1.pid = com2.id')
                            ->where(['com2.name' => Constants::$HOUSER_LEASE_METHOD])
                            ->field('com1.id, com1.name')->select();
        $this->assign('leaseMethodList', $leaseMethodList);

        $key = 'admin_uptown_type';
        $uptownTypeList = M('commonality as com1')//->cache($key, 3600)
                            ->join('left join pano_commonality as com2 on com1.pid = com2.id')
                            ->where(['com2.name' => Constants::$HOUSER_UPTOWN_TYPE])
                            ->field('com1.id, com1.name')->select();
        $this->assign('uptownTypeList', $uptownTypeList);

        $key = 'admin_house_equip';
        $houseEquipList = M('commonality as com1')//->cache($key, 3600)
                            ->join('left join pano_commonality as com2 on com1.pid = com2.id')
                            ->where(['com2.name' => Constants::$HOUSER_HOUSE_EQUIP])
                            ->field('com1.id, com1.name')->select();
        $this->assign('houseEquipList', $houseEquipList);

        //查询数据
        $houseResource = M('house_resources')->where("pano_id='{$pano_id}'")
                            ->find();
        if(empty($houseResource)){
            $this->display('add');
        }else{
            //查询小区名称
            $uptown = M('region')->where(['id' => $houseResource['uptown_id']])->find();

            $this->assign('uptown', $uptown);
            $this->assign('detail', $houseResource);
            $this->display('edit');
        }

    }

    //生成访问编号
    private function createHouseNo($district_id){
        //查询合作编号
        $memberInfo = M("Member")->field('cooperate_no')->where(array("id"=>$this->member_id))->find();

        //查询区号和识别符
        $districtInfo = M('region')->where(['id'=>$district_id])->field('citycode, idcode')->find();

        //编号生成规则  合作编号+区域编码+区域标识符+随机生成的6位数字
        $house_no_suffix = $memberInfo['cooperate_no'] . $districtInfo['citycode'] . $districtInfo['idcode'];

        do{
            //生随机6位编号
            $randNum = mt_rand(100000, 999999);
            $house_no =  $house_no_suffix . $randNum;

            //判断编号是否存在
            $count = M("house_resources")->where(['house_no' => $house_no])->count('id');
        }while($count > 0);

        return $house_no;
    }
}