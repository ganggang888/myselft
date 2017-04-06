<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/30
 * Time: 13:30
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class TopicController extends AdminbaseController
{
    protected $term = NULL;
    function _initialize() {
        $this->term = D("Common/TopicTerm");
        parent::_initialize();
    }

    //话题分类首页
    public function index()
    {
        $result = $this->term->where(['type'=>0])->order(array("listorder" => "ASC"))->select();
        import("Tree");
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $newmenus=array();
        foreach ($result as $m){
            $newmenus[$m['id']]=$m;

        }
        foreach ($result as $n=> $r) {

            $result[$n]['level'] = $this->_get_level($r['id'], $newmenus);
            $result[$n]['parentid_node'] = ($r['parentid']) ? ' class="child-of-node-' . $r['parentid'] . '"' : '';

            $result[$n]['str_manage'] = '<a href="' . U("Topic/add", array("parentid" => $r['id'], "menuid" => $_GET['menuid'])) . '">添加子菜单</a> | <a  href="' . U("Topic/edit", array("id" => $r['id'], "menuid" => $_GET['menuid'])) . '">修改</a> | <a class="J_ajax_del" href="' . U("Topic/delete", array("id" => $r['id'], "menuid" => I("get.menuid")) ). '">删除</a> ';
            $result[$n]['status'] = $r['status'] ? "显示" : "隐藏";
        }

        $tree->init($result);
        $str = "<tr id='node-\$id' \$parentid_node>
					<td style='padding-left:20px;'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input input-order'></td>
					<td>\$id</td>
        			<td>\$spacer\$name</td>
					<td>\$add_time</td>
				    <td>\$str_manage</td>
				</tr>";
        $categorys = $tree->get_tree(0, $str);
        $this->assign(compact('categorys'));
        $this->display();
    }

    //话题分类添加
    public function add()
    {
        if (IS_POST) {
            if ($this->term->create() !== false) {
                if ($this->term->add() !== false) {
                    $this->success('添加成功',U('Topic/index'));
                } else {
                    $this->error('添加失败',U('Topic/index'));
                }
            } else {
                $this->error($this->term->getError());
            }
        }
        import("Tree");
        $tree = new \Tree();
        $parentid = intval(I("get.parentid"));
        $result = $this->term->where(['type'=>0])->order(array("listorder" => "ASC"))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign(compact('select_categorys'));
        $this->display();
    }

    //话题分类修改
    public function edit($id)
    {
        if (IS_POST) {
            if ($this->term->create() !== false) {
                if ($this->term->save() !== false) {
                    $this->success('修改成功',U('Topic/index'));
                } else {
                    $this->error('修改失败',U('Topic/index'));
                }
            } else {
                $this->error($this->term->getError());
            }
        }
        import("Tree");
        $tree = new \Tree();
        $id = intval(I("get.id"));
        $rs = $this->term->where(array("id" => $id))->find();
        $result = $this->term->where(['type'=>0])->order(array("listorder" => "ASC"))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign(compact('rs','select_categorys'));
        $this->display();
    }

    //话题分类删除
    public function delete($id)
    {
        $this->term->where("id=%d",array($id))->delete() ? $this->success('删除成功',U('Topic/index')) : $this->error('删除失败',U('Topic/index'));
    }

    //排序
    public function listorders()
    {
        $caseThen = '';
        $i = '';
        $info = I('post.listorders');
        foreach ($info as $key=>$vo) {
            $caseThen .= " WHEN $key THEN $vo \n";
            $i .= "$key,";
        }
        $i ? $i = substr($i,0,strlen($i)-1) : '';
        if ($i && $caseThen) {
            $sql = " UPDATE sp_topic_term SET listorder = CASE id $caseThen \n END \n WHERE ID IN($i)";

            M()->execute($sql);
        }
        $this->success('排序成功');
    }


    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    protected function _get_level($id, $array = array(), $i = 0) {

        if ($array[$id]['parentid']==0 || empty($array[$array[$id]['parentid']]) || $array[$id]['parentid']==$id){
            return  $i;
        }else{
            $i++;
            return $this->_get_level($array[$id]['parentid'],$array,$i);
        }

    }
}