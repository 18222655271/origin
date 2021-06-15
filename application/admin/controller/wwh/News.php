<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use fast\Tree;

/**
 * @icon fa fa-circle-o
 */
class News extends Backend
{

    /**
     * WwhNews模型对象
     * @var \app\admin\model\wwh\News
     */
    protected $model = null;

    /**
     * 快速搜索时执行查找的字段
     */
    
    protected $relationSearch = true;
    protected $searchFields = 'newsname';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\app\admin\model\wwh\News');
        $newscategorylist = collection(model('\app\admin\model\wwh\Newscategory')->select())->toArray();
        Tree::instance()->init($newscategorylist);
        $newscategorylist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $categorylist = array('' => '==请选择==');
        $this->categoryList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        foreach ($newscategorylist as $k => $v) {
            $categorylist[$v['id']] = $v['name'];
        }
        $this->view->assign('newscategorylist', $categorylist);
        $this->view->assign("tjdataList", $this->model->getTjdataList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $categoryids = $this->request->request("categoryids");
            $query = [];
            if ($categoryids !== null) {
                $query["news.newscategoryid"] = ["in", $categoryids];
            }

            $total = $this->model
                    ->with('newscategory')
                    ->where($where)
                    ->where($query)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('newscategory')
                    ->where($where)
                    ->where($query)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 获取所有列表
     */
    public function getallnewslist()
    {
        //设置过滤方法
        if ($this->request->isAjax()) {
            $list = $this->model
                    ->select();
            $list = collection($list)->toArray();
            return json($list);
        }
    }
}
