<?php
// APP ROOT
defined("ROOT_PATH")
    or define("ROOT_PATH", realpath(dirname(__FILE__)));
    // echo ROOT_PATH;
//echo $root; //C:\xampp\htdocs\watchnow

defined("SMTP_PATH")
    or define("SMTP_PATH", realpath(dirname(__FILE__) . '/db'));
//
