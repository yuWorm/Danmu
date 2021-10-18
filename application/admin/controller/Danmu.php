<?php

namespace app\admin\controller;

use think\Db;

class Danmu extends Base{
    public function __construct() {
        parent::__construct();
    }

    public function Index(){
        return $this->fetch('admin@Danmu/index');
    }

    public function delDanmu(){
        $data = $this->request->post('d');
        if (empty($data)) return json_encode(["code"=>1,"msg"=>"数据不可为空"]);
        $data = explode(';',$data);
//        return json($data);
        return json(model('Danmuku')->deleteList($data));
    }

    public function getDanmu(){
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        return json(model('Danmuku')->getDanmuList(1,$page,false, $limit), '0');
    }

    public function findDanmu(){
//        $method = $this->request->get('m');
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $key = $this->request->get('key');
        return json(model('Danmuku')->findDanmu($key, $page, $limit));
    }

    public function delFile(){
        $p = [
            APP_PATH.'admin/controller/Danmu.php',
            APP_PATH.'admin/view/Danmu/',
            APP_PATH.'index/controller/Danmu.php',
            APP_PATH.'common/model/Danmuku.php',
//            __DIR__.'/static/addons/danmu/'
        ];
        $msg = '';
        foreach ($p as $v){
            $s = $this->deldir($v);
            $msg .= $s ? '删除'.$v.'成功' : ($msg.'删除'.$v.'失败，请尝试手动删除\n');
        }
        return json(["code"=>0, "msg"=>$msg]);
    }

    public function deldir($path){
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            //如果 $p 中有两个以上的元素则说明当前 $path 不为空
            if(count($p)>2){
                foreach($p as $val){
                    //排除目录中的.和..
                    if($val !="." && $val !=".."){
                        //如果是目录则递归子目录，继续操作
                        if(is_dir($path.$val)){
                            //子目录中操作删除文件夹和文件
                            deldir($path.$val.'/');
                        }else{
                            //如果是文件直接删除
                            unlink($path.$val);
                        }
                    }
                }
            }
        }else{
            return unlink($path);
        }
        //删除目录
        return rmdir($path);
    }
}