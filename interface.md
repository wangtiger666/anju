#### 登陆页地址：/LoginReg
#### 登出页地址：/LoginReg/loginout
#### 个人信息：/Account/info
#### 我的关注：/Account/myFollow
#### 我的浏览记录：/Account/myHistory
#### 我的消息：/Account/myMessage
#### 我的预订：/Account/myBook
#### 我的租房：/Account/myRenting


## code_type 短信类型 1：手机注册 2：手机绑定 3：找回密码  4:手机登陆 5:手机解绑


## 所有的接口都是POST
## 发送短信接口

	/Api/sendPhoneMessage

参数

	phone 手机号
    code_type 短信类型 1：手机注册 2：手机绑定 3：找回密码  4:手机登陆


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "当天发送量已达到上限"
    }


##手机验证码登陆接口

	/LoginReg/sendPhoneMessage

参数

    phone 手机号
    code  验证码


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##账号秘密登陆接口

	/LoginReg/accountPwdLogin

参数

    account 账号
    password  秘密


返回json格式数据

    code 200是成功，其他都是失败


    {
        "code": 400,
        "msg": "登陆失败"
    }


##账号验证码秘密注册接口

	/LoginReg/phoneVerifycodeReg

参数

    phone 手机号
    code  验证码
    password  秘密


返回json格式数据

    code 200是成功，其他都是失败


    {
        "code": 400,
        "msg": "登陆失败"
    }

##手机验证码验证接口

	/Api/phoneVerifycodeValid

参数

    phone 手机号
    code  验证码
    code_type  验证类型  1：手机注册 2：手机绑定 3：找回密码  4:手机登陆


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

#接口登陆状态下才能使用
##移出绑定手机

	/Account/removeBindingPhone

参数

    phone 手机号
    code  验证码


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##绑定手机

	/Account/bindingPhone

参数

    phone 手机号
    code  验证码


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##修改密码

	/Account/changePwd

参数

    phone 手机号
    password  旧密码
    newpwd  新密码
    newpwd_check  新密码


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##实名认证

	/Account/realNameAuth

参数

    real_name 真实姓名
    id_card  身份证


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##修改信息

	/Account/updateInfo

参数

    nick_name 真实姓名
    contact_phone  联系手机
	contact_qq  联系qq
	contact_weixin  联系微信
	contact_period  联系时段
	contact_time    联系时间


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##上传头像

	/Account/uploadHeadPic

参数

    head_img 上传的文件（支持格式'jpg', 'gif', 'png', 'jpeg'）
    old_head_img  旧头像（非必须）


返回json格式数据

    code 200是成功，其他都是失败

失败信息
    {
        "code": 400,
        "msg": "登陆失败"
    }

成功信息
    {
        "code": 200,
        "msg": "http://qj.com/Account/uploadHeadPic" //返回头像路径
    }


##浏览记录

	/House/history

参数

    house_resouces_id 房源id


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##关注记录

	/House/follow

参数

    house_resouces_id 房源id


返回json格式数据

    code 200是成功，其他都是失败

    {
        "code": 400,
        "msg": "登陆失败"
    }

##获取父级和孩子节点信息

	/HouseResources/getParentAndChildInfo

参数

    name 名称
    show_parent 是否返回父节点数据  1不返回 2返回  默认是1
    show_childList 是否返回子节点数据  1不返回 2返回  默认是1


返回json格式数据

    成功
	{
	    "code": 200,
	    "parent_data": { //父节点数据
	        "id": "1120",
	        "name": "福建省",
	        "longitude": "119.295143",// 经度
	        "latitude": "26.100779" //维度
	    },
	    "data": {
	        "id": "1171",
	        "name": "厦门市",
	        "longitude": "118.11022",
	        "latitude": "24.490474"
	    }
	}
	失败
	 {
        "code": 400,
        "msg": "登陆失败"
    }

##获取子节点数据信息

	/HouseResources/getChildrenInfo

参数

	pid 父节点
	name 名称  非必填


返回json格式数据

	成功
	{
        "code": 200,
        "dataList": {
            "regionList": [
                {
                    "id": "1176",
                    "name": "思明区",
                    "longitude": "118.087828",
                    "latitude": "24.462059"
                },
                {
                    "id": "1175",
                    "name": "湖里区",
                    "longitude": "118.10943",
                    "latitude": "24.512764"
                },
            ],
            "childList": [
                {
                    "id": "3687",
                    "pid": "1176",
                    "name": "软件园二期",
                    "longitude": "118.182595",
                    "latitude": "24.488049",
                    "house_num": "1"
                },
                {
                    "id": "3683",
                    "pid": "1175",
                    "name": "大唐世家",
                    "longitude": "118.121768",
                    "latitude": "24.507946",
                    "house_num": "0"
                },
                {
                    "id": "3690",
                    "pid": "1175",
                    "name": "大唐世家一期",
                    "longitude": "118.122242",
                    "latitude": "24.508298",
                    "house_num": "1"
                }
            ]
        }
    }
	失败
	 {
        "code": 400,
        "msg": "登陆失败"
    }


##获取详细的房源信息--小区查询

	/HouseResources/getHouseList

参数

    name 小区名称
	pid 父节点
	page 页数 默认第一页
	page_size 分页大小默认5条
	uptown_show 是否返回小区信息，默认不返回 1:不返回小区信息  2：返回小区信息
	house_type 房屋类型  几房几厅  格式：类型id
	lease_money 租金    格式：类格式：0-500
	lease_type 租赁方式    格式：类型id
	uptown_type 小区类型    格式：类型id-类型id
	house_direction 朝向    格式：类型id-类型id
	house_equip 房屋设施    格式：冰箱-洗衣机


返回json格式数据

	成功
	{
	    "code": 200,
	    "uptown_data" :{ //小区信息
	        'id' : 1,
            'name' : 1, //小区名称
            'intro' : 1,//介绍
            'img' : 1, //图片
            'house_num' : 0,//房屋数
	    },
	    "house_list": [//返回多个子节点数据
	        {
	            "house_id": "1",
	            "pano_guid": "haogetaobaodian", //全景图唯一id
	            "house_title": "超娱网络",  //房屋标题
	            "house_img": "/uploads/station/1533799278438C.jpg",//房屋图片，需要加上域名
	            "house_type": "三房两厅", //房屋户型
	            "house_direction": "朝西",//朝向
	            "lease_type": "整租",//出租方式
	            "lease_money": "2000"//租金
	        }
	    ]
	}
	失败
	 {
        "code": 400,
        "msg": "登陆失败"
    }

##获取小区楼号及房源数

	/HouseResources/getBuildingData

参数

    name 小区名称
	pid 父节点


返回json格式数据

	成功
	{
	    "code": 200,
	    "data_list": [
	        {
	            "building_no": "望海路10号之四",
	            "longitude": "118.182616",
	            "latitude": "24.488044",
	            "house_num": "1"
	        }
	    ]
	}
	失败
	 {
        "code": 400,
        "msg": "登陆失败"
    }
    
    
##获取房源信息属性

	/HouseResources/getHouseInfoAttr

参数



返回json格式数据

	成功
	[
        {
            "pid": "3",
            "pname": "房屋户型",
            "childList": [
                {
                    "id": "4",
                    "name": "一房一厅"
                },
                {
                    "id": "5",
                    "name": "两方一厅"
                },
                {
                    "id": "6",
                    "name": "三房两厅"
                }
            ]
        }
    ]
	失败
	 {
        "code": 400,
        "msg": "登陆失败"
    }
    
   ##根据搜索条件获取相关的房源信息
   
   	/HouseResources/getUptownInfo
   
   参数
   
    city_id 城市id
   	district_id 区域id
   	page 页数 默认第一页
   	page_size 分页大小默认5条
   	uptown_show 是否返回小区信息，默认不返回 1:不返回小区信息  2：返回小区信息
   	house_type 房屋类型  几房几厅  格式：类型id
   	lease_money 租金    格式：类格式：0-500
   	lease_type 租赁方式    格式：类型id
   	uptown_type 小区类型    格式：类型id-类型id
   	house_direction 朝向    格式：类型id-类型id
   	house_equip 房屋装修   格式：类型id-类型i
   
   
   返回json格式数据
   
   	成功
   	{
        "code": 200,
        "uptown_data": [
            {
                "id": "3683",
                "name": "大唐世家",
                "intro": null,
                "img": null,
                "house_num": "1",
                "house_list": [
                    {
                        "house_id": "3",
                        "pano_guid": "52339890873d5376",
                        "house_title": "大唐世家在组",
                        "house_img": "/uploads/station/153535291626E4.png",
                        "house_type": "一房一厅",
                        "house_direction": "朝东",
                        "lease_type": "整租",
                        "lease_money": "2200"
                    }
                ]
            },
            {
                "id": "3687",
                "name": "软件园二期",
                "intro": null,
                "img": null,
                "house_num": "1",
                "house_list": [
                    {
                        "house_id": "1",
                        "pano_guid": "haogetaobaodian",
                        "house_title": "超娱网络",
                        "house_img": "/uploads/station/1533799278438C.jpg",
                        "house_type": "三房两厅",
                        "house_direction": "朝西",
                        "lease_type": "整租",
                        "lease_money": "2000"
                    }
                ]
            },
            {
                "id": "3690",
                "name": "广兴新村",
                "intro": null,
                "img": null,
                "house_num": "1",
                "house_list": [
                    {
                        "house_id": "2",
                        "pano_guid": "251eec6186842d96",
                        "house_title": "广兴新村",
                        "house_img": " ",
                        "house_type": "一房一厅",
                        "house_direction": "朝东",
                        "lease_type": "整租",
                        "lease_money": "2200"
                    }
                ]
            }
        ]
    }
   	失败
   	 {
           "code": 400,
           "msg": "登陆失败"
       }
       
   ##根据小区名称获取相关信息
      
        /HouseResources/getUptownInfoById
      
      参数
      
        city_id 城市id
        name 小区名称
      
      
      返回json格式数据
      
        成功
        {
            "code": 200,
            "uptown_data": {
                "name": "大唐世家",
                "longitude": "118.121768",
                "latitude": "24.507946",
                "house_num": "1",
                "area_name": "湖里区",
                "area_id": "1175"
            }
        }
        失败
         {
              "code": 400,
              "msg": "登陆失败"
          }
          
   ##根据经纬度获取小区相关信息
         
           /HouseResources/getUptownInfoByCircular
         
         参数
         
           longitude 经度
           latitude 纬度
           radius 半径
         
         
         返回json格式数据
         
           成功
           {
               "code": 200,
               "town_data": [
                   {
                       "name": "软件园二期",
                       "longitude": "118.182595",
                       "latitude": "24.488049",
                       "house_num": "1",
                       "area_id": "1176",
                       "area_name": "思明区"
                   },
                   {
                       "name": "1111111",
                       "longitude": "118.182241",
                       "latitude": "24.483907",
                       "house_num": "0",
                       "area_id": "3687",
                       "area_name": "软件园二期"
                   },
                   {
                       "name": "大唐世家",
                       "longitude": "118.121768",
                       "latitude": "24.507946",
                       "house_num": "1",
                       "area_id": "1175",
                       "area_name": "湖里区"
                   },
                   {
                       "name": "广兴新村",
                       "longitude": "118.113120",
                       "latitude": "24.503262",
                       "house_num": "1",
                       "area_id": "1175",
                       "area_name": "湖里区"
                   }
               ]
           }
           失败
            {
                 "code": 400,
                 "msg": "登陆失败"
             }