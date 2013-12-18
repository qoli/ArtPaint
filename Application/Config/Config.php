<?

//DBConfig
if ($_SERVER['SERVER_ADDR'] == '0.0.0.0') {
    //Server

} else {
    //Localhost
    define('db_host', 'localhost');
    define('db_name', 'root');
    define('db_pass', '1234567890');
    define('db_db', 'dbname');
}

//AppSet
define('app_title', '库倪先生');
define('app_varsion', '2.0');
