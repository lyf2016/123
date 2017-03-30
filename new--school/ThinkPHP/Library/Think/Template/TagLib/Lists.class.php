<?php

namespace Think\Template\TagLib;
use Think\Template\TagLib;
class Lists extends TagLib{
    protected $tags = array(
        'list' => array('attr' => 'limit,order','close' =>1), // attr 属性列表close 是否闭合（0 或者1 默认为1，表示闭合）
        'category' => array('attr' => ''),
        'china' => array('attr' => ''),
        'search' => array('attr' => ''),
        'history' => array('attr' => ''),
        'explain' => array('attr' => ''),
    );

    public function _list ($attr,$content){
        $attr = $this->parseXmlAttr($attr);
        $limit=$attr['limit'];//参数$limit，可通过模板传入参数值
        $order=$attr['order'];//$order$limit，可通过模板传入参数值
        $str='<?php ';
        $str .= '$field=array("id","title","hits");';//定义需要调用的字段
        $str .= '$_list_news=M("News")->field($field)->limit('.$limit.')->order("'.$order.'")->select();';//查询语句
        $str .= 'foreach ($_list_news as $_list_value):';
        $str .= 'extract($_list_value);';
        $str .= '$url=U("read/".$id);?>';//自定义文章生成路径$url
        $str .= $content;
        $str .='<?php endforeach ?>';
        return $str;
    }

    /**
     * 商品类别
     */
    public function _category ($attr, $content) {
        $str='<?php ';
        $str .= '$categorys = A("Category");';
        $str .= '$list_category = $categorys->index();';
        $str .= 'foreach ($list_category as $_list_value):';
        $str .= 'extract($_list_value);';
        $str .= '$product_url = U("Product/index", array("category_id"=>$id, "name"=>$name));?>';
        $str .= $content;
        $str .='<?php endforeach ?>';
        return $str;
    }

    /**
     * 省份城市城区信息
     */
    public function _china ($attr, $content) {
        $str = '<?php ';
        $str .= '$data_post["parent"] = $attr["parent"]';
        $str .= '$chinas = A("China");';
        $str .= '$list_china = $chinas->index($data_post);';
        $str .= 'foreach ($list_china as $_list_value):';
        $str .= 'extract($_list_value);?>';
        $str .= $content;
        $str .='<?php endforeach ?>';
        return $str;
    }

    /**
     * 搜索功能 推荐的关键词
     */
    public function _search ($attr, $content) {
        $str = '<?php ';
        $str .= '$searchs = A("Search");';
        $str .= '$list_search = $searchs->getSearchHot();';
        $str .= 'foreach ($list_search as $_list_value):';
        $str .= 'extract($_list_value);?>';
        $str .= $content;
        $str .='<?php endforeach ?>';
        return $str;
    }

    /**
     * 搜索功能 推荐的历史
     */
    public function _history ($attr, $content) {
        $str = '<?php ';
        $str .= '$searchs = A("Search");';
        $str .= '$list_search = $searchs->getSearchHistory();';
        $str .= 'foreach ($list_search as $_list_value):';
        $str .= 'extract($_list_value);?>';
        $str .= $content;
        $str .='<?php endforeach ?>';
        return $str;
    }

    /**
     * 网站说明 帮助中心列表
     */
    public function _explain ($attr, $content) {
        $str = '<?php ';
        $str .= '$explain = A("Explain");';
        $str .= '$list_explain = $explain->getExplains();';
        $str .= 'foreach ($list_explain as $_list_value):';
        $str .= 'extract($_list_value);?>';
        $str .= $content;
        $str .='<?php endforeach ?>';
        return $str;
    }

}
