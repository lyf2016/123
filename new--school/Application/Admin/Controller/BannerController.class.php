<?php
/**
 * 后台管理的主要操作类
 * 轮播图管理
 * @author wangsong <wangsong@gmail.com>
 * @copyright 上海熔意网络科技有限公司 <http://www.txrt.cn>
 */
namespace Admin\Controller;
use Think\Controller;
class BannerController extends BaseController {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->display();
    }

    //轮播图图片列表
    public function showimg() {
        $slider_model = M('slider'); // 实例化User对象
        $count = $slider_model->where('status=1')->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, C("PAGE_COUNT")); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show(); // 分页显示输出
        $list = $slider_model->where('status=1')->order('sort asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    //显示添加轮播图页面
    public function addimg() {
        $this->display();
    }

    //查询修改图片的信息
    public function editimg() {
        if (IS_GET) {
            $data = array();
            $data["id"] = I('get.id');
            $slider_model = M("slider");
            $list = $slider_model->where($data)->find();
            $this->assign("list", $list);
            $this->display('');
        }
    }

    //添加轮播图
    public function doimg() {
        if (IS_POST) {
            $data["title"] = I('post.title', '', 'string');
            $data["local"] = I('post.local', "int");
            $data["sort"] = I('post.sort', '', 'int');
            $data["link"] = I('post.link', '', 'string');
            if ($data['title'] == '' || $data['local'] == '' || $data['sort'] == '') {
                $this->error('请完整填写信息!');
                return false;
            }
            //填写规范的地址
            $domain = strstr($data['link'], "http://");
            if (empty($domain)) {
                $this->error("请填写规范的地址! 如：http://www.baidu.com");
            }
            
            $slider_model = M("slider");
            //检测图片
            if ($_FILES['showimg']['name'] == '') {
                $this->error('没有上传图片');
                return false;
            } else {
                $pic = $this->_upPic('showimg');
                if (!$pic) {// 上传错误提示错误信息
                    $this->error('上传错误');
                    exit;
                } else {
                    $data["src"] = $pic["savepath"] . $pic['savename'];
                }
            }
            $new_data_id = $slider_model->add($data);
            if ($new_data_id) {
                $this->success('上传成功！', U('Banner/showimg'));
            } else {
                $this->error('上传失败！', U('Banner/showimg'));
            }
        }
    }

    //修改轮播图
    public function doedit() {
        if (IS_POST) {
            $banner_id = I('post.id', 0, 'int');
            $data["local"] = I('post.local', '', 'int');
            $data["title"] = I('post.title', '', 'string');
            $data["sort"] = I('post.sort', '', 'int');
            $data["link"] = I('post.link', '', 'string');
            $data['src'] = I('post.src', '', 'string');
            
            $img["showimg"] = I('post.showimg', '', 'string');
            if ($data['title'] == '' || $data['local'] == '') {
                $this->error('请完整填写信息!');
                return false;
            }
            if ($banner_id == 0) {
                $this->error("内部错误");
            }
            
            //填写规范的地址
            $domain = strstr($data['link'], "http://");
            if (empty($domain)) {
                $this->error("请填写规范的地址! 如：http://www.baidu.com");
            }
            
            $slider_model = M("slider");
            if($data['src'] == ''){
                if ($_FILES['showimg']['name'] == '') {
                    if ($img['showimg'] == '') {
                        $this->error('没有上传图片');
                        return false;
                    }
                } else {
                    $pic = $this->_upPic('showimg');
                    if (!$pic) {// 上传错误提示错误信息
                        $this->error('上传错误');
                        exit;
                    }
                    $data["src"] = $pic["savepath"] . $pic['savename'];
                }
            }
            $save_result = $slider_model->where(array('id'=>$banner_id))->save($data);
            if ($save_result === FALSE) {
                $this->error("修改失败");
            } else {
                $this->success('修改成功！', U('Banner/showimg'));
            }
        }
    }

    //删除轮播图片
    public function delimg() {
        if (IS_GET) {
            $id = I('get.id', '', 'int');
            $slider_model = M('slider');
            $data['status'] = 2;
            $list = $slider_model->where("id=$id")->save($data);
            if ($list) {
                $this->success('删除成功', U('Banner/showimg'));
            } else {
                $this->error('删除失败');
            }
        } else {
            $this->error('非法请求');
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
