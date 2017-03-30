<?php

/**
 * 后台管理的主要操作类
 * 友情链接管理
 * @author wangsong <wangsong@gmail.com>
 * @copyright 上海熔意网络科技有限公司 <http://www.txrt.cn>
 */

namespace Admin\Controller;

use Think\Controller;

class LinkController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    //友情链接列表
    public function link() {
        $post_data = array();
        $link = M('link'); // 实例化User对象
        $count = $link->where('status=1 OR status=2')->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, C("PAGE_COUNT")); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show(); // 分页显示输出
        $link_list = $link->where('status=1 OR status=2')->order('id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('link_list', $link_list);
        $this->assign('page', $show);
        $this->assign('firstRow', $Page->firstRow);
        $this->display();
    }

    //友情链接详情查看
    public function detaillink() {
        $id = I('get.id', '', 'int');
        $link = M('link'); // 实例化User对象
        $link_list = $link->where(array('id' => $id))->find();
        if ($id == '') {
            $this->error('存在错误请检查');
        }
        $this->assign('link_list', $link_list);
        $this->display();
    }

    //添加友情链接
    public function addlink() {
        $this->display();
    }

    //添加友情链接
    public function addlinking() {
        if (IS_POST) {
            $data = I('post.');
            if ($data['name'] == '' || $data['status'] == '') {
                $this->error('请完整填写信息!');
                return false;
            }
            //填写规范的地址
            $domain = strstr($data['url'], "http://");
            if (empty($domain)) {
                $this->error("请填写规范的地址! 如：http://www.baidu.com");
            }
            $data["name"] = I('post.name', "string");
            $data["url"] = I('post.url', '', 'string');
            $data["status"] = I('post.status', '', 'int');

            $link = M("link");
            $add = $link->add($data);
            if ($add) {
                $this->success('添加成功！', U('Link/link'));
            } else {
                $this->error('添加失败！');
            }
        }
    }

    //修改友情链接
    public function editlink() {
        //获取之前的值
        $id = I('get.id', '', 'int');
        $link = M("link");
        $link_list = $link->where(array('id' => $id))->find();
        $this->assign('link_list', $link_list);
        $this->display();
    }

    //修改友情链接
    public function editlinking() {
        //修改
        if (IS_POST) {
            $id = I('post.id', '', 'int');
            $name = I('post.name', '', 'string');
            $status = I('post.status', '', 'int');
            $url = I('post.url', '', 'string');
            if (empty($id)) {
                $this->error("传参错误");
            }
            if (empty($name)) {
                $this->error("请填写友情链接名称!");
            }
            if (empty($status)) {
                $this->error("请填写友情链接状态!");
            }
            //填写规范的地址
            $domain = strstr($url, "http://");
            if (empty($domain)) {
                $this->error("请填写规范的地址! 如：http://www.baidu.com");
            }

            $data['id'] = $id;
            $data["name"] = $name;
            $data["url"] = $url;
            $data["status"] = $status;
            $link = M("link");
            $link_save = $link->save($data);
            //修改数据库
            if ($link_save) {
                $this->success('修改成功！', U('Link/link'));
            } else {
                $this->error("修改失败");
            }
        }
    }

    //删除友情链接
    public function dellinks() {
        $id = I('get.id', 0, 'int');
        if ($id == '') {
            $this->error('内部错误!');
        }
        $data['status'] = 3;
        $link = M("link");
        $link_list = $link->where("id=$id")->save($data);
        if ($link_list) {
            $this->success('删除成功', U('Link/link'));
        } else {
            $this->error('删除失败!');
        }
    }

}
