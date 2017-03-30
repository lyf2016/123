<?php

namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->display();
    }

    public function loginAction() {
        $name = I('post.name', '', 'string');
        $pwd = I('post.pwd', '', 'string');

        if ($name == '' || $pwd == '') {
            $this->ajaxReturn(array('result' => 2, 'message' => '用户名密码不能为空！'));
            exit;
        }

        $account = C('ACCOUNT');
        if ($name != $account['NAME'] || $pwd != $account['PASSWD']) {
            $this->ajaxReturn(array('result' => 3, 'message' => '用户名或者密码错误！'));
            exit;
        }

        if ($name == $account['NAME'] && $pwd == $account['PASSWD']) {
            $_SESSION['username'] = $name;
            $_SESSION['expire'] = time() + C('EXPIRE');
            $this->ajaxReturn(array('result' => 1, 'message' => '登录成功!'));
            exit;
        }
    }

    public function logout() {
        session_unset();
        $this->redirect('Login/index');
    }

}
