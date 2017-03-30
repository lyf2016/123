<?php

namespace Home\Controller;

use Think\Controller;

class DemoController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        //获取当前id=2的数据
        $User = M('apply');
        $map['id'] = '2';
        $User->where($map)->find(); // 读取当前数据对象
        $data = $User->data();
        //print_r($data);
        dump($data);
        $this->display();
        //返回的数据
//        array (size = 25)
//        'id' => string '2' (length = 1)
//        'school_name' => string '学校' (length = 6)
//        'student_name' => string '王崧' (length = 6)
//        'sex' => string '1' (length = 1)
//        'nation' => string '汗' (length = 3)
//        'birth' => string '2006-7' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string '学生' (length = 6)
//        'health' => string '良好' (length = 6)
//        'family_address' => string '上海市啊时间啊好精神' (length = 30)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '600' (length = 3)
//        'grade_ranking' => string '12' (length = 2)
//        'city_ranking' => string '12' (length = 2)
//        'learn_resume' => string '{"date":["1990-12","",""],"unit":["\u4e0a\u6d77","",""],"prove":["\u554a\u65f6\u95f4","",""]}' (length = 93)
//        'honor' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'specialty' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'create_time' => string '2016-05-30 13:34:52' (length = 19)
    }

//系统提供了assign方法对模板变量赋值
//$this->assign('name',$value);// 下面的写法是等效的
//$this->name = $value;
    //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=index

    public function save() {
        $User = M("aa"); // 实例化User对象
        // 要修改的数据对象属性赋值
        $data['name'] = '552';
        $data['password'] = '8474';
        $User->where('id=12')->save($data); // 根据条件更新记录
        // print_r($data);
        dump($data);
        $this->display();
        //返回的数据
//        array (size = 2)
//        'name' => string '552' (length = 3)
//        'password' => string '8474' (length = 4)
    }

//地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=save

    public function add() {
        $User = M("aa"); // 实例化User对象
        $data['id'] = '203';
        $data['name'] = '4506';
        $data['password'] = '4056';
        //$User->add($data);
        //print_r($data);
        dump($data);
        $this->display();
        //返回的数据
//        array (size = 3)
//        'id' => string '203' (length = 3)
//        'name' => string '4506' (length = 4)
//        'password' => string '4056' (length = 4)
    }

    //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=add

    public function addALL() {
        $user = M('aa');
        $data = array(
            array('id' => '1203', 'name' => '221', 'password' => '4551'),
            array('id' => '4506', 'name' => '331', 'password' => '455'),
            array('id' => '7809', 'name' => '441', 'password' => '4555'),
        );
        dump($data);
        $this->display();
        //返回的数据
//        array (size = 3)
//        0 =>
//        array (size = 3)
//        'id' => string '1203' (length = 4)
//        'name' => string '221' (length = 3)
//        'password' => string '4551' (length = 4)
//        1 =>
//        array (size = 3)
//        'id' => string '4506' (length = 4)
//        'name' => string '331' (length = 3)
//        'password' => string '455' (length = 3)
//        2 =>
//        array (size = 3)
//        'id' => string '7809' (length = 4)
//        'name' => string '441' (length = 3)
//        'password' => string '4555' (length = 4)
    }

    //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=addALL

    public function find() {
        $User = M("apply"); // 实例化User对象
        // 查找sex值为1,name值为think的用户数据 
        $data = $User->where('sex="1" AND school_name="asu"')->find();
        dump($data);
        $this->display();
        //返回的数据
//        array (size = 25)
//        'id' => string '4' (length = 1)
//        'school_name' => string 'asu' (length = 3)
//        'student_name' => string 'wnagsong' (length = 8)
//        'sex' => string '1' (length = 1)
//        'nation' => string 'han' (length = 3)
//        'birth' => string '2003-5' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string '学生' (length = 6)
//        'health' => string '两哈' (length = 6)
//        'family_address' => string '上海市' (length = 9)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '120' (length = 3)
//        'grade_ranking' => string '600' (length = 3)
//        'city_ranking' => string '12' (length = 2)
//        'learn_resume' => string '{"date":["1990-01","",""],"unit":["218","",""],"prove":["12","",""]}' (length = 68)
//        'honor' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'specialty' => string '{"name":["","",""],"unit":["","",""],"prize":["","",""],"date":["","",""]}' (length = 74)
//        'create_time' => string '2016-05-31 19:34:25' (length = 19)
//        value2
    }

    //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=find

    public function findALL() {
        $User = M("apply"); // 实例化User对象
        // 查找sex值为1的用户数据 以创建时间排序 返回5条数据
        $list = $User->where('sex=1')->order('create_time')->limit(5)->select();
        $this->assign('list', $list);
        //print_r($list);
        dump($list);
        $this->display();

        //返回的数据
//        array (size = 5)
//        0 =>
//        array (size = 25)
//        'id' => string '1' (length = 1)
//        'school_name' => string '学校' (length = 6)
//        'student_name' => string '王崧' (length = 6)
//        'sex' => string '1' (length = 1)
//        'nation' => string '汗' (length = 3)
//        'birth' => string '1987-3' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string '学生' (length = 6)
//        'health' => string '良好' (length = 6)
//        'family_address' => string '上海市啊黄金时间' (length = 24)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '600' (length = 3)
//        'grade_ranking' => string '12' (length = 2)
//        'city_ranking' => string '12' (length = 2)
//        'learn_resume' => string '{"date":["1991-01","",""],"unit":["\u5b66\u6821","",""],"prove":["\u6c14\u6e29","",""]}' (length = 87)
//        'honor' => string '{"name":["\u8363\u8a89","",""],"unit":["\u4e0a\u6d77","",""],"date":["1990-01","",""]}' (length = 86)
//        'specialty' => string '{"name":["\u8363\u8a89","",""],"unit":["\u4e0a\u6d77","",""],"date":["1990-01","",""]}' (length = 86)
//        'create_time' => string '2016-05-30 13:25:23' (length = 19)
//        1 =>
//        array (size = 25)
//        'id' => string '2' (length = 1)
//        'school_name' => string '学校' (length = 6)
//        'student_name' => string '王崧' (length = 6)
//        'sex' => string '1' (length = 1)
//        'nation' => string '汗' (length = 3)
//        'birth' => string '2006-7' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string '学生' (length = 6)
//        'health' => string '良好' (length = 6)
//        'family_address' => string '上海市啊时间啊好精神' (length = 30)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '600' (length = 3)
//        'grade_ranking' => string '12' (length = 2)
//        'city_ranking' => string '12' (length = 2)
//        'learn_resume' => string '{"date":["1990-12","",""],"unit":["\u4e0a\u6d77","",""],"prove":["\u554a\u65f6\u95f4","",""]}' (length = 93)
//        'honor' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'specialty' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'create_time' => string '2016-05-30 13:34:52' (length = 19)
//        2 =>
//        array (size = 25)
//        'id' => string '4' (length = 1)
//        'school_name' => string 'asu' (length = 3)
//        'student_name' => string 'wnagsong' (length = 8)
//        'sex' => string '1' (length = 1)
//        'nation' => string 'han' (length = 3)
//        'birth' => string '2003-5' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string '学生' (length = 6)
//        'health' => string '两哈' (length = 6)
//        'family_address' => string '上海市' (length = 9)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '120' (length = 3)
//        'grade_ranking' => string '600' (length = 3)
//        'city_ranking' => string '12' (length = 2)
//        'learn_resume' => string '{"date":["1990-01","",""],"unit":["218","",""],"prove":["12","",""]}' (length = 68)
//        'honor' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'specialty' => string '{"name":["","",""],"unit":["","",""],"prize":["","",""],"date":["","",""]}' (length = 74)
//        'create_time' => string '2016-05-31 19:34:25' (length = 19)
//        3 =>
//        array (size = 25)
//        'id' => string '6' (length = 1)
//        'school_name' => string 'ash' (length = 3)
//        'student_name' => string 'wang' (length = 4)
//        'sex' => string '1' (length = 1)
//        'nation' => string 'han ' (length = 4)
//        'birth' => string '2003-5' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string 'sjah' (length = 4)
//        'health' => string 'asjhsj' (length = 6)
//        'family_address' => string 'hjhasjhj' (length = 8)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '120' (length = 3)
//        'grade_ranking' => string '12' (length = 2)
//        'city_ranking' => string '120102' (length = 6)
//        'learn_resume' => string '{"date":["12","",""],"unit":["12","",""],"prove":["12","",""]}' (length = 62)
//        'honor' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'specialty' => string '{"name":["","",""],"unit":["","",""],"prize":["","",""],"date":["","",""]}' (length = 74)
//        'create_time' => string '2016-05-31 19:37:33' (length = 19)
//        4 =>
//        array (size = 25)
//        'id' => string '7' (length = 1)
//        'school_name' => string 'sjsi' (length = 4)
//        'student_name' => string 'hajs' (length = 4)
//        'sex' => string '1' (length = 1)
//        'nation' => string 'han' (length = 3)
//        'birth' => string '2003-5' (length = 6)
//        'mobile' => string '13761130527' (length = 11)
//        'is_league_members' => string '2' (length = 1)
//        'duty' => string 'xuesheng' (length = 8)
//        'health' => string 'liasj' (length = 5)
//        'family_address' => string 'sahjshja' (length = 8)
//        'junior_two_score' => string '{"middle":120,"end":120}' (length = 24)
//        'junior_three_score' => string '{"middle":120,"end":120}' (length = 24)
//        'math' => string '120' (length = 3)
//        'english' => string '120' (length = 3)
//        'chinese' => string '120' (length = 3)
//        'physical' => string '120' (length = 3)
//        'chemistry' => string '120' (length = 3)
//        'total' => string '600' (length = 3)
//        'grade_ranking' => string '120' (length = 3)
//        'city_ranking' => string '1290' (length = 4)
//        'learn_resume' => string '{"date":["19","",""],"unit":["12","",""],"prove":["12","",""]}' (length = 62)
//        'honor' => string '{"name":["","",""],"unit":["","",""],"date":["","",""]}' (length = 55)
//        'specialty' => string '{"name":["","",""],"unit":["","",""],"prize":["","",""],"date":["","",""]}' (length = 74)
//        'create_time' => string '2016-05-31 19:39:38' (length = 19)
//        1:
//        2:
//        4:
//        6:
//        7:
    }

    //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=findALL

    public function del() {
        $User = M("aa"); // 实例化User对象
        $User->where('id=1')->delete(); // 删除id为1的用户数据
        $data = $User->where('id=1')->delete(); 
        dump($data);
        $this->display();
        //返回的数据
//       int 0
    }

    //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=del

    public function startTrans() {
        $User = M('aa'); //实例化Info对象
        $User->startTrans(); //启用事务
        $data['name'] = '52';
        $data['password'] = '874';
        $User->where('id=123')->save($data); // 根据条件更新记录
        // print_r($data);
        dump($data);

        //print_r($Info);
        //exit;
        if (操作成功) {
            // 提交事务
            $User->commit();
        } else {
            // 事务回滚
            $User->rollback();
        }
//返回的数据
//        array (size = 2)
//        'name' => string '52' (length = 2)
//        'password' => string '874' (length = 3)

        //地址栏：http://127.0.0.1:8889/index.php?m=Home&c=Demo&a=startTrans
    }

}
