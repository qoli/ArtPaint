<?php

/**
 * Description of Exception
 *
 * @author Qoli Wong
 */
class ExceptionEx extends Exception {

    public function Msg($param) {
        echo '<br><b>ERROR:</b><hr>';
        dump($param,'Param');
        $e = $this->getTrace();
        dump($e,'Trace');
        echo '<hr>';
    }

}
?>
