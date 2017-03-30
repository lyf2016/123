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
class DiscoverController extends BaseController {

    private $article_model, $article_list_img_model, $news_category_model;
    protected $config;

    public function __construct() {
        parent::__construct();
        $this->article_model = M('Article');
        $this->article_list_img_model = M('ArticleListImg');
        $this->news_category_model = M('NewsCategry');
    }

    /**
     * 列表页
     */
    public function index() {

        $this->display();
    }

    /**
     * 发现新闻详情
     */
    public function discoverList() {
        $id = I('get.id', '', 'int');
        $type = I('get.type', 0, 'int');
        if ($type == 0) {
            $this->error('参数错误!');
        }
        $this->assign('type', $type);
        $this->assign('id', $id);

        $map['status'] = 1;
        $map['type'] = $type;
        
        $mapc['status'] = 1;
        $mapc['recommend'] = 1;
        
        $list = $this->article_model->where($map)->order('create_time DESC')->limit(6)->select();
        $recommend_list = $this->article_model->where($mapc)->order(array("type"=>"asc"))->group(type)->select();
        $this->assign('comment_list', $recommend_list);//推荐到首页查询
        $this->assign('list', $list);
        //标签
        $keywords_array = array();
        $keywords_str = '';
        foreach($list as $val){
            $keywords_str .= $val['key_words'].',';
        }
        $keywords_array = explode(',',$keywords_str);
        $keywords_array = array_unique($keywords_array);
        $this->assign('key_words', $keywords_array);
        
        //详情页中第一张图
        $article_list_img_list = $this->article_list_img_model->where($map)->limit(1)->select();
        $this->assign('article_list_img_list', $article_list_img_list);
        
        //分类
        $category_list = $this->news_category_model->where($map)->select();
        $this->assign('category_list', $category_list);
        //热点
        $map['id'] = array('neq', $id);
        $article_list = $this->article_model->where($map)->order('create_time desc')->limit(0, 3)->select(); //右上角三条
        $this->assign('article_list', $article_list);

        $this->display();
    }

    /**
     * 发现文章详情
     */
    public function detail() {
        $id = I('get.id', '', 'int');
        if (empty($id)) {
            $this->error('内部错误!');
        }
        $map['status'] = 1;
        $list = $this->article_model->where($map)->find($id);
        $news_category_id = $list['news_category_id']; //查询新闻分类
        $list['classname'] = $this->article_list_img_model->where(array('id' => $news_category_id))->getField('name');
        $this->assign('list', $list);
        if ($list['key_words'] != '') {
            $this->assign('key_words', explode(',', $list['key_words']));
        }

        //分类
        $category_list = $this->news_category_model->where($map)->select();
        $this->assign('category_list', $category_list);
        //热点
        $map['id'] = array('neq', $id);
        $article_list = $this->article_model->where($map)->order('create_time desc')->limit(0, 3)->select(); //右上角三条
        $this->assign('article_list', $article_list);

        $this->display();
    }

}
