<?php

namespace Home\Controller;

/**
 * NewsController
 * @category Home
 * @package Home
 * @author wangsong <wangsong@gmail.com>
 * @copyright 上海熔意网络科技有限公司 <http://www.txrt.cn>
 *
 */
class NewsController extends BaseController {

    private $news_model, $news_category_model, $slider_model, $article_model;
    protected $config;

    public function __construct() {
        parent::__construct();
        $this->news_model = M('News');
        $this->news_category_model = M('NewsCategry');
        $this->slider_model = M('Slider');
        $this->article_model = M('Article');
    }

    /**
     * 首页
     */
    public function index() {
        $news_map = array();
        $news_map['status'] = 1;
        $news_map['recommend'] = 1;
        $list = $this->news_model->where($news_map)->order('id desc')->select();
        $this->assign('list', $list);

        $slider_map['status'] = 1;
        $slider_map['location'] = 1;
        $src = $this->slider_model->where($slider_map)->order('id desc')->select();
        $this->assign('src', $src);

        $type_article = array('', '特辑', '风物志', '科学教研', '对外交流');
        $discover_list = $this->article_model->where('status =1 AND recommend =1')->order(array("type"=>"asc"))->group(type)->select();
        $this->assign('discover_list', $discover_list);
        $this->assign('type_article', $type_article);

        $this->display();
    }

    /**
     * 新闻列表
     */
    public function newslist() {
        $map['status'] = 1;
        $category = I('get.news_category_id', '', 'int');
        if (!empty($category)) {
            $map['news_category_id'] = $category;
        }

        $key_words = I('get.key_words', '', 'string');
        if ($key_words != '') {
            $map['key_words'] = array('like', "%{$key_words}%");
        }

        $count = $this->news_model->where($map)->count();
        $Page = new \Think\Page($count, C('PAGE_COUNT'));

        //分页跳转的时候保证查询条件
        foreach ($map as $key => $val) {
            $Page->parameter .= "$key=" . urlencode($val) . '&';
        }

        $p = I('get.p', 1, 'int');
        $nowPage = I('get.nowPage', '', 'int');
        $list = $this->news_model->where($map)->order('create_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign("count", (int) $Page->totalPages); //总页数
        $this->assign("p", $p);
        $this->assign("nowPage", $nowPage);
        $this->display();
    }

    /**
     * 新闻详情
     */
    public function detail() {
        $id = I('get.id', '', 'int');
        if (empty($id)) {
            $this->error('内部错误!');
        }
        $news_map['status'] = 1;
        $list = $this->news_model->where($news_map)->find($id);
        $news_category_id = $list['news_category_id']; //查询新闻分类
        $list['classname'] = $this->news_category_model->where(array('id' => $news_category_id))->getField('name');
        $this->assign('list', $list);
        if ($list['key_words'] != '') {
            $this->assign('key_words', explode(',', $list['key_words']));
        }

        $category_list = $this->news_category_model->where('status=1')->select();
        $this->assign('category_list', $category_list);

        $map['id'] = array('neq', $id);
        $article_list = $this->news_model->where($map)->order('create_time desc')->limit(0, 3)->select(); //右上角三条
        $this->assign('article_list', $article_list);

        $this->display();
    }

}
