<?php

/*
 * ------------------
 * ArtPaint @2012
 * ------------------
 * Author Qoli Wong
 * E-Mail llqoli@Gmail.com
 * Home Page http://ArtPaint.llqoli.com
 * version 0.2
 * Last Update 24/03/2012
 */

//初始化
session_start();
error_reporting(E_ALL ^ E_NOTICE); //开发环境请输出全部错误

$Basepart = dirname(__FILE__) . '/';
$Subpart = 'Application/';
$AppUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';

define('_ROOT', $Basepart);
define('_APP', $Subpart);
define('_URL', $AppUrl);
define('EXT', '.php');
define('DEBUG_DB', FALSE);
define('_RunTime', FALSE);

if (_RunTime) {
    require_once _ROOT . 'System/Plus/runtime' . EXT;
    $rt = new runtime();
    $rt->start();
}

require_once 'System/Start' . EXT;

if (_RunTime) {
    $rt->stop();
    echo "页面执行时间: " . $rt->spent() . " 毫秒";
}