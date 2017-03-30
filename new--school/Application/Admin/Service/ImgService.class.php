<?php
/**
 * 首页
 */
namespace Admin\Service;

class ImgService {
    public function __construct() {
        
    }
    
    /**
     * 获取上传图片路径
     */
    public function getUploadImgPath($name = ''){
        if($name == ''){
            return FALSE;
        }
        
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = C('UPLOAD_FILE_MAX_BYTES'); // 设置附件上传大小
        $upload->exts = C('UPLOAD_FILE_TYPE'); // 设置附件上传类型
        $upload->rootPath = 'Public/'; // 设置附件上传根目录
        $upload->savePath = 'upload/'; // 设置附件上传(子)目录
        $pic_path = $upload->uploadOne($_FILES["showimg"]);
        return $pic_path['savepath'].$pic_path['savename'];
    }
}
