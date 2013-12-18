<?php
// Qoli Wong _@2010

// TODO Session 继续完善
class Session {
    private $DBSID;
    private $SESSIONCODE;
    private $SESSIONID;
    private $SID;
    private $Sql;
    private $Table;
    function  __construct() {
        $this->Sql = new Sql();
        $this->Table = 'ap_session';
        $this->SID = 'sessionid';
        $this->SESSIONCODE = _SESSIONCODE;

        $SessionCode = md5($this->SESSIONCODE.time());
        if (!isset ($_COOKIE['SESSIONID'])) {
            $_COOKIE = '';
            setcookie('SESSIONID', $SessionCode,0);
        }

        $this->SESSIONID = $_COOKIE['SESSIONID'];
        $SessionIDinDB = $this->Sql->GetOne($this->Table, 'id', $this->SID, $this->SESSIONID);
        if ($SessionIDinDB == FALSE) {
            $this->Sql->Insert($this->Table, $this->SID, $this->SESSIONID);
        }
        $this->DBSID = $SessionIDinDB;
    }
    function Save($name,$data) {
        $Save[$name] = $data;
        $SaveString = ArrayToSrting($Save);
        $Before = $this->Sql->GetOne($this->Table, 'data', $this->SID, $this->SESSIONID);
        $BeforeString = ArrayToSrting($Before);
        $this->Sql->Update($this->Table, 'data', "'$BeforeString.$SaveString'", $this->SID, $this->SESSIONID);
    }
    function Load($name = '') {
        if ($name == '') {
            _echo("Hi! I am SESSION Check");
            $data = $this->Sql->GetOne($this->Table, 'data', $this->SID, $this->SESSIONID);
            $data = StringToArray($data);
            _echo($data);
        } else {
            $data = $this->Sql->GetOne($this->Table, 'data', $this->SID, $this->SESSIONID);
            $data = StringToArray($data);
            $o = $data[$name];
            return $o;
        }
    }
    function Drop() {
        //清空用户cookie
    }
}
?>
