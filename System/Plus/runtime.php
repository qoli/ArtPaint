<?php

/**
 * runtime
 * 运行时间测试 
 */
class runtime {

    var $StartTime = 0;
    var $StopTime = 0;

    function get_microtime() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }

    /**
     * 开始记录 
     */
    function start() {
        $this->StartTime = $this->get_microtime();
    }

    /**
     * 停止记录 
     */
    function stop() {
        $this->StopTime = $this->get_microtime();
    }

    /**
     * 获得花费时间
     * @return int 时间(毫秒数)
     */
    function spent() {
        return round(($this->StopTime - $this->StartTime) * 1000, 1);
    }

}