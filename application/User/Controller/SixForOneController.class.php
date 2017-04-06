<?php
/*
 * @name 六对一 
 * @sad 手如柔荑，肤如凝脂，领如蝤蛴，齿如瓠犀，螓首蛾眉，巧笑倩兮，美目盼兮。
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class SixForOneController extends AdminbaseController
{
    protected $info; //mongodb info表
    protected $evaluation;
    function _initialize() {
            //连接mongo db
            $mongo = new \MongoClient ("mongodb://localhost:27017");
            $this->evaluation = $mongo->evaluation;
            $this->info = $this->evaluation->info;
            parent::_initialize();
    }
    
    /*
     * 首页
     */
    public function index()
    {
        $babyName = I('get.babyName');
        //根据宝宝名称检索匹配的宝宝ID
        $allId = getIdForBabyName($babyName);
        $where = array();
        $allId ? $where['babyId'] = array('$in'=>$allId) : '';
        //开始组织测评日期检索
        $begin = I('get.begin');
        $end = I('get.end');
        $this->assign(compact('begin','end'));
        $begin ? $begin = $begin." 00:00:00" : '';
        $end ? $end = $end." 23:59:59" : '';
        if ($begin && $end) {
            $where['times'] = array('$gt'=>$begin,'$lte'=>$end);
        } elseif ($begin) {
            $where['times'] = array('$gt'=>$begin);
        } elseif ($end) {
            $where['times'] = array('$lte'=>$end);
        }
        $count = $this->info->find($where)->count();
        $page = $this->page($count,15);
        $result = $this->info->find($where)->limit($page->listRows)->skip($page->firstRow)->sort(array("times"=>-1));
        foreach ($result as $key=>$vo) {
            $lists[] = $vo;
        }

        $this->assign(compact('page','lists','babyName'));
        $this->display();
        //var_dump($lists);
    }


    /*
     * 添加
     * @param int $babyId 宝宝Id 
     * @return view
     */
    public function add()
    {
        if (IS_POST) {
            $array = array(
                'data'=>I('post.data'),
                'proposal'=>I('post.proposal'),
                'evaluation'=>I('post.evaluation'),
                'supplement'=>I('post.supplement'),
                'times'=>date("Y-m-d H:i:s"),
                'babyId'=>I('post.babyId'),
                'poor'=>I('post.poor'),
                'maternal'=>I('post.maternal'),
            );
            if ($_FILES['upload']['name'][0]) {
                //处理文件上传的信息
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     10485760 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =      './data/six/'; // 设置附件上传根目录
                $info = $upload->upload();
                !$info ? $this->error($upload->getError()) : $array['upload'] = $info;
            }
            $this->info->insert($array) ? $this->success('添加成功',U('SixForOne/index')) : $this->error('添加失败');
        }
        $babyId = I('get.babyId');
        $babyName = getNameForBabyid($babyId);
        $babyId = I('get.babyId');
        !$babyId ? $this->error("GET OUT!!") : '';

        //处理儿保数据
        $poor = $this->evaluation->data->findOne();
        unset($poor['_id']); //去除自增ID
        $this->assign('babyId',$babyId);
        $this->assign('babyName',$babyName);
        $this->assign(compact('babyId','babyName','poor'));
        $this->display();
    }


    /*
     * 查看单独已测评过的信息
     * @param int $id 自增ID 
     * @return view
     */
    public function seeOne()
    {
        $id = I('get.id');
        !$id ? $this->error("GET OUT") : '';
        $info = $this->info->findOne(array('_id'=>new \MongoId("$id")));
        $babyName = getNameForBabyid($info['babyId']);
        //处理儿保数据
        $poor = $this->evaluation->data->findOne();
        unset($poor['_id']); //去除自增ID
        $this->assign(compact('info','id','poor','babyName'));
        $this->display();
    }


    //修改
    public function edit()
    {
        $id = I('get.id');
        !$id ? $this->error("GET OUT") : '';
        $info = $this->info->findOne(array('_id'=>new \MongoId("$id")));
        if (IS_POST) {
            $id = I('post.id');
            $array = array(
                'data'=>I('post.data'),
                'proposal'=>I('post.proposal'),
                'evaluation'=>I('post.evaluation'),
                'supplement'=>I('post.supplement'),
                'times'=>date("Y-m-d H:i:s"),
                'babyId'=>I('post.babyId'),
                'poor'=>I('post.poor'),
                'maternal'=>I('post.maternal'),
            );
            $hadUpload = I('post.hadUpload');
            //有新文件上传
            if ($_FILES['upload']['name'][0]) {
                //处理文件上传的信息
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     10485760 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =      './data/six/'; // 设置附件上传根目录
                $infos = $upload->upload();
                //上传成功并修改过已上传信息
                if ($infos && $hadUpload) {
                    foreach ($info['upload'] as $key=>$vo) {
                        in_array($key,$hadUpload) ? $old[] = $vo : $delete[] = $vo['savepath'].$vo['savename'];
                    }
                    $delete ? self::removeOld($delete) : '';
                    $upload = array_merge($old,$infos);
                    $array['upload'] = $upload;
                    //删除掉全部的信息并上传新的图片信息
                } elseif ($infos && !$hadUpload) {
                    $upload = $infos;
                    $array['upload'] = array_merge($infos,$info['upload']);
                } elseif (!$infos) {
                    $this->error($upload->getError());
                }           
                 } else {

                //删除过文件信息并提交
                if ($hadUpload) {
                    foreach ($info['upload'] as $key=>$vo) {
                        in_array($key,$hadUpload) ? $upload[] = $vo : $delete[] = $vo['savepath'].$vo['savename'];
                    }
                    $delete ? self::removeOld($delete) : '';
                    $array['upload'] = $upload;
                } else {
                    //全部删除后
                    foreach ($info['upload'] as $key=>$vo) {
                        $delete[] = $vo['savepath'].$vo['savename'];
                    }
                    $delete ? self::removeOld($delete) : '';
                    $array['upload'] = array();
                }
                

            }
            $this->info->update(array('_id'=>new \MongoId("$id")),array('$set'=>$array)) ? $this->success('修改成功',U('SixForOne/index')) : $this->error('修改失败');
        }
        
        $babyName = getNameForBabyid($info['babyId']);
        //处理儿保数据
        $poor = $this->evaluation->data->findOne();
        unset($poor['_id']); //去除自增ID
        $this->assign(compact('info','id','poor','babyName'));
        $this->display();
    }

    //删除
    public function delete(string $id)
    {
        !$id ? $this->error("GET OUT") : '';
        $this->info->remove(array('_id'=>new \MongoId("$id"))) ? $this->success('删除成功',U('SixForOne/index')) : $this->error('删除失败',U('SixForOne/index'));
    }

    //删除旧的图片
    private function removeOld(array $delete)
    {
        $info = $evaluation->info;
        array_map(function($v){unlink("./data/six/".$v);},$delete);
    }
    
}