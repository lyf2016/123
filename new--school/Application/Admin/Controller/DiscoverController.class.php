<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 后台管理的主要操作类
 * 发现模块管理页
 * @author wangsong <wangsong@gmail.com>
 * @copyright 上海熔意网络科技有限公司 <http://www.txrt.cn>
 *
 */
class DiscoverController extends BaseController {

    private $article_list_img_model, $article_model,$news_category_model;

    public function __construct() {
        parent::__construct();
        $this->article_list_img_model = M('ArticleListImg');
        $this->article_model = M('article');
        $this->news_category_model = M('NewsCategry');
    }

    /**
     * 首页发现模块列表头部图片管理
     */
    public function bannerList() {
        $condition = array();
        $condition['status'] = array('neq', 3);

        $count = $this->article_list_img_model->where($condition)->order('create_time desc')->count();
        $Page = new \Think\Page($count, C('PAGE_COUNT'));
        $show = $Page->show(); // 分页显示输出       

        $img_list = $this->article_list_img_model
                ->where($condition)
                ->order('create_time desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        $this->assign('img_list', $img_list);
        $this->assign('page', $show);
        $this->assign('firstRow', $Page->firstRow);
        $this->display();
    }

    /**
     * 首页发现模块列表头部图片添加
     */
    public function bannerAdd() {
        $this->display();
    }

    /**
     * 首页发现模块列表头部图片添加动作
     */
    public function bannerAddAct() {
        if (IS_POST) {
            $img_name = I('post.title', '', 'string');
            $img_status = I('post.status', 0, 'int');
            $img_type = I('post.banner_type', 0, 'int');
            if ($img_status == 0 || !in_array($img_type, array(1, 2, 3, 4, 5)) || $img_name == '') {
                $this->error('参数错误！');
                exit;
            }

            if ($_FILES['showimg']['error'] != 0) {
                $this->error('请上传图片');
                exit;
            }
            //启动事务：
            M()->startTrans();
            if ($img_status == 1) {
                $this->article_list_img_model->where(array('type' => $img_type, 'status' => 1))->save(array('status' => 2));
            }

            $img_service = D('Img', 'Service');
            $pic_path = $img_service->getUploadImgPath('showimg');

            $new_data = array();
            $new_data['img_path'] = $pic_path;
            $new_data['name'] = $img_name;
            $new_data['type'] = $img_type;
            $new_data['status'] = $img_status;
            $new_data['create_time'] = date('Y-m-d H:i:s');
            $new_data_id = $this->article_list_img_model->add($new_data);

            if ($new_data_id > 0) {
                M()->commit(); // 提交事务：
                $this->success('添加图片成功！', U('Admin/Discover/bannerList'));
            } else {
                M()->rollback(); //事务回滚：
                $this->error('添加图片失败');
            }
        }
    }

    /**
     * 首页发现模块列表头部图片管理
     */
    public function bannerEdit() {
        $banner_id = I('get.id', 0, 'int');
        $this->assign('list', $list);
        $condition = array();
        $condition['id'] = $banner_id;
        $banner_detail = $this->article_list_img_model->where($condition)->field('id, name, img_path, type, status'. '')->find();
        $this->assign('banner_detail', $banner_detail);
        $this->display();
    }

    //发现模块列表头部图片修改
    public function doeditbanner() {
        $data = I('post.');
        $id = $data['id'];
        unset($data['id']);
        if ($data['name'] == '' || !in_array($data['type'], array(1, 2, 3, 4, 5)) || $data['status'] == '') {
            $this->error('请完整填写信息!');
            return false;
        }

        if ($_FILES['showimg']['name'] == '') {
            if ($data['img_path'] == '') {
                $this->error('没有上传图片');
                return false;
            }
            $map['img_path'] = $data['img_path'];
        } else {
            $pic = $this->_upPic('showimg');
            if (!$pic) {
                $this->error("上传失败");
            } else {
                $map['img_path'] = $pic['savepath'] . $pic['savename'];
            }
        }

        //查询旧图 删除图片
        $list = $this->article_model->where(array('id' => $id))->find();
        $oldImg = $list['picture'];
        $oldImgpath = $oldImg;
        $map['name'] = $data['name'];
        $map['banner_type'] = $data['banner_type'];
        $map['status'] = $data['status'];
        $save = $this->article_list_img_model->where(array('id' => $id))->save($map);
        if ($save) {
            $this->success('修改成功', U('Discover/bannerList'));
            @unlink($oldImgpath);
        } else {
            $this->error('修改失败');
        }
    }

    //删除发现模块列表头部图片
    public function delimg() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $map['status'] = 3;
        $list = $this->article_list_img_model->where("id=$id")->save($map);
        if ($list) {
            $this->success('删除成功', U('Admin/Discover/bannerList'));
        } else {
            $this->error('删除失败!');
        }
    }

    public function index() {
        $this->display();
    }

    //文章查看
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

            $type = I('post.type', '', 'int');
            if ($type != '') {
                $post_data['type'] = $type;
            }
            $this->assign('post_data', $_POST);
        }
        $count = $this->article_model->where($post_data)->count();
        $Page = new \Think\Page($count, C('PAGE_COUNT'));
        $show = $Page->show();
        $list = $this->article_model->where($post_data)->order('create_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $category_list = $this->article_list_img_model->where('id AND status=1')->select();
        $this->assign('category_list', $category_list);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('firstRow', $Page->firstRow);
        $this->display();
    }

    //文章添加
    public function addactione() {
        $condition = array();
        $condition['status'] = 1;
        $list = $this->article_model->where($condition)->select();
        $category_list = $this->news_category_model->field('name, id')->where($condition)->select();
        $this->assign('list', $list);
        $this->assign('category_list', $category_list);
        $this->display();
    }

    //文章添加
    public function doaddactione() {
        $data = I('post.');
        if ($data['title'] == '' || $data['desc'] == '' || $_FILES['title_pic']['name'] == '' || $data['person'] == '' || !in_array($data['type'], array(1, 2, 3, 4))) {
            $this->error('请完整填写信息!');
            return false;
        }

        //判断图片是否在首页显示
        if ($data['recommend'] == 1) {
            if ($_FILES['large_pic']['name'] == ''&& $_FILES['small_pic']['name'] == '') {
                $this->error('首页图片必须选择一个格式!');
                return false;
            }
            //首页 640*360 大图上传
            if ($_FILES['large_pic']['name']) {
                $pic = $this->_upPic('large_pic');
                if (!$pic) {
                    $this->error("上传失败");
                } else {
                    $map['home_large_pic'] = $pic['savepath'] . $pic['savename'];
                }
            }
            //首页 320*360 小图上传
            if ($_FILES['small_pic']['name']) {
                $pic = $this->_upPic('small_pic');
                if (!$pic) {
                    $this->error("上传失败");
                } else {
                    $map['home_small_pic'] = $pic['savepath'] . $pic['savename'];
                }
            }
        }

        $map['title'] = $data['title'];
        $map['person'] = $data['person'];
        $map['news_category_id'] = $data['news_category'];
        $map['type'] = $data['type'];
        $map['create_time'] = date("Y-m-d H:i:s");
        $map['summary'] = $data['summary'];
        $map['key_words'] = $data['key_words'];
        $map['recommend'] = $data['recommend'];
        $map['content'] = $data['desc'];
        //图片上传
        $pic = $this->_upPic('title_pic');
        if (!$pic) {
            $this->error("上传失败");
        } else {
            $map['picture'] = $pic['savepath'] . $pic['savename'];
        }
        $add = $this->article_model->add($map);
        if ($add) {
            $this->success('文章发布成功', U('Discover/actione'));
        } else {
            $this->success('文章发布失败');
        }
    }

    //文章修改
    public function editacti() {
        $id = I('get.id', '', 'int');
        $list = $this->article_model->where(array('id' => $id))->find();
        $this->assign('list', $list);
        $condition = array();
        $condition['status'] = 1;
        $category_list = $this->news_category_model->field('name, id')->where($condition)->select();
        $this->assign('category_list', $category_list);
        $this->display();
    }

    //文章修改
    public function doeditacti() {
        $data = I('post.');
        $id = $data['id'];
        unset($data['id']);
        if ($data['title'] == '' || $data['desc'] == '' || $data['person'] == '' || !in_array($data['type'], array(1, 2, 3, 4, 5))) {
            $this->error('请完整填写信息!');
            return false;
        }
        //判断图片是否在首页显示
        if ($data['recommend'] == 1) {
            if ($_FILES['large_pic']['name'] == '' && $_FILES['small_pic']['name'] == '') {
                $this->error('首页图片必须选择一个格式!');
                return false;
            }
            //大图片首页 640*360 修改
            if ($_FILES['large_pic']['name']) {   
                    $pic = $this->_upPic('large_pic');
                    if (!$pic) {
                        $this->error("上传失败");
                    } else {
                        $map['home_large_pic'] = $pic['savepath'] . $pic['savename'];
                    }
                }
            //小图片首页 320*360 修改
            if ($_FILES['small_pic']['name']) {
                    $pic = $this->_upPic('small_pic');
                    if (!$pic) {
                        $this->error("上传失败");
                    } else {
                        $map['home_small_pic'] = $pic['savepath'] . $pic['savename'];
                    }
                }
            }
        
        //图片修改
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
        $list = $this->article_model->where(array('id' => $id))->find();
        $oldImg = $list['picture'];
        $oldLargeImg = $list['home_large_pic'];
        $oldSmallImg = $list['home_small_pic'];
        $oldLargeImgpath = $oldImg;
        $oldSmallImgpath = $oldImg;
        $oldImgpath = $oldImg;
        
        $map['title'] = $data['title'];
        $map['content'] = $data['desc'];
        $map['person'] = $data['person'];
        $map['type'] = $data['type'];
        $map['modify_time'] = date("Y-m-d H:i:s");
        $map['summary'] = $data['summary'];
        $map['key_words'] = $data['key_words'];
        $map['recommend'] = $data['recommend'];
        $save = $this->article_model->where(array('id' => $id))->save($map);
        if ($save) {
            $this->success('修改成功', U('Discover/actione'));
            @unlink($oldImgpath);
            @unlink($oldLargeImgpath);
            @unlink($oldSmallImgpath);
        } else {
            $this->error('内容未修改');
        }
    }

    //文章查看
    public function detail() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('存在错误请检查');
        }
        $list = $this->article_model->where(array('id' => $id))->find();
        $this->assign('list', $list);
        $this->display();
    }

    //删除发现文章
    public function delarticle() {
        $id = I('get.id', '', 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $map['status'] = 2;
        $list = $this->article_model->where("id=$id")->save($map);
        if ($list) {
            $this->success('删除成功', U('Discover/actione'));
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
