<?php

namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index() {
        $url = U('Home/Index/formView','','',TRUE );
        $this->assign('url', $url);
        $this->display();
    }
    
    /**
     * 验证码
     */
    public function verifyCode(){
        $config = array();
        $config['fontSize'] = 24;
        $config['codeSet']  = '0123456789abcdefghijk0123456789lmnopqrstuvwxyz';
        $config['length']   = 6;
        $config['expire']   = 300;
        $config['useNoise'] = FALSE;
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
    
    /**
     * 表单页
     */
    public function formView(){
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz!@#$%^&*():><?';
        $code = '';
        for($i = 0; $i<=6; $i++){
            $code .= substr($str, rand(0, strlen($str)), 1);
        }
        $_SESSION['code'] = $code;
        $this->assign('code', $code);
        $this->display();
    }
    
    /**
     * 表单提交
     */
    public function formPost(){
        writeLog(print_r($_POST, TRUE));
        if(!IS_POST || $_POST['verify_code'] != $_SESSION['code']){
            $this->error('非法请求！');
            exit;
        }
        $code = trim(I('post.code'));
        $verify = new \Think\Verify();
        $code_result = $verify->check($code);
        if(!$code_result){
            $this->error('验证码错误！');
            exit;
        }
        $save_data = array();
        $school_name = I('post.school_name', '', 'string');
        if($school_name == ''){
            $this->error('请填写学校名称！');
        }
        $save_data['school_name'] = $school_name;
        
        $student_name = I('post.student_name', '', 'string');
        if($student_name == ''){
            $this->error('请填写您的姓名！');
        }
        $save_data['student_name'] = $student_name;
        
        $sex = I('post.sex', 1, 'int');
        $save_data['sex'] = $sex;
        
        $nation = I('post.nation', '', 'string');
        if($nation == ''){
            $this->error('请填写民簇！');
        }
        $save_data['nation'] = $nation;
        
        //缺生日
        $age_year = I('post.age_year', '', 'string');
        if(!strtotime($age_year)){
            $this->error('请选择出生年份！');
        }
        $age_month = I('post.age_month', '', 'int');
        if($age_month > 12){
            $this->error('请选择出生月份！');
        }
        $save_data['birth'] = $age_year.'-'.$age_month;
        
        $phone = I('post.phone', '', 'int');
        if($phone == ''){
            $this->error('请填写手机号码！');
        }
        if(!checkPhone($phone)){
            $this->error('请检查手机号码格式！');
        }
        $save_data['mobile'] = $phone;
        $exist_id = M('Apply')->where(array('mobile'=>$phone, 'student_name'=>$student_name))->getField('id');
        if($exist_id > 0){
            $this->error('已存在相同数据！');
        }
        
        $is_league = I('post.is_league', 2, 'int');
        $save_data['is_league_members'] = $is_league;
        
        $duty = I('post.duty', '', 'string');
        if($duty == ''){
            $this->error('请填写担任职务！');
        }
        $save_data['duty'] = $duty;
        
        $health = I('post.health', '', 'string');
        if($health == ''){
            $this->error('请填写健康状况！');
        }
        $save_data['health'] = $health; 
        
        $address = I('post.address', '', 'string');
        if($address == ''){
            $this->error('请填写家庭地址！');
        }
        $save_data['family_address'] = $address;
        
        $junior_two_middle = I('post.junior_two_middle', 0, 'int');
        if($junior_two_middle == 0){
            $this->error('请填写初二第二学期期中成绩！');
        }
        $junior_two = array();
        $junior_two['middle'] = $junior_two_middle;
        
        $junior_two_end = I('post.junior_two_end', 0, 'int');
        if($junior_two_end == 0){
            $this->error('请填写初二第二学期期末成绩！');
        }
        $junior_two['end'] = $junior_two_end;
        $save_data['junior_two_score'] = json_encode($junior_two);
        
        $junior_three_middle = I('post.junior_three_middle', 0, 'int');
        if($junior_three_middle == 0){
            $this->error('请填写初三第一学期期中成绩！');
        }
        $junior_three = array();
        $junior_three['middle'] = $junior_three_middle;
        
        $junior_three_end = I('post.junior_three_end', 0, 'int');
        if($junior_three_end == 0){
            $this->error('请填写初三第一学期期末成绩！');
        }
        $junior_three['end'] = $junior_three_end;
        $save_data['junior_three_score'] = json_encode($junior_three);
        
        $math = I('post.math', '', 'int');
        if($math == ''){
            $this->error('请填写初三第一次县模拟考数学成绩！');
        }
        $save_data['math'] = $math;
        
        $english = I('post.english', '', 'int');
        if($english == ''){
            $this->error('请填写初三第一次县模拟考英语成绩！');
        }
        $save_data['english'] = $english;
        
        $chinese = I('post.chinese', '', 'int');
        if($chinese == ''){
            $this->error('请填写初三第一次县模拟考语文成绩！');
        }
        $save_data['chinese'] = $chinese;
        
        $physical = I('post.physical', '', 'int');
        if($physical == ''){
            $this->error('请填写初三第一次县模拟考物理成绩！');
        }
        $save_data['physical'] = $physical;
        
        $chemistry = I('post.chemistry', '', 'int');
        if($chemistry == ''){
            $this->error('请填写初三第一次县模拟考语文成绩！');
        }
        $save_data['chemistry'] = $chemistry;
        
        $total = I('post.total', '', 'int');
        if($total == ''){
            $this->error('请填写初三第一次县模拟考总成绩！');
        }
        $save_data['total'] = $total;
        
        $grade_ranking = I('post.grade_ranking', 0, 'int');
        if($grade_ranking == 0){
            $this->error('请填写初三第一次县模拟考年级排名成绩！');
        }
        $save_data['grade_ranking'] = $grade_ranking;
        
        $city_ranking = I('post.city_ranking', 0, 'int');
        if($city_ranking > 0){
            $save_data['city_ranking'] = $city_ranking;
        }
        
        $learn_resume_date  = $_POST['learn_resume_date'];
        $learn_resume_unit  = $_POST['learn_resume_unit'];
        $learn_resume_prove = $_POST['learn_resume_prove'];
        if(($learn_resume_date[0] == '' && $learn_resume_date[1] == '' && $learn_resume_date[2] == '')
            || ($learn_resume_unit[0] == '' && $learn_resume_unit[1] == '' && $learn_resume_unit[2] == '')
            || ($learn_resume_prove[0] == '' && $learn_resume_prove[1] == '' && $learn_resume_prove[2] == '')){
            $this->error('请填写学习简历！');
        }
        $save_data['learn_resume'] = json_encode(array('date'=>$learn_resume_date, 'unit'=>$learn_resume_unit, 'prove'=>$_POST['learn_resume_prove']));
        
        if(!empty($_POST['honor_name']) || !empty($_POST['honor_unit']) || !empty($_POST['honor_date'])){
            $honor_temp = array();
            $honor_temp['name'] = $_POST['honor_name'];
            $honor_temp['unit'] = $_POST['honor_unit'];
            $honor_temp['date'] = $_POST['honor_date'];
            $save_data['honor'] = json_encode($honor_temp);
        }
        
        if(!empty($_POST['specialty_name']) 
                || !empty($_POST['specialty_unit']) 
                || !empty($_POST['specialty_prize']) 
                || !empty($_POST['specialty_date'])){
            $specialty_temp = array();
            $specialty_temp['name'] = $_POST['specialty_name'];
            $specialty_temp['unit'] = $_POST['specialty_unit'];
            $specialty_temp['prize'] = $_POST['specialty_prize'];
            $specialty_temp['date'] = $_POST['specialty_date'];
            $save_data['specialty'] = json_encode($specialty_temp);
        }
        $save_data['create_time'] = date('Y-m-d H:i:s');
        $new_id = M('Apply')->add($save_data);
        if($new_id){
            $this->success('报名成功！', '', '', 2);
        }else{
            writeLog(M()->_sql());
            $this->error('报名失败，请联系管理员！');
        }
    }
}
