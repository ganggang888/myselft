<?php
/*
 * @name 六对一
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class SixForOneController extends AdminbaseController
{
    protected $info;
    function _initialize() {
            $mongo = new \MongoClient ("mongodb://localhost:27017");
            $evaluation = $mongo->evaluation;
            $this->info = $evaluation->info;
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
            );
            $this->info->insert($array) ? $this->success('添加成功',U('SixForOne/index')) : $this->error('添加失败');
        }
        $babyId = I('get.babyId');
        $babyName = getNameForBabyid($babyId);
        $babyId = I('get.babyId');
        !$babyId ? $this->error("GET OUT!!") : '';
        $this->assign('babyId',$babyId);
        $this->assign('babyName',$babyName);
        $this->assign(compact('babyId','babyName'));
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
        $this->assign(compact('info','id'));
        $this->display();
    }


    //修改
    public function edit()
    {
        if (IS_POST) {
            $id = I('post.id');
            $array = array(
                'data'=>I('post.data'),
                'proposal'=>I('post.proposal'),
                'evaluation'=>I('post.evaluation'),
                'supplement'=>I('post.supplement'),
                'times'=>date("Y-m-d H:i:s"),
                'babyId'=>I('post.babyId'),
            );
            $this->info->update(array('_id'=>new \MongoId("$id")),array('$set'=>$array)) ? $this->success('修改成功',U('SixForOne/index')) : $this->error('修改失败');
        }
        $id = I('get.id');
        !$id ? $this->error("GET OUT") : '';
        $info = $this->info->findOne(array('_id'=>new \MongoId("$id")));
        $this->assign(compact('info','id'));
        $this->display();
    }

    //删除
    public function delete(string $id)
    {
        !$id ? $this->error("GET OUT") : '';
        $this->info->remove(array('_id'=>new \MongoId("$id"))) ? $this->success('删除成功',U('SixForOne/index')) : $this->error('删除失败',U('SixForOne/index'));
    }
    
}