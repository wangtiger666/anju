<?php

class KindediterWidget extends Widget {
    public function render($data) {
        $appbase = "/".APP_NAME;
        $html =  "<script type=\"text/javascript\" src=\"$appbase/Tools/kindeditor/kindeditor-min.js\"></script>\r\n";
        $html .= "<link href=\"$appbase/Tools/kindeditor/themes/default/default.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n";
        return $html;
    }
}

?>
