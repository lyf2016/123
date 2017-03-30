<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->_getLink();
    }

    /**
     * ��������
     */
    private function _getLink() {
        $link = M('link');
        $link_list = $link->where('status=1')->select();
        $this->assign('link_list', $link_list);
    }
}
