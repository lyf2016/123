<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function __construct() {
        parent::__construct();
        if(!isset($_SESSION['username']) || $_SESSION['expire'] < time()){
            unset($_SESSION);
            redirect(U('Admin/Login/index'));
        }else{
            $_SESSION['expire'] = time() + C('EXPIRE');
        }
    }

}
