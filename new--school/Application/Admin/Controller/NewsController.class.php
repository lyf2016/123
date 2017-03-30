<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 后台管理的主要操作类
 * 新闻管理
 * @author wangsong <wangsong@gmail.com>
 * @copyright 上海熔意网络科技有限公司 <http://www.txrt.cn>
 *
 */
class NewsController extends BaseController {

    private $news_model, $news_category_model;

    public function __construct() {
        parent::__construct();
        $this->news_model = M('News');
        $this->news_category_model = M('NewsCategry');
    }

    public function index() {
        $this->display();
    }

    //新闻查看
    public function actione() {
        $post_data = array();
        $post_data['status'] = 1;
        //搜索
        if (IS_POST) {
            $title = trim(I('post.title', '', 'string'));
            if ($title != '') {
                $post_data['title'] = array('like', '%' . $title . '%');
            }

            $key_words = trim(I('post.key_words', '', 'string'));
            if ($key_words != '') {
                $post_data['key_words'] = array('like', '%' . $key_words . '%');
            }

            $news_category_id = I('post.news_category_id', '', 'int');
            if ($news_category_id != '') {
                $post_data['news_category_id'] = $news_category_id;
            }
            $this->assign('post_data', $_POST);
        }
        $count = $this->news_model->where($post_data)->count();
        $Page = new \Think\Page($count, C('PAGE_COUNT'));
        $show = $Page->show();
        $list = $this->news_model->where($post_data)->order('create_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $category_list = $this->news_category_model->where('id AND status=1')->select();
        $this->assign('category_list', $category_list);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('firstRow', $Page->firstRow);
        $this->display();
    }

    //新闻添加
    public function addactione() {
        $condition = array();
        $condition['status'] = 1;
        $category_list = $this->news_category_model->field('name, id')->where($condition)->select();
        $this->assign('category_list', $category_list);
        $this->display();
    }

    //新闻添加
    public function doaddactione() {
        $data = I('post.');
        if ($data['title'] == '' || $data['desc'] == '' || $_FILES['title_pic']['name'] == '' || $data['person'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }
        $map['title'] = $data['title'];
        $map['person'] = $data['person'];
        $map['news_category_id'] = $data['news_category'];
        $map['create_time'] = date("Y-m-d H:i:s");
        $map['summary'] = $data['summary'];
        $map['key_words'] = $data['key_words'];
        $map['recommend'] = $data['recommend'];
        $pic = $this->_upPic('title_pic');

        if (!$pic) {
            $this->error("上传失败");
        } else {
            $map['picture'] = $pic['savepath'] . $pic['savename'];
        }
        $map['content'] = $data['desc'];
        $add = $this->news_model->add($map);
        if ($add) {
            $this->success('文章发布成功', U('News/actione'));
        } else {
            $this->success('文章发布失败');
        }
    }

    //新闻修改
    public function editacti() {
        $id = I('get.id', '', 'int');
        $list = $this->news_model->where(array('id' => $id))->find();
        $this->assign('list', $list);
        $condition = array();
        $condition['status'] = 1;
        $category_list = $this->news_category_model->field('name, id')->where($condition)->select();
        $this->assign('category_list', $category_list);
        $this->display();
    }

    //新闻修改
    public function doeditacti() {
        $data = I('post.');
        $id = $data['id'];
        unset($data['id']);
        if ($data['title'] == '' || $data['desc'] == '' || $data['person'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }
        if ($_FILES['title_pic']['name'] == '') {
            if ($data['pic'] == '') {
                $this->error('没有上传图片');
                return false;
            }
            $map['picture'] = $data['pic'];
        } else {
            $pic = $this->_upPic('title_pic');
            if (!$pic) {
                $this->error("上传失败");
            } else {
                $map['picture'] = $pic['savepath'] . $pic['savename'];
            }
        }
        //查询旧图 删除图片
        $list = $this->news_model->where(array('id' => $id))->find();
        $oldImg = $list['picture'];
        $oldImgpath = $oldImg;
        $map['title'] = $data['title'];
        $map['content'] = $data['desc'];
        $map['person'] = $data['person'];
        $map['news_category_id'] = $data['news_category'];
        $map['modify_time'] = date("Y-m-d H:i:s");
        $map['summary'] = $data['summary'];
        $map['key_words'] = $data['key_words'];
        $map['recommend'] = $data['recommend'];
        if ($this->news_model->where(array('id' => $id))->save($map)) {
            $this->success('修改成功', U('News/actione'));
            @unlink($oldImgpath);
        } else {
            $this->error('内容未修改');
        }
    }

    //新闻查看
    public function detail() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('存在错误请检查');
        }
        $list = $this->news_model->where(array('id' => $id))->find();
        $this->assign('list', $list);
        $this->display();
    }

    //删除文章
    public function delarticle() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $map['status'] = 2;
        $list = $this->news_model->where("id=$id")->save($map);
        if ($list) {
            $this->success('删除成功', U('News/actione'));
        } else {
            $this->error('删除失败!');
        }
    }

    //分类查看
    public function category() { 
        $condition = array();
        $condition['status'] = 1;
        $category_list = $this->news_category_model->field('id, name,create_time')->where($condition)->select();
        $this->assign('category_list', $category_list);
        $this->display();
    }

    //分类添加新闻
    public function AddCategory() {
        $this->display();
    }

    //分类添加
    public function DoAddCategory() {
        $data = I('post.');
        if ($data['title'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }
        $map['name'] = $data['title'];
        $map['pid'] = 1;
        $map['create_time'] = date("Y-m-d H:i:s");
        $add = $this->news_category_model->add($map);
        if ($add) {
            $this->success('添加成功', U('News/category'));
        } else {
            $this->success('添加失败');
        }
    }

    //分类修改
    public function EditCategory() {
        $id = I('get.id', '', 'int');
        $condition = array();
        $condition['id'] = $id;
        $condition['status'] = 1;
        $category_name = $this->news_category_model->field('name')->where($condition)->select();
        $this->assign('category_name', $category_name[0]['name']);
        $this->assign('id', $id);
        $this->display();
    }

    //分类修改
    public function DoEditCategory() {
        $data = I('post.');
        $id = I('get.id', '', 'int');
        $map['name'] = $data['title'];
        $map['pid'] = 1;
        $map['status'] = 1;
        $map['modify_time'] = date("Y-m-d H:i:s");
        $list = $this->news_category_model->where(array('id' => $id))->save($map);
        if ($list) {
            $this->success('修改成功', U('News/category'));
        } else {
            $this->error('内容未修改');
        }
    }

    //删除分类
    public function DelCategory() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $map['status'] = 3;
        $list = $this->news_category_model->where("id=$id")->save($map);
        if ($list) {
            $this->success('删除成功', U('News/category'));
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
