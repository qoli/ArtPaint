<?php

// Qoli Wong _@2010
// Use for First install app.

$Sql = new Sql();

$Table = 'project';
$Rows = array(
    'UDID' => 'varchar(128)',
    'name' => 'varchar(64)',
    'clock' => 'date',
    'author' => 'varchar(128)',
    'remarks' => 'text',
    'part' => 'text'
);
$Sql->CreatTable($Table, $Rows, 1);
$Sql->SqlExec("ALTER TABLE `project` ADD UNIQUE (`UDID`);");
$Sql->SqlExec("ALTER TABLE `project` ADD INDEX ( `UDID` );");

$Table = 'category';
$Rows = array(
    'name' => 'varchar(64)'
);
$Sql->CreatTable($Table, $Rows, 1);

$Table = 'category_relationship';
$Rows = array(
    'category_id' => 'varchar(128)',
    'project_udid' => 'varchar(128)'
);
$Sql->CreatTable($Table, $Rows, 1);

$Table = 'images';
$Rows = array(
    'project_udid' => 'varchar(128)', //登入名
    'url' => 'varchar(256)',
    'name' => 'varchar(32)',
    'num' => 'varchar(32)',
    'remarks' => 'text'
);
$Sql->CreatTable($Table, $Rows, 1);

$DataArray = array(
    'name' => 'UI'
);
$Sql->Insert_ByArray('category', $DataArray);
$DataArray = array(
    'name' => 'PRINTED'
);
$Sql->Insert_ByArray('category', $DataArray);
$DataArray = array(
    'name' => 'ILLUSTRATION'
);
$Sql->Insert_ByArray('category', $DataArray);
$DataArray = array(
    'name' => 'LOGO'
);
$Sql->Insert_ByArray('category', $DataArray);
$DataArray = array(
    'name' => 'WEB'
);
$Sql->Insert_ByArray('category', $DataArray);

echo 'Done';
