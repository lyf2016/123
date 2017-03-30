<?php
/**
 * 首页
 */
namespace Admin\Controller;
use Think\Controller;

class IndexController extends BaseController {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 报名列表页
     */
    public function index(){
        $condition = array();
        if(IS_POST){
            $phone = trim(I('post.phone', '', 'int'));
            if(strlen($phone) > 11){
                $this->error('请检查手机号码！');
            }
            $condition['mobile'] = array('like','%'.$phone.'%');
            
            $student_name = trim(I('post.name', '', 'string'));
            if($student_name != ''){
                $condition['student_name'] = $student_name;
            }
            $this->assign('post_data', $_POST);
        }
        $apply_model = M('Apply');
        $count      = $apply_model->where($condition)->count();
        $page       = new \Think\Page($count,25);
        $show       = $page->show();
        $apply_list = $apply_model
            ->field('id, school_name, student_name, mobile, sex, create_time, birth, grade_ranking, city_ranking')
            ->where($condition)
            ->order('create_time desc')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $this->assign('apply_list', $apply_list);
        $this->assign('page', $show);
        $this->display();
    }
    
    /**
     * 报名详情
     */
    public function applyDetail(){
        $apply_id = I('get.id', 0, 'int');
        if($apply_id == 0){
            $this->error('参数错误！');
        }
        
        $apply_model = M('Apply');
        $detail = $apply_model->where(array('id'=>$apply_id))->find();
        $detail['age'] = explode('-', $detail['birth']);
        $detail['learn_resume'] = json_decode($detail['learn_resume'], TRUE);
        $detail['honor'] = json_decode($detail['honor'], TRUE);
        $detail['specialty'] = json_decode($detail['specialty'], TRUE);
        $detail['junior_two_score'] = json_decode($detail['junior_two_score'], TRUE);
        $detail['junior_three_score'] = json_decode($detail['junior_three_score'], TRUE);
        $this->assign('detail', $detail);
        $this->display();
    }
}
