<?php
/**
 +------------------------------------------------------------------------------
 * cUrl class
 +------------------------------------------------------------------------------
 * @author guanxuejun
 +------------------------------------------------------------------------------
 */
namespace Org\Net;
/**
 * cURL 工具类
 * 做了简单的封装，直接静态方法调用，无须实例化
 * @author guanxuejun
 *
 */
class Curl {

    /**
     +----------------------------------------------------------
     * GET 方式打开远程 url 地址
     * 2014-11-09 增加对 HTTPS 协议支持
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $url 远程地址
     * @param string $timeout 运行超时限制
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    static public function get($url, $timeout) {
    	$ssl = substr($url, 0, 8) == 'https://' ? true : false;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	if ($ssl) {
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
    	};
    	$r = curl_exec($ch);
    	$info = curl_getinfo($ch);
    	curl_close($ch);
    	return array('info' => $info, 'result' => $r);
    }
    
    static public function post($url, $header, $body, $timeout) {
    	$ssl = substr($url, 0, 8) == 'https://' ? true : false;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    	if ($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_POST, true);           //POST方式
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);    //POST数据
    	if ($ssl) {
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
    	};
    	$r = curl_exec($ch);
    	$info = curl_getinfo($ch);
    	curl_close($ch);
    	return array('info' => $info, 'result' => $r);
    }
	
};
