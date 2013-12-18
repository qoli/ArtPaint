<?php

header('Content-Type:text/html;charset=UTF-8');
include_once _ROOT . _APP . 'Config/' . 'Config' . EXT;
$Sql->Connect(db_host, db_name, db_pass, db_db);
