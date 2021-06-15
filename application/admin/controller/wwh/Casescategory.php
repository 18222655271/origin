<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use fast\Tree;

/**
 *
 * @icon fa fa-circle-o
 */
class Casescategory extends Backend
{

    /**
     * WwhCasescategory模型对象
     * @var \app\admin\model\wwh\Casescategory
     */
    protected $noNeedRight = ['getjsTree', 'category'];
    protected $model = null;
    protected $categoryList = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\app\admin\model\wwh\Casescategory');

        $casesCategoryList = collection($this->model->select())->toArray();
        Tree::instance()->init($casesCategoryList);

        $casesCategory = [];
        $this->categoryList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $primary = array(array('name' => '无', 'id' => '0'));
        $result = array_merge_recursive($primary, $this->categoryList);
        foreach ($result as $k => $v) {
            $casesCategory[$v['id']] = $v['name'];
        }

        $this->view->assign("casesCategory", $casesCategory);
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
        if ($this->request->isAjax()) {
            $list = $this->categoryList;
            $total = count($this->categoryList);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }


    /**
     * 获取类别树
     */
    public function getjsTree()
    {
        $categoryList = collection($this->model->select())->toArray();
        $result = [];
        foreach ($categoryList as $k => $v) {
            $n = [];
            $n['id'] = $v['id'];
            $n['parent'] = $v['pid'] == 0 ? "#" : $v['pid'];
            $n['text'] = $v['name'];
            $n['type'] = $v['id'];
            $n['data'] = $v;
            $n['state'] = ["opend" => true, "disabled" => false];
            $result[]=$n;
        }
        return json($result);
    }

    /**
     * 获取类别名称
     */
    public function category()
    {
        $result = collection($this->model->select())->toArray();
        return json($result);
    }
}
