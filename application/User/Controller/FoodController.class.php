<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User\Controller;
use Common\Controller\AdminbaseController;
class FoodController extends AdminbaseController
{
    
    protected $food = null;
    protected $field = array('id','month','level','admin_id','nr','add_time');
    function _initialize() {
        $this->food = D("Common/SubjectFood");
        parent::_initialize();
    }
    
    /*
     * 推荐食谱首页
     * @access public
     * @param int $month 月份
     * @param int $level 级别
     * @return view:user.game.index
     */
    public function index()
    {
        $month = I('get.month');
        $level = I('get.level');
        $where = array();
        $month ? $where['month'] = $month : '';
        $level ? $where['level'] = $level : '';
        $count = $this->food->where($where)->count();
        $page = $this->page($count,10);
        $result = $this->food->where($where)->field($this->field)->order("id DESC")->limit($page->firstRow,$page->listRows);
        $this->assign(compact('month','level','page','result'));
        $this->display();
    }
    
    /*
     * 添加推荐食谱
     * @access public
     * @return view:user.game.add
     */
    public function add()
    {
        if (IS_POST) {
            $_POST['nr'] = serialize(I('post.test_id'));
            if ($this->food->create() !== false) {
                if ($this->food->add($_POST) !== false) {
                    $this->success("添加成功",U('Food/index'));
                } else {
                    $this->error("添加失败");
                }
            } else {
                $this->error($this->food->getError());
            }
        }
    }
    
    /*
     * 修改食谱
     * @access public
     * @param int $id 自增ID
     * @return view:use.game.edit
     */
    public function edit()
    {
        if (IS_POST) {
            $_POST['nr'] = serialize(I('post.test_id'));
                if ($this->food->create() !== false) {
                        if ($this->food->where("id = %d",array($_POST['id']))->save($_POST) !== false) {
                                $this->success('修改成功',U('Food/index'));
                        } else {
                                $this->error('修改失败',U('Food/index'));
                        }
                } else {
                        $this->error($this->food->getError());
                }
        }
        $id = I('get.id');
        $info = $this->food->where("id=%id",array($id))->field($this->field)->find();
        $info['nr'] = $this->food->savedTestStore($info['nr']);
        $this->assign('month',$this->food->allMonth());
        $this->assign('level',$this->food->allLevel());
        $this->assign('info',$info);
        $this->display();
    }
    
    
    /*
     * 检索列表
     * @access public
     * @param string $name 名称
     * @param int $month 月份
     * @return view
     */
    public function checkTestStore()
    {
        $name = I('get.name');
        $month = I('get.month');
        $result = $this->food->selectTestStoreInfo($name,$month);
        $this->assign('result',$result);
        $this->display();
    }
    
    /*
     * 删除食谱信息
     * @access public
     * @param int $id ID
     * @return url
     */
    public function delete()
    {
        $id = I('get.id');
        $this->food->where("id=%id",array($id))->delete() ? $this->success('删除成功',U('Food/index')) : $this->error("删除失败",U('Food/index'));
    }
    
}