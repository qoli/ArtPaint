<?php

// Qoli Wong _@2010

echo "Your Config: ";
require_once _ROOT . _APP . 'Config/' . 'Config.php';
$server = db_host;
$username = db_name;
$password = db_pass;
$db_name = db_db;

$Sql->ConnectWithoutChoose($server, $username, $password);
$Sql->DropDatabase($db_name);
$Sql->CreatDatabase($db_name);
$Sql->ChooseDatabase($db_name);
?>
