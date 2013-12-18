<?php

/**
 * Sql Class
 * @version 2011.8.16 第二次更新
 * @author Qoli Wong
 */
class Sql {

    private $SqlLink;

    /**
     * 连接到数据库,并且选择指定的数据库名称
     * @param string $server
     * @param string $username
     * @param string $password
     * @param string $db_name
     * @return string 
     */
    function Connect($server, $username, $password, $db_name) {
        @$link = mysql_connect($server, $username, $password);
        if (!$link) {
            die('Mysql Connect Error: [ ' . mysql_error() . " ]<br>");
        }

        $this->UTF8();

        @$ok = mysql_select_db($db_name);
        if (!$ok) {
            die("Mysql Choose Database Error: [ " . mysql_error() . " ] <br>");
        }

        $this->SqlLink = $link;
        return $link;
    }

    /**
     * 获得连接
     * @return sql_link sql 连接句柄
     */
    function GetLink() {
        return $this->SqlLink;
    }

    /**
     * 连接数据库,但是不选择指定的数据库,仅用于初始化数据库
     * @param string $server 
     * @param string $username
     * @param string $password
     * @return string 
     */
    function ConnectWithoutChoose($server, $username, $password) {
        $link = mysql_connect($server, $username, $password);
        if (!$link) {
            die('<b>| INSTALL |</b> Mysql Connect Error: [ ' . mysql_error() . " ]<br>");
        } else {
            echo '<b>| INSTALL |</b> CONECT ' . $server . ' - Success<br>';
        }
        $this->UTF8();
        return $link;
    }

    /**
     * 选择数据库
     * @param string $DATABASE 数据库
     * @return bool 真假
     */
    function ChooseDatabase($DATABASE) {
        @$ok = mysql_select_db($DATABASE);
        if (!$ok) {
            die("<b>| INSTALL |</b> Mysql Choose Database Error: [ " . mysql_error() . " ] <br>");
        } else {
            echo "<b>| INSTALL |</b> CHOOSE $DATABASE - Success<br>";
        }
        return $ok;
    }

    /**
     * 创建数据库
     * @param string $DATABASE
     * @return bool 
     */
    function CreatDatabase($DATABASE) {
        $ok = mysql_query("CREATE DATABASE $DATABASE");

        if ($ok) {
            echo "<b>| INSTALL |</b> CTEAR DATABASE $DATABASE - Success<br>";
        } else {
            echo "<b>| INSTALL |</b> CTEAR DATABASE $DATABASE - False<br>";
        }

        return $ok;
    }

    /**
     * 删除数据库
     * @param string $DATABASE
     * @return bool 
     */
    function DropDatabase($DATABASE) {
        $ok = mysql_query("DROP DATABASE $DATABASE");

        if ($ok) {
            echo "<b>| INSTALL |</b> DROP DATABASE $DATABASE - Success<br>";
        } else {
            echo "<b>| INSTALL |</b> DROP DATABASE $DATABASE - False<br>";
        }

        return $ok;
    }

    /**
     * 创建表 
     * @param string $Table 表
     * @param string $Cols 有什么栏目,$Cols必须为数组,传入格式 $ID['int'] $列名称['值类型']
     * @param string $IsDrop 是否删除,当为 0 时候则删除再创建
     */
    function CreatTable($Table, $Cols, $IsDrop = 0) {
        if ($IsDrop != 0) {
            $mysql_command = "DROP TABLE IF EXISTS $Table";
            $result = $this->SqlExec($mysql_command) or
                    die("error:" . mysql_error() . "<br>");
        }
        $mysql_command = "CREATE TABLE $Table";
        $mysql_command = $mysql_command . '(';
        $mysql_command = $mysql_command . 'id int auto_increment primary key,';
        $Max = count($Cols) - 1;
        $n = 0;
        $RowName = array_keys($Cols);
        while ($n < $Max) {
            $mysql_command = $mysql_command . $RowName[$n] . ' ' . $Cols[$RowName[$n]] . $this->UTF8_Field($Cols[$RowName[$n]]) . ',';
            $n = $n + 1;
        }
        $mysql_command = $mysql_command . $RowName[$Max] . ' ' . $Cols[$RowName[$Max]] . $this->UTF8_Field($Cols[$RowName[$Max]]) . '';
        $mysql_command = $mysql_command . ')';
        $result = $this->SqlExec($mysql_command);
        $mysql_command = "ALTER TABLE `$Table` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin";
        $result = $this->SqlExec($mysql_command);
        if ($result) {
            echo '<b>| INSTALL |</b> TABALE ' . $Table . '.<br/>';
        }
    }

    /**
     * 获得受到
     * @return type 
     */
    function get_affrows() {
        return mysql_affected_rows($this->GetLink());
    }

    /**
     * 简单获得整表
     * @param type $Table 表名称
     * @param type $Rows
     * @param type $SortBy
     * @param type $Desc_FALSE
     * @param type $return_Array_true
     * @param type $LIMIT 0,30 或 FALSE
     * @return boolean 返回数据或错误
     */
    function Get($Table, $Rows, $SortBy = 'id', $Desc_FALSE = FALSE, $return_Array_true = TRUE, $LIMIT = FALSE) {
        $Table = mysql_real_escape_string($Table);
        $Rows = mysql_real_escape_string($Rows);
        $SortBy = mysql_real_escape_string($SortBy);
        $Desc_FALSE = mysql_real_escape_string($Desc_FALSE);

        if ($LIMIT == FALSE) {
            $LIMIT = '';
        } else {
            $LIMIT = 'LIMIT ' . $LIMIT;
        }

        if ($Desc_FALSE) {
            $Sql_command = "SELECT $Rows FROM `$Table` ORDER BY $SortBy DESC $LIMIT";
        } else {
            $Sql_command = "SELECT $Rows FROM `$Table` ORDER BY $SortBy $LIMIT";
        }
        
        $r = $this->SqlExec($Sql_command);
        if ($r == FALSE) {
            return FALSE;
        } else {
            $o = $this->SqlArray($r);
            if ($return_Array_true == FALSE) {
                $o = $o[0][$Row];
            }
            return $o;
        }
    }

    /**
     * 简单查询
     * @param string $Table 表名称
     * @param string $Col 栏目名称
     * @param string $Where 查找的栏目
     * @param string $WhereVaule 其内容
     * @param string $return_Array_true 是否以数组返回数据,如果$Row设定为多栏，则$return_Array_true必须为TRUE。
     * @param string $Desc_FALSE 是否倒序排列
     * @return bool+string 当找不到对应数据时候,返回 False ,否则,返回数据
     */
    function GetOne($Table, $Col, $Where, $WhereVaule, $return_Array_true = TRUE, $Desc_FALSE = FALSE, $ORDERBY = 'id') {
        $Col = mysql_real_escape_string($Col);
        $Table = mysql_real_escape_string($Table);
        $Where = mysql_real_escape_string($Where);
        $WhereVaule = mysql_real_escape_string($WhereVaule);

        if ($Desc_FALSE == TRUE) {
            $Sql = "SELECT $Col FROM $Table WHERE $Where = '$WhereVaule' ORDER BY $ORDERBY DESC";
        } else {
            $Sql = "SELECT $Col FROM $Table WHERE $Where = '$WhereVaule'";
        }
        _MarkLog($Sql);
        $r = $this->SqlExec($Sql);
        if ($r == FALSE) {
            return FALSE;
        } else {
            $o = $this->SqlArray($r);
            if ($return_Array_true == FALSE) {
                $o = $o[0][$Col];
            }
            return $o;
        }
    }

    /**
     * 运行 Sql 查询命令
     * @param string $SqlCommand_Str Sql 命令
     * @param string $Param_Array 启用Sql的参数模式查询(没有测试)
     * @return Array 
     */
    function Quert($SqlCommand_Str, $Param_Array = '') {
        if ($Param_Array != '') {
            $i = 1;
            foreach ($Param_Array as $v) {
                $SqlCommand_Str = str_replace('?', mysql_real_escape_string($v), $SqlCommand_Str, $i);
                $i++;
            }
        }
        $r = $this->SqlExec($SqlCommand_Str);
        $o = $this->SqlArray($r);
        return $o;
    }

    /**
     * 插入数据,以一个数组
     * @param string $Table
     * @param string $DataArray
     * @return string 
     */
    function Insert_ByArray($Table, $DataArray) {
        $DataArray = $this->ArrayToSafearray($DataArray);

        $Cols = '';
        $Data = '';
        foreach ($DataArray as $key => $value) {
            $Cols .= $key . ',';
            $Data .= "'" . $value . "'" . ',';
        }
        $Cols = substr($Cols, 0, -1);
        $Data = substr($Data, 0, -1);
        return $this->Insert_ByString($Table, $Cols, $Data);
    }

    /**
     * 插入数据,以字符串方法
     * @param string $Table
     * @param string $ColsString
     * @param string $DataString
     * @param bool $unSafe 是否基于不安全模式
     * @return array 
     */
    function Insert_ByString($Table, $ColsString, $DataString) {

        //判断是插入单个 Col 还是插入多个 Cols
        if (!strpos($ColsString, ',')) {

            $e = explode("''", "'$DataString'");
            if (count($e) == 1) {
                $mysql_command = "INSERT INTO `$Table`(`$ColsString`) VALUES('$DataString')";
            } else {
                $mysql_command = "INSERT INTO `$Table`(`$ColsString`) VALUES($DataString)";
            }
        } else {
            $mysql_command = "INSERT INTO `$Table`($ColsString) VALUES($DataString)";
        }
        $result = $this->SqlExec($mysql_command);
        $r = ($result != FALSE) ? TRUE : FALSE;
        return $r;
    }

    /**
     * Replace数据,以一个数组
     * @param string $Table
     * @param string $DataArray
     * @return string 
     */
    function Replace_ByArray($Table, $DataArray) {
        $DataArray = $this->ArrayToSafearray($DataArray);

        $Cols = '';
        $Data = '';
        foreach ($DataArray as $key => $value) {
            $Cols .= $key . ',';
            $Data .= "'" . $value . "'" . ',';
        }
        $Cols = substr($Cols, 0, -1);
        $Data = substr($Data, 0, -1);
        return $this->Replace_ByString($Table, $Cols, $Data);
    }

    /**
     * Replace数据,以字符串方法
     * @param string $Table
     * @param string $ColsString
     * @param string $DataString
     * @param bool $unSafe 是否基于不安全模式
     * @return array 
     */
    function Replace_ByString($Table, $ColsString, $DataString) {

        //判断是插入单个 Col 还是插入多个 Cols
        if (!strpos($ColsString, ',')) {
            $mysql_command = "REPLACE INTO `$Table`($ColsString) VALUES($DataString)";
        } else {
            $mysql_command = "REPLACE INTO `$Table`($ColsString) VALUES($DataString)";
        }
        $result = $this->SqlExec($mysql_command);
        $r = ($result != FALSE) ? TRUE : FALSE;
        return $r;
    }

    /**
     * 更新数据
     * @param string $Table 表名称
     * @param string $Col 更新对应的 Row 名称
     * @param string $Data 对应的 Row 内容
     * @param string $Where 查找的Row
     * @param string $WhereVaule 对应的值
     * @return bool 
     */
    function Update_ByString($Table, $Col, $Data, $Where, $WhereVaule, $FromArray = FALSE) {
        if ($FromArray == TRUE) {
            $String = '';
            foreach ($Data as $key => $value) {
                $String .= (string) $key . '=' . "'" . (string) $value . "'" . ',';
            }
            $String = substr($String, 0, -1);
        } else {
            if (strpos($Col, ',')) {
                $Col = explode(',', $Col);
                $Data = explode(',', $Data);
                if (count($Col) != count($Data)) {
                    //Cols 和 Data 的数量无法对应
                    return FALSE;
                }

                $Data = array_combine($Col, $Data);
                $this->Update_ByArray($Table, $Data, $Where, $WhereVaule);
            } else {
                $String = $Col . '=' . $Data;
            }
        }

        $mysql_command = "UPDATE `$Table` SET $String WHERE $Where = '$WhereVaule' ";
        $result = $this->SqlExec($mysql_command);
        if (!$result) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 更新数据基于数组
     * @param string $Table 表
     * @param array $DataArray 数组
     * @param string $Where 位置
     * @param string $WhereVaule 位置对应值
     * @return bool 返回
     */
    function Update_ByArray($Table, $DataArray, $Where, $WhereVaule) {
        $DataArray = $this->ArrayToSafearray($DataArray);
        return $this->Update_ByString($Table, '', $DataArray, $Where, $WhereVaule, TRUE);
    }

    /**
     * 删除记录
     * @param string $Table 表名称
     * @param string $Where Row 名称
     * @param string $WhereVaule 对应 Row 的内容
     * @return bool 
     */
    function Delete($Table, $Where, $WhereVaule) {
        $mysql_command = "DELETE FROM `$Table` WHERE $Where = '$WhereVaule'";
        $result = $this->SqlExec($mysql_command);
        if (!$result) {
            return FALSE;
        } else {
            $mysql_command = "OPTIMIZE TABLE `$Table`";
            $this->SqlExec($mysql_command);
            return TRUE;
        }
    }

    /**
     * 数据库解释
     * @param string $Sql
     * @return bool+string 
     */
    function SqlExec($Sql) {
        if (DEBUG_DB) {
            echo $Sql . '<br/>';
        }
        $Result = mysql_query($Sql);
        if (!$Result || $Result == FALSE) {
            $ok = FALSE;
        } else {
            $ok = $Result;
        }
        return $ok;
    }

    /**
     * 返回数据库的输出内容,以PHP数组格式
     * @param sqlobject $SqlResult
     * @return array 
     */
    function SqlArray($SqlResult) {
        $fetch = array();
        while ($row = mysql_fetch_array($SqlResult, MYSQL_ASSOC)) {
            $fetch[] = $row;
        }
        return $fetch;
    }

    /**
     * 设置数据库读写为 UTF-8
     */
    function UTF8() {
        $this->SqlExec("SET NAMES 'UTF8'");
    }

    /**
     * UTF-8 格式化
     * @param string $FieldName
     * @return string 
     */
    function UTF8_Field($FieldName) {
        $needle = array(
            1 => 'varchar',
            2 => 'char',
            3 => 'text'
        );
        $n = 3;
        while ($n) {
            $ok = strstr($FieldName, $needle[$n]);
            if ($ok) {
                return " CHARACTER SET utf8 COLLATE utf8_bin";
            }
            $n = $n - 1;
        }
    }

    /**
     * 数组转义到安全可储存到Sql的数组
     * @param array $Array 输入数组
     * @return array 输出数组
     */
    function ArrayToSafearray($Array) {
        foreach ($Array as $key => $value) {
            $k[] = $this->StringToSafestring($key);
            $v[] = $this->StringToSafestring($value);
        }

        $Array = array_combine($k, $v);
        return $Array;
    }

    /**
     * 字符串转义到安全可储存到Sql的字符串
     * @param String $String 输入
     * @return String 输出
     */
    function StringToSafestring($String) {
        $String = stripcslashes($String);
        $String = addslashes($String);
        return $String;
    }

}

?>
