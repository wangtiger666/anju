<?php

class IndexAction extends AdminAction {

    function index() {
        $base = CC("web_root");
        
        $fatherhtml = '<li>会员信息管理</li>
                                <li class="rightb">系统参数管理</li>';
        $menuhtml = '<div class="menubox">
                                    <div class="cube">
                                        <div class="title">会员管理</div>
                                        <div class="body" style="display:block">
                                            <div class="link"><a href="'.$base.'/admin/user/index" target="main">会员帐号信息</a></div>
                                            <div class="link"><a href="'.$base.'/admin/message/index" target="main">会员站内消息</a></div>
                                        </div>
                                        <div class="shadow"></div>                                
                                    </div>
                                </div>
                                <div class="menubox">
                                    <div class="cube">
                                        <div class="title">系统设置</div>
                                        <div class="body" style="display:block">
                                            <div class="link"><a href="'.$base.'/admin/system/index" target="main">系统参数管理</a></div>
                                            <div class="link"><a href="'.$base.'/admin/control/index" target="main">管理员帐号管理</a></div>
                                            <div class="link"><a href="'.$base.'/admin/updata/index" target="main">系统版本升级</a></div>
                                            <div class="link"><a href="'.$base.'/admin/filemaster/index" target="main">在线文件管理</a></div>
                                        </div>
                                        <div class="shadow"></div>                                
                                    </div>
                                    <div class="cube">
                                        <div class="title">常用维护</div>
                                        <div class="body" style="display:block">
                                            <div class="link"><a href="'.$base.'/admin/database/index/type/export" target="main">数据库一键备份</a></div>
                                            <div class="link"><a href="'.$base.'/admin/database/index/type/import" target="main">数据库一键还原</a></div>
                                        </div>
                                        <div class="shadow"></div>                                
                                    </div>
                                </div>';
        
        $fatherhtml = creatMenuFather($this->menuFather);
        $menuhtml = creatMenuList($this->menuList);
        
        $this->assign('fatherhtml', $fatherhtml);
        $this->assign('menuhtml', $menuhtml);
        $this->display();
    }

}

?>
