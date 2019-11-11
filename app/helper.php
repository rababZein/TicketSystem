<?php
if(!function_exists('getStrBefore')) {
    function getStrBefore($sub, $str) {
        return substr($str, 0, strpos($str, $sub));
    }
}
?>