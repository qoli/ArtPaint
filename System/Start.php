<?php

/**
 * 引导文件，启动 AirPlant 环境
 * Author Qoli Wong
 * .2010.
 */
global $Sql;
global $Param; //URL 参数
global $View, $Model; //视图和模型交换变量
$Param = array();
$View = array();
$Model = array();

//PATH_INFO 初始化
if (!isset($_SERVER['PATH_INFO'])) {
    $_SERVER['PATH_INFO'] = 'Home';
}
$Param = explode('/', $_SERVER['PATH_INFO']);
if ($Param[0] == 'Home') {
    $Param[1] = 'Home';
}
$Param[0] = count($Param);

//核心库载入
require_once _ROOT . 'System/Library/Sql' . EXT; //SQL Class
$Sql = new Sql();
require_once _ROOT . 'System/Library/MainFunction' . EXT; // Funs
require_once _ROOT . 'System/Library/ExceptionEx' . EXT; // Error Ex Class
require_once _ROOT . 'System/Plus/Plus' . EXT;

//install 程式
if ($Param[1] == 'install') {
    require_once _ROOT . 'System/' . 'Initialization/' . 'Initialization' . EXT; //框架初始化
    require_once _ROOT . _APP . 'Config/' . 'install' . EXT; //程序初始化
    exit;
}

//启动程序
require_once _ROOT . _APP . 'Config/' . 'StartApp' . EXT; //连接数据库
//载入程式
if ($Param[1] != "") {
    Load($Param[1], 'Control');
} else {
    _Refresh(_URL);
}
