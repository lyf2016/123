<?php

namespace Home\Controller;

use Think\Controller;

class singlePageController extends Controller {

    private $teacher_model, $teacher_positionmodel;
    protected $config;

    public function __construct() {
        parent::__construct();
        $this->teacher_model = M('Teacher');
        $this->teacher_position_model = M('TeacherPosition');
    }

    /**
     * 学校领导详情主页面
     */
    public function schoolLeader() {
        $map['status'] = 1;
        $map['is_leader'] = 1;
        $list = $this->teacher_model->where($map)->order(array("position" => "asc"))->select();
        //老师职位显示 . 取,1,2,里面的值
        foreach ($list as &$val) {
            $position = trim($val['position'], ',');
            $where_position['id'] = array('in', $position);
            $position_name_list = $this->teacher_position_model->where($where_position)->getField('position_name', true);
            $val['position_name'] = implode(' ', $position_name_list);
        }
        unset($val);
               
        $position_name_list = $this->teacher_position_model->where('id AND status=1')->order(array("id" => "asc"))->select();
        //dump($position_name_list);
        $this->assign('position', $position);
        $this->assign('position_name_list', $position_name_list);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 学校领导个人详情
     */
    public function leaderDetail() {
        $id = I('get.id', '', 'int');
        if (empty($id)) {
            $this->error('内部错误!');
        }
        $map['status'] = 1;
        $list = $this->teacher_model->where($map)->find($id);
        $this->assign('list', $list);
        $this->display();
    }

}
