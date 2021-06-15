<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use addons\fastexport\library\ExportLib;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

/**
 * 数据导出管理
 *
 * @icon fa fa-circle-o
 */
class Fastexport extends Backend
{

    /**
     * FastExport模型对象
     * @var \app\admin\model\FastExport
     */
    protected $model = null;
    protected $database = [];
    public $save_dir = RUNTIME_PATH . 'fastexport' . DS;// 临时文件保存位置(每次开始任务时清空)
    public $fastExportDir;// ZIP包保存位置(重新初始化/删除任务时删除ZIP包)

    public function _initialize()
    {
        parent::_initialize();
        $rootPath            = str_replace('/', DS, ROOT_PATH);
        $this->model         = new \app\admin\model\FastExport;
        $this->database      = config('database');
        $this->fastExportDir = $rootPath . 'public' . DS . 'fastexport';

        $tableList = [];
        $list      = Db::query("SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.TABLES WHERE table_schema = ? ", [$this->database['database']]);

        // 需要排除的数据表
        $rule_out_table = [
            $this->database['prefix'] . 'config',
            $this->database['prefix'] . 'version',
            // $this->database['prefix'] . 'admin',
            $this->database['prefix'] . 'user_token',
            $this->database['prefix'] . 'fast_auth_group_access',
        ];

        foreach ($list as $key => $row) {
            if (!in_array($row['TABLE_NAME'], $rule_out_table)) {
                $tableList[] = [
                    'name'    => $row['TABLE_NAME'],
                    'comment' => $row['TABLE_COMMENT'] ? str_replace('表', '', $row['TABLE_COMMENT']) : '',
                ];
            }
        }

        $this->view->assign("tableList", $tableList);
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk       = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {

                    // 同时删除临时文件和zip包
                    if (is_dir($this->save_dir . $v->id)) {
                        $this->emptydir($this->save_dir . $v->id, true);
                    }

                    if ($v->file) {
                        $file_name = explode(DS, str_replace('/', DS, $v->file));
                        if (file_exists($this->fastExportDir . DS . end($file_name))) {
                            unlink($this->fastExportDir . DS . end($file_name));
                        }
                    }

                    $count += $v->delete();
                }

                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 测试任务-导出前10条数据
     * @param int $ids [description]
     */
    public function testTask($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $ExportLib = new ExportLib($row);

        try {

            $export_number  = $row['export_number'] ? $row['export_number'] : $ExportLib->buildSql('count');
            $xls_max_number = ($export_number >= $row['xls_max_number']) ? $row['xls_max_number'] : $export_number;
            if (!$export_number) {
                $this->error('没有数据需要导出~');
            }

            $min = 0;
            $max = 10;

            $subtask = [
                1 => [
                    'id'     => 1,
                    'status' => 0,// 0准备好，1进行中，2完成，3失败
                    'min'    => $min,
                    'max'    => $max,
                    'sql'    => $ExportLib->buildSql('limit', [$min, $max])
                ]
            ];

            // 测试sql
            Db::query($subtask[1]['sql']);

            $row->subtask = $subtask;
            $row->save();

        } catch (PDOException $e) {
            $this->error($e->getMessage());
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('导出任务测试准备好~', null, [
            'direct_export' => true,
            'subtask'       => $subtask,
            'id'            => $ids
        ]);
    }

    /**
     * 开始任务->初始化任务配置
     * @return bool
     */
    public function performTask($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $ExportLib = new ExportLib($row);

        try {

            $export_number  = $row['export_number'] ? $row['export_number'] : $ExportLib->buildSql('count');
            $xls_max_number = ($export_number >= $row['xls_max_number']) ? $row['xls_max_number'] : $export_number;
            if (!$export_number) {
                $this->error('没有数据需要导出~');
            }

            $subtask       = [];
            $subtask_count = ceil($export_number / $xls_max_number);// 子任务数量
            for ($i = 1; $i <= $subtask_count; $i++) {

                if ($i == 1) {
                    $min = 0;
                } else {
                    $min = ($i - 1) * $xls_max_number;
                }

                $subtask[$i] = [
                    'id'     => $i,
                    'status' => 0,// 0准备好，1进行中，2完成，3失败
                    'min'    => $min,
                    'max'    => $xls_max_number,
                    'sql'    => $ExportLib->buildSql('limit', [$min, $xls_max_number])
                ];

                // 测试sql
                if ($i == 1) {
                    Db::query($subtask[$i]['sql']);
                }
            }

            if ($subtask_count > 1) {
                // 清理任务临时文件目录
                if (!is_dir($this->save_dir . $ids)) {
                    mkdir($this->save_dir . $ids, 0777, true);
                } else {
                    $this->emptydir($this->save_dir . $ids);
                }

                $row->progress = 5;
            } else {
                $row->progress = 0;
            }

            // 删除上次任务的zip包
            if ($row->file) {

                if ($row->file) {
                    $file_name = explode(DS, str_replace('/', DS, $row->file));
                    if (file_exists($this->fastExportDir . DS . end($file_name))) {
                        unlink($this->fastExportDir . DS . end($file_name));
                    }
                }
                $row->file = '';
            }

            $row->subtask = $subtask;
            $row->save();

        } catch (PDOException $e) {
            $this->error($e->getMessage());
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('导出任务初始化成功！', null, [
            'direct_export' => ($subtask_count == 1) ? true : false,
            'subtask'       => $subtask,
            'id'            => $ids
        ]);
    }

    /**
     * 子任务控制器
     * @param  [type] $task_id 任务ID
     */
    public function taskControl($task_id = null)
    {
        $row = $this->model->get($task_id);
        if (!$row) {
            $this->error(__('任务找不到啦~'));
        }

        if (!isset($row['subtask']) || !is_array($row['subtask'])) {
            $this->error('子任务找不到啦，请重新开始任务~');
        }
        $row = $row->toArray();

        // 子任务状态:0准备好，1进行中，2完成，3失败
        foreach ($row['subtask'] as $key => $value) {
            switch ($value['status']) {
                case '0':
                    $row['subtask'][$key]['status_text'] = '等待中';
                    break;
                case '1':
                    $row['subtask'][$key]['status_text'] = '进行中';
                    break;
                case '2':
                    $row['subtask'][$key]['status_text'] = '已完成';
                    break;

                default:
                    $row['subtask'][$key]['status_text'] = '失败';
                    break;
            }
        }

        $current_page = 1;
        $subtask_page = [];
        foreach ($row['subtask'] as $key => $value) {
            $value['status']               = 0;// 用户可能会刷新任务控制页面，js将重新确定状态
            $subtask_page[$current_page][] = $value;
            if (count($subtask_page[$current_page]) >= $row['xls_create_concurrent']) {
                $current_page++;
            }
        }

        $subtask_count                = count($row['subtask']);
        $row['item_subtask_progress'] = round(92 / $subtask_count, 2);
        $row['progress']              = 5;

        $this->assignconfig('subtask_page', $subtask_page);
        $this->assignconfig('task', $row);
        $this->view->assign("task", $row);
        return $this->view->fetch();
    }

    /**
     * 执行子任务
     * @param int $task_id 任务ID
     * @param int $subtask_id 子任务ID
     * @param boolean $direct_export 是否直接反回文件
     */
    public function performSubTask($task_id, $subtask_id, $direct_export = false)
    {
        \think\Config::set('default_return_type', 'json');
        $row = $this->model->get($task_id);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if (isset($row['subtask'][$subtask_id]) && is_array($row['subtask'][$subtask_id])) {
            $subtask   = $row['subtask'][$subtask_id];
            $task_name = $row['name'];
        } else {
            $this->result(['subtask_id' => $subtask_id], 0, '子任务找不到啦~', 'json');
        }

        set_time_limit(0);// 脚本执行时间限制
        ini_set('memory_limit', $row['memory_limit'] . 'M');// 脚本内存限制
        $ExportLib = new ExportLib($row);
        unset($row);

        // 检查任务状态
        if (!$direct_export) {
            if ($subtask['status'] == 1) {
                $this->result(['subtask_id' => $subtask_id], 0, '此子任务正在执行中~', 'json');
            } else if ($subtask['status'] == 2) {
                if (file_exists($this->save_dir . $task_id . DS . $subtask_id . '.xlsx')) {
                    $this->result(['subtask_id' => $subtask_id], 1, '此子任务已经处理过啦~', 'json');
                }
            }
        }

        $spreadsheet = new Spreadsheet();
        $worksheet   = $spreadsheet->getActiveSheet();
        $worksheet->setTitle($task_name);

        //设置表头
        $head       = $ExportLib->getXlsTitle();
        $head_count = count($head);
        for ($i = 0; $i < $head_count; $i++) {
            $worksheet->setCellValueByColumnAndRow($i + 1, 1, $head[$i]);
        }

        // 写人数据
        try {
            $data = Db::query($subtask['sql']);
        } catch (PDOException $e) {
            $this->result([
                'subtask_id' => $subtask_id,
                'error_msg'  => $e->getMessage(),
            ], 0, '任务失败！', 'json');
        }

        $y = 2;
        foreach ($data as $row_key => $row) {
            $i = 1;
            foreach ($ExportLib->fields as $key => $value) {

                $field = $value['field'];

                if ($value['discerns'] == 0) {
                    // 文本
                    $worksheet->setCellValueByColumnAndRow($i, $y, $row[$field], DataType::TYPE_STRING);
                } else if ($value['discerns'] == 1) {
                    // 数字
                    $worksheet->setCellValueByColumnAndRow($i, $y, $row[$field] . "\t");
                } else if ($value['discerns'] == 2) {
                    // 日期时间
                    if ($row[$field]) {

                        $excelDateValue = date('Y-m-d H:i:s', $row[$field]);
                        $worksheet->setCellValueByColumnAndRow($i, $y, $excelDateValue, DataType::TYPE_STRING);

                        /*$excelDateValue = PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel( $row[$field] );
                        if ($excelDateValue) {
                            // 以日期日期格式写入，没区别，有需要的可以打开
                            $worksheet->setCellValueByColumnAndRow($i, $y, $excelDateValue);
                            $worksheet->getStyleByColumnAndRow($i,$y)
                            ->getNumberFormat()
                            ->setFormatCode('yyyy-mm-dd h:mm:ss');
                            $worksheet->getStyleByColumnAndRow($i,$y)->getFont()->setBold(true);
                            $worksheet->getStyleByColumnAndRow($i,$y)->getFont()->setBold(false);
                        }*/
                    } else {
                        $worksheet->setCellValueByColumnAndRow($i, $y, '-', DataType::TYPE_STRING);
                    }
                } else if ($value['discerns'] == 3) {
                    // 图片-太慢,太耗资源,弃用
                    /*$file_info  = pathinfo($row[$field]);
                    $coorrow    = $worksheet->getCellByColumnAndRow($i, $y)->getRow();
                    $coorcolumn = $worksheet->getCellByColumnAndRow($i, $y)->getColumn();

                    if ($y == 2) {
                        $worksheet->getColumnDimension($coorcolumn)->setWidth(8);
                    }

                    $worksheet->getRowDimension($coorrow)->setRowHeight(40);

                    if (!empty($file_info['basename'])) {
                        $basename = $file_info['basename'];
                        $drawing  = new Drawing();
                        $drawing->setName('图片');
                        $drawing->setPath($remote_save_dir . $row[$field]);
                        $drawing->setWidth(40);
                        $drawing->setHeight(40);
                        $drawing->setCoordinates($coorcolumn . $coorrow);
                        $drawing->setWorksheet($worksheet);
                    } else {
                        $worksheet->setCellValueByColumnAndRow($i, $y, $row[$field], DataType::TYPE_STRING);
                    }*/

                    // 以链接导出图片
                    $field_value = cdnurl($row[$field], true);
                    $worksheet->setCellValueByColumnAndRow($i, $y, $field_value, DataType::TYPE_STRING);
                    $worksheet->getCellByColumnAndRow($i, $y)->getHyperlink()->setUrl($field_value);

                } else if ($value['discerns'] == 4) {
                    // 文件
                    $field_value = cdnurl($row[$field], true);
                    $worksheet->setCellValueByColumnAndRow($i, $y, $field_value, DataType::TYPE_STRING);
                    $worksheet->getCellByColumnAndRow($i, $y)->getHyperlink()->setUrl($field_value);
                } else if ($value['discerns'] == 5) {
                    // 赋值
                    $field_value = $ExportLib->assignment($row[$field], $value['scheme']);
                    $worksheet->setCellValueByColumnAndRow($i, $y, $field_value, DataType::TYPE_STRING);
                }

                $i++;
            }
            $y++;
            unset($data[$row_key]); // 能节约一点内存
        }

        // xls文件处理
        if ($direct_export) {
            // 直接下载
            ob_end_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");

            $task_name        = $task_id . '.' . $task_name . '.xlsx';
            $encoded_filename = urlencode($task_name);
            $ua               = $_SERVER["HTTP_USER_AGENT"];
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $task_name . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $task_name . '"');
            }
            header("Content-Transfer-Encoding:binary");
            header('Cache-Control: max-age=0');// 禁止缓存

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        } else {
            // 保存
            $writer = new Xlsx($spreadsheet);
            $writer->save($this->save_dir . $task_id . DS . $subtask_id . '.xlsx');

            // 设置任务状态
            $result = false;
            Db::startTrans();
            try {
                // 读取最新的 subtask 数据
                $row      = $this->model->get($task_id);
                $progress = $row->progress + round(92 / count($row['subtask']), 2);
                $progress = ($progress > 100) ? 100 : $progress;

                if (isset($row['subtask'][$subtask_id]) && is_array($row['subtask'][$subtask_id])) {
                    $subtask                        = $row['subtask'];
                    $subtask[$subtask_id]['status'] = 2;
                    $row->subtask                   = $subtask;
                    $row->progress                  = $progress;
                    $row->save();
                    $result = true;
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();

                $this->result([
                    'subtask_id' => $subtask_id,
                    'error_msg'  => $e->getMessage(),
                ], 0, '任务失败！', 'json');

            } catch (Exception $e) {
                Db::rollback();

                $this->result([
                    'subtask_id' => $subtask_id,
                    'error_msg'  => $e->getMessage(),
                ], 0, '任务失败！', 'json');
            }

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            if ($result) {
                $this->result(['subtask_id' => $subtask_id], 1, '子任务处理成功！', 'json');
            } else {
                $this->result(['subtask_id' => $subtask_id], 0, '子任务找不到啦~', 'json');
            }
        }
    }

    /**
     * 打包ZIP
     */
    public function taskFilePack($task_id)
    {
        $row = $this->model->get($task_id);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if (!isset($row['subtask']) || !is_array($row['subtask'])) {
            $this->result(null, 0, '打包失败，任务找不到啦~', 'json');
        }

        if ($row['file']) {
            $this->result(['file_name' => $row['file']], 1, '此任务已经打过包了！', 'json');
        }

        is_dir($this->fastExportDir) OR mkdir($this->fastExportDir, 0755, true);

        foreach ($row['subtask'] as $key => $subtask) {
            if (!file_exists($this->save_dir . $task_id . DS . $subtask['id'] . '.xlsx')) {
                $this->result(null, 0, '子任务未处理完毕！', 'json');
            }
        }

        $task_dir = $this->save_dir . $task_id . DS;// 导出任务的临时文件目录
        $zipname  = $task_id . '.export_' . \fast\Random::alnum(6) . '.zip';
        $zipurl   = request()->domain() . DS . 'fastexport' . DS . $zipname;// 绝对地址,以便各处直接点击下载
        $zipname  = $this->fastExportDir . DS . $zipname;

        $zip = new \ZipArchive();
        $res = $zip->open($zipname, \ZipArchive::CREATE);

        if ($res === TRUE) {
            $dh = opendir($task_dir);
            while ($file = readdir($dh)) {
                if ($file != "." && $file != "..") {
                    $fullpath = $task_dir . $file;
                    if (!is_dir($fullpath)) {
                        $zip->addFile($fullpath, $file);
                    }
                }
            }
            closedir($dh);
        }

        $zip->close();
        $row->file     = $zipurl;
        $row->progress = 100;
        $row->save();

        $this->result(['file_name' => $zipurl], 1, '文件打包完成！', 'json');
    }

    /**
     * 获取字段列表、字段注释、表主键
     * @internal
     */
    public function getTableFields($table = null)
    {
        if ($this->request->isAjax()) {
            $table = $this->request->request('table');
        }

        //从数据库中获取表字段信息
        $sql = "SELECT * FROM `information_schema`.`columns` "
            . "WHERE TABLE_SCHEMA = ? AND table_name = ? "
            . "ORDER BY ORDINAL_POSITION";

        //加载主表的列
        $columnList        = Db::query($sql, [$this->database['database'], $table]);
        $fieldlist_comment = [];

        foreach ($columnList as $index => $item) {
            $fieldlist_comment[$item['COLUMN_NAME']] = $this->FieldComment($item['COLUMN_NAME'], $item['DATA_TYPE'], $item['COLUMN_COMMENT']);
        }

        if ($this->request->isAjax()) {
            $this->success("", null, ['fieldlist_comment' => $fieldlist_comment]);
        } else {
            return $fieldlist_comment;
        }
    }

    /**
     * 字段分析
     * @param String $field 字段名称
     * @param String $data_type 字段数据类型
     * @param String $comment 字段注释
     * @return Array  可用的字段数据
     */
    protected function FieldComment($field, $data_type, $comment)
    {
        // 字段类型识别
        $data_type_int = [
            'tinyint',
            'int',
            'smallint',
            'mediumint',
            'integer',
            'bigint'
        ];

        $discerns = 0;

        if (in_array($data_type, $data_type_int)) {
            $discerns = 1;// 数字
        }

        if (preg_match("/time$/i", $field)) {
            $discerns = 2;// 日期时间
        } else if (preg_match("/image$|avatar$/i", $field)) {
            $discerns = 4;// 3为图片-导出慢弃用
        } else if (preg_match("/file$/i", $field)) {
            $discerns = 4;// 文件
        }

        /*else if (preg_match("/list$|data$|switch$/i", $field)) {
            $discerns = 5;// 赋值
        }*/


        // 字段标题和赋值分析
        if (!$comment) {
            return [
                'discerns' => $discerns,
                'name'     => $field,
                'comment'  => ''
            ];
        }

        $comment = str_replace('，', ',', $comment);
        $comment = str_replace(['(多选)', '(单选)', '（多选）', '（单选）'], '', $comment);

        if (stripos($comment, ':') !== false && stripos($comment, ',') && stripos($comment, '=') !== false) {
            list($field_name, $field_comment) = explode(':', $comment);
            return [
                'discerns' => 5,
                'name'     => $field_name ? $field_name : $field,
                'comment'  => $field_comment
            ];
        } else {

            if (preg_match("/switch$/i", $field)) {
                $discerns     = 5;// 赋值
                $comment_temp = '0=关闭,1=开启';
            }

            return [
                'discerns' => $discerns,
                'name'     => $comment ? $comment : $field,
                'comment'  => isset($comment_temp) ? $comment_temp : ''
            ];
        }
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with(['admin'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['admin'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {

                $row->getRelation('admin')->visible(['username']);
            }
            $list   = collection($list)->toArray();
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
                $params = method_exists($this, 'preExcludeFields') ? $this->preExcludeFields($params) : $params;

                $params['admin_id'] = $this->auth->id;// 记录导出人
                if (isset($params['where_field']['op']) && is_array($params['where_field']['op'])) {
                    foreach ($params['where_field']['op'] as $key => $value) {
                        $params['where_field']['op'][$key] = trim($value);
                    }
                }

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params                = method_exists($this, 'preExcludeFields') ? $this->preExcludeFields($params) : $params;
                $params['join_table']  = isset($params['join_table']) ? $params['join_table'] : [];
                $params['where_field'] = isset($params['where_field']) ? $params['where_field'] : [];
                if (isset($params['where_field']['op']) && is_array($params['where_field']['op'])) {
                    foreach ($params['where_field']['op'] as $key => $value) {
                        $params['where_field']['op'][$key] = trim($value);
                    }
                }

                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $row                      = $row->toArray();
        $row['join_table_number'] = count($row['join_table']);

        // 源表字段列表
        $table_field = $this->getTableFields($row['main_table']);

        // 准备源表和关联表的所有字段-带表名-供筛选和排序
        $allField = [];
        foreach ($table_field as $key => $value) {
            $field_name            = $row['main_table'] . '.' . $key;
            $value['field_name']   = $field_name . ' - ' . $value['name'];
            $allField[$field_name] = $value;
        }

        // 要导出的字段
        $fields = [];
        if ($row['field_config'] && isset($row['field_config']['title'])) {
            foreach ($row['field_config']['title'] as $key => $value) {
                $fields[$key]['title']    = $value;
                $fields[$key]['discerns'] = $row['field_config']['discerns'][$key];
                $fields[$key]['scheme']   = $row['field_config']['scheme'][$key];
            }
        }
        $row['fields'] = $fields;

        // 源表和关联表的字段列表透传数据准备
        $join_field_count = 0;
        if (isset($row['join_table'])) {
            foreach ($row['join_table'] as $key => $value) {
                $join_table_name = isset($value['table']) ? $value['table'] : false;
                if ($join_table_name) {

                    $row['join_table'][$key]['field_list'] = $join_table_field[$join_table_name] = $this->getTableFields($join_table_name);

                    $join_table_name_as = $value['join_as'] ? $value['join_as'] : $join_table_name;
                    foreach ($join_table_field[$join_table_name] as $join_field_key => $join_field_value) {
                        $field_name                     = $join_table_name_as . '.' . $join_field_key;
                        $join_field_value['field_name'] = $field_name . ' - ' . $join_field_value['name'];
                        $allField[$field_name]          = $join_field_value;
                    }

                }

                // 关联表取值字段准备
                $field_name_arr        = [];
                $field_name_arr_select = [];
                if (isset($row['join_table'][$key]['fields']) && isset($row['join_table'][$key]['fields']['title'])) {
                    foreach ($row['join_table'][$key]['fields']['title'] as $fkey => $fvalue) {
                        $field_name_arr[$fkey]['title']    = $fvalue;
                        $field_name_arr[$fkey]['discerns'] = $row['join_table'][$key]['fields']['discerns'][$fkey];
                        $field_name_arr[$fkey]['scheme']   = $row['join_table'][$key]['fields']['scheme'][$fkey];
                        $field_name_arr_select[]           = $fkey;
                    }
                }
                $row['join_table'][$key]['field_name_arr']        = $field_name_arr;
                $row['join_table'][$key]['field_name_arr_select'] = $field_name_arr_select;// 供selectpicker初始化
                $join_field_count                                 += count($field_name_arr_select);
            }
        }

        $row['where_field_arr_select'] = [];
        $row['where_field_arr']        = [];
        if ($row['where_field'] && isset($row['where_field']['op'])) {
            foreach ($row['where_field']['op'] as $key => $value) {
                $row['where_field_arr_select'][] = $key;
                $row['where_field_arr'][$key]    = [
                    'op'        => $value,
                    'condition' => $row['where_field']['condition'][$key]
                ];
            }
        }

        $this->assignconfig('fields', [
            'table_field'       => $table_field,
            'join_table_field'  => isset($join_table_field) ? $join_table_field : [],
            'join_table_number' => $row['join_table_number'],
            'field_count'       => count($row['fields']) + $join_field_count,
        ]);

        $this->view->assign("row", $row);
        $this->view->assign("allField", $allField);
        $this->view->assign("table_field", $table_field);
        return $this->view->fetch();
    }

    public function emptydir($dir, $del_current_dir = false)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . DS . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->emptydir($fullpath, true);
                }
            }
        }
        closedir($dh);

        // 删除当前文件夹
        if ($del_current_dir) {
            rmdir($dir);
        }
    }
}
