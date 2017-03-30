<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 后台管理的主要操作类
 * 老师管理
 * @author wangsong <wangsong@gmail.com>
 * @copyright 上海熔意网络科技有限公司 <http://www.txrt.cn>
 *
 */
class TeacherController extends BaseController {

    private $teacher_model, $teacher_position_model;

    public function __construct() {
        parent::__construct();
        $this->teacher_model = M('Teacher');
        $this->teacher_position_model = M('TeacherPosition');
    }

    public function index() {
        $this->display();
    }

    //老师查看
    public function schoolTeacher() {
        $post_data = array();
        $post_data['status'] = array('neq', 3);
        //搜索
        if (IS_POST) {
            $name = trim(I('post.name', '', 'string'));
            if ($name != '') {
                $post_data['name'] = array('like', '%' . $name . '%');
            }

            $position = I('post.position', 0, 'int');
            if ($position != 0) {
                $post_data['position'] = array('like', '%,' . $position . ',%');
            }
            $this->assign('post_data', $_POST);
        }
        $count = $this->teacher_model->where($post_data)->count();
        $Page = new \Think\Page($count, C('PAGE_COUNT'));
        $show = $Page->show();
        $teacher_list = $this->teacher_model->where($post_data)->order('position asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $position_list = $this->teacher_position_model->where('id AND status=1')->select();
        //老师职位显示 . 取,1,2,里面的值
        foreach ($teacher_list as &$val) {
            $position = trim($val['position'], ',');
            $where_position['id'] = array('in', $position);
            $position_name_list = $this->teacher_position_model->where($where_position)->getField('position_name', true);
            $val['position_name'] = implode(',', $position_name_list);
        }
        unset($val);
        $this->assign('teacher_list', $teacher_list); // 赋值数据集
        $this->assign('position_list', $position_list); //老师职位查询
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }

    //老师添加
    public function addTeacher() {
        $position_list = $this->teacher_position_model->where('id AND status=1')->select();
        $this->assign('position_list', $position_list); //老师职位查询
        $this->display();
    }

    //老师添加
    public function addTeacherIng() {
        $data = I('post.');
        if ($data['name'] == '' || $data['desc'] == '' || $_FILES['img_path']['name'] == '' || $data['img_position'] == '' || $data['position'] == '' || $data['exper'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }      
        //老师职位拼接入库
        $position_array = array();
        $position_array = implode(',', $data['position']);
        $map['name'] = $data['name'];
        $map['img_position'] = $data['img_position'];
        $map['position'] =$position_array;
        $map['create_time'] = date("Y-m-d H:i:s");
        $map['exper'] = $data['exper'];
        $map['is_leader'] = $data['is_leader'];
        $map['status'] = $data['status'];
        //图片上传
        $pic = $this->_upPic('img_path');

        if (!$pic) {
            $this->error("上传失败");
        } else {
            $map['img_path'] = $pic['savepath'] . $pic['savename'];
        }
        $map['content'] = $data['desc'];
        $add = $this->teacher_model->add($map);
        if ($add) {
            $this->success('老师添加成功', U('Teacher/schoolTeacher'));
        } else {
            $this->success('老师添加失败');
        }
    }

    //老师修改
    public function editTeacher() {
        $id = I('get.id', '', 'int');
        $list = $this->teacher_model->where(array('id' => $id))->find();
        $this->assign('list', $list);
        $condition = array();
        $condition['status'] = 1;
        $category_list = $this->teacher_position_model->field('position_name, id')->where($condition)->select();
        $position_list = $this->teacher_position_model->where('id AND status=1')->select();
        $this->assign('position_list', $position_list); //老师职位查询
        $this->assign('category_list', $category_list);
        $this->display();
    }

    //老师修改
    public function editTeacherIng() {
        $data = I('post.');
        $id = $data['id'];
        unset($data['id']);
        if ($data['name'] == '' || $data['desc'] == '' || $data['img_position'] == '' || $data['position'] == '' || $data['exper'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }
        if ($_FILES['title_pic']['name'] == '') {
            if ($data['pic'] == '') {
                $this->error('没有上传图片');
                return false;
            }
            $map['img_path'] = $data['pic'];
        } else {
            $pic = $this->_upPic('title_pic');
            if (!$pic) {
                $this->error("上传失败");
            } else {
                $map['img_path'] = $pic['savepath'] . $pic['savename'];
            }
        }
        //查询旧图 删除图片
        $list = $this->teacher_model->where(array('id' => $id))->find();
        $oldImg = $list['img_path'];
        $oldImgpath = $oldImg;
         //老师职位拼接入库
        $position_array = array();
        $position_array = implode(',', $data['position']);
        
        $map['name'] = $data['name'];
        $map['img_position'] = $data['img_position'];
        $map['position'] = $position_array;
        $map['exper'] = $data['exper'];
        $map['modify_time'] = date("Y-m-d H:i:s");
        $map['content'] = $data['desc'];
        $map['is_leader'] = $data['is_leader'];
        $map['status'] = $data['status'];
        if ($this->teacher_model->where(array('id' => $id))->save($map)) {
            $this->success('修改成功', U('Teacher/schoolTeacher'));
            @unlink($oldImgpath);
        } else {
            $this->error('内容未修改');
        }
    }

    //删除老师
    public function delTeacher() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $map['status'] = 3;
        $list = $this->teacher_model->where("id=$id")->save($map);
        if ($list) {
            $this->success('删除成功', U('Teacher/schoolTeacher'));
        } else {
            $this->error('删除失败!');
        }
    }

    //教师职位查看
    public function position() {
        $condition = array();
        $condition['status'] = array('neq', 3);
        $position_list = $this->teacher_position_model->field('id, position_name,status')->where($condition)->select();
        $this->assign('position_list', $position_list);
        $this->display();
    }

    //添加教师职位
    public function AddPosition() {
        $this->display();
    }

    //教师职位添加
    public function DoAddPosition() {
        $data = I('post.');
        if ($data['position_name'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }
        $map['position_name'] = $data['position_name'];
        $map['status'] = $data['status'];
        $map['create_time'] = date("Y-m-d H:i:s");
        $add = $this->teacher_position_model->add($map);
        if ($add) {
            $this->success('添加成功', U('Teacher/position'));
        } else {
            $this->success('添加失败');
        }
    }

    //教师职位修改
    public function editPosition() {
        $id = I('get.id', '', 'int');
        $condition = array();
        $condition['id'] = $id;
        $position_list = $this->teacher_position_model->where($condition)->find($id);
        $this->assign('position_list', $position_list);
        $this->assign('id', $id);
        $this->display();
    }

    //修改教师职位
    public function DoEditPosition() {
        $data = I('post.');
        $id = I('get.id', '', 'int');
        $map['position_name'] = $data['position_name'];
        $map['status'] = $data['status'];
        $map['modify_time'] = date("Y-m-d H:i:s");
        $list = $this->teacher_position_model->where(array('id' => $id))->save($map);
        if ($list) {
            $this->success('修改成功', U('Teacher/position'));
        } else {
            $this->error('内容未修改');
        }
    }

    //删除教师职位
    public function delPosition() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $map['status'] = 3;
        $list = $this->teacher_position_model->where("id=$id")->save($map);
        if ($list) {
            $this->success('删除成功', U('Teacher/position'));
        } else {
            $this->error('删除失败!');
        }
    }

    /**
     * 文件上传
     * $name 为图片字段名称
     * */
    private function _upPic($name) {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = C('UPLOAD_FILE_MAX_BYTES'); // 设置附件上传大小
        $upload->exts = C('UPLOAD_FILE_TYPE'); // 设置附件上传类型
        $upload->rootPath = 'Public/'; // 设置附件上传根目录
        $upload->savePath = 'upload/'; // 设置附件上传(子)目录
        // 上传文件
        return $upload->uploadOne($_FILES["$name"]);
    }

}
