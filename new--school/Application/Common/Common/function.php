<?php

/**
 * 日志书写
 * @staticvar string $__APP_LOG_PID__
 * @param type $content
 * @param type $level
 * @param type $log_name
 */
function writeLog($content, $level='', $log_name=''){
    static $__APP_LOG_PID__; // 进程号
    if (!$__APP_LOG_PID__) {
        $__APP_LOG_PID__ = '[PID:' . getmypid() . ']';
    }
    $first = '[PAGE:' . MODULE_NAME . DIRECTORY_SEPARATOR . CONTROLLER_NAME . DIRECTORY_SEPARATOR . ACTION_NAME . ']';
    $first.= '[IP:' . get_client_ip() . '][GET:' . $_SERVER['REQUEST_URI'] . '][ACTION:' . ACTION_NAME . ']';
    $host_name    = function_exists('gethostname') ? gethostname() : php_uname('n');
    $log_level = '[' . $hostName . ']' . $__APP_LOG_PID__ . $first . $level;
    
    if ($log_name == '') {
        $log_name = CONTROLLER_NAME;
    }
    $log_path = C('LOG_PATH').date('Y-m-d');
    if(!is_dir($log_path)){
        mkdir($log_path);
    }
    $destination = $log_path.'/TX_' . MODULE_NAME . '_' . $log_name;
    \Think\Log::write($content, $log_level, '', $destination);
}

/**
 * 检查手机号码
 * @param type $mobile
 * @return boolean
 */
function checkPhone($mobile = ''){
    if (!preg_match("/^1[34578][0-9]{9}$/", $mobile) || $mobile == '') {
        return false;
    }else{
        return true;
    }
}

?>