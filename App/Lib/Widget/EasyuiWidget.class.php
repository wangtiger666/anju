<?php

class EasyuiWidget extends Widget{
    public function render($data) {
        $html = "";
        $appbase = "/".APP_NAME;
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/Public/common/easyui/themes/gray/easyui.css\">\r\n";
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/Public/common/easyui/themes/icon.css\">\r\n";
        $html .= "<script type=\"text/javascript\" src=\"/Public/common/easyui/jquery.easyui.min.js\"></script>\r\n";
        return $html;
    }
}

?>
