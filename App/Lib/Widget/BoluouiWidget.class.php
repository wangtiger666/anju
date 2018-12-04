<?php

class BoluouiWidget extends Widget{
    public function render($data) {
        $html = "";
        $appbase = "/".APP_NAME;
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/Public/common/boluoui/style/boluo.css\">\r\n";
        $html .= "<script type=\"text/javascript\" src=\"/Public/common/boluoui/js/boluo.js\"></script>\r\n";
        return $html;
    }
}

?>
