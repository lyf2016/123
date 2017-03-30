<?php

/**
 * Demo
 */

namespace Home\Controller;

use Think\Controller;

class DemoController extends BaseController {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Demo
     */
    public function index() {
        $one_arr = array(a, b, c, d, e);
        foreach ($one_arr as $key=>$val) {
            echo $key.'=>'.$val.'<br />';
        }
        
        echo '<br />===========================<br />';
        
        $two_arr = array(
            array(a,b,c), 
            array(d,e,f), 
            array(g,h,i),
            );
        foreach ($two_arr as $key=>$val) {
            foreach ($val as $k=>$v) {
                echo $k.'=>'.$v.'<br />';
            }
        }
        
        $this->display();
    }
    
    
}