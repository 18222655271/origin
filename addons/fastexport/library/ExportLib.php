<?php

namespace addons\fastexport\library;

use think\Db;

/**
 *
 */
class ExportLib
{
    protected $_error = '';
    protected $task;// 模型
    public $fields = [];

    public function __construct($task)
    {
        if (!$task) {
            $this->setError('导出任务找不到啦~');
            return false;
        }
        $this->task = $task;

        // 准备所有的字段数据
        if ($this->task['field_config'] && isset($this->task['field_config']['title'])) {
            foreach ($this->task['field_config']['title'] as $key => $value) {
                $as_name                            = $this->task['main_table'] . '.' . $key . ' as ' . $this->task['main_table'] . '_' . $key;
                $this->fields[$as_name]['title']    = $value;
                $this->fields[$as_name]['discerns'] = $this->task['field_config']['discerns'][$key];
                $this->fields[$as_name]['scheme']   = $this->task['field_config']['scheme'][$key];
                $this->fields[$as_name]['table']    = $this->task['main_table'];
                $this->fields[$as_name]['field']    = $this->task['main_table'] . '_' . $key;
            }
        }

        if (isset($this->task['join_table']) && is_array($this->task['join_table'])) {
            foreach ($this->task['join_table'] as $key => $value) {
                if (isset($this->task['join_table'][$key]['fields']) && isset($this->task['join_table'][$key]['fields']['title'])) {
                    $join_table_name = $value['join_as'] ? $value['join_as'] : $value['table'];
                    foreach ($this->task['join_table'][$key]['fields']['title'] as $fkey => $fvalue) {
                        $as_name                            = $join_table_name . '.' . $fkey . ' as ' . $join_table_name . '_' . $fkey;
                        $this->fields[$as_name]['title']    = $fvalue;
                        $this->fields[$as_name]['discerns'] = $this->task['join_table'][$key]['fields']['discerns'][$fkey];
                        $this->fields[$as_name]['scheme']   = $this->task['join_table'][$key]['fields']['scheme'][$fkey];
                        $this->fields[$as_name]['table']    = $join_table_name;
                        $this->fields[$as_name]['field']    = $join_table_name . '_' . $fkey;
                    }
                }
            }
        }
    }

    public static function assignment($field_value, $scheme)
    {
        if ($scheme) {
            $scheme = explode(',', $scheme);
            foreach ($scheme as $key => $value) {
                list($item_key, $item_value) = explode('=', $value);
                if ($item_key == $field_value) {
                    return $item_value;
                }
            }
        } else {
            return $field_value;
        }
    }

    public static function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function getXlsTitle()
    {
        foreach ($this->fields as $key => $value) {
            $title[] = $value['title'] ? $value['title'] : $key;
        }
        return $title;
    }

    /**
     * 构建SQL
     * @param string $type count=直接查询总数,limit=读取指定范围的记录,test=测试SQL(读取前10条记录)
     * @param array $limit [mim, max]查询范围
     * @return string        SqlString
     */
    public function buildSql($build_type = 'count', $limit = [0, 10])
    {
        $field      = '';// 字段
        $join_table = []; // 关联表
        $where      = [];
        $order      = [];

        // 要查询的字段
        foreach ($this->fields as $key => $value) {
            $field .= $key . ',';
        }
        $field = trim($field, ',');

        // 关联表
        if (isset($this->task['join_table']) && is_array($this->task['join_table'])) {
            foreach ($this->task['join_table'] as $key => $value) {

                if ($value['table'] && $value['foreign_key'] && $value['local_key']) {
                    $join_table_name = $value['join_as'] ? $value['join_as'] : $value['table'];

                    $join      = $value['join_as'] ? $value['table'] . ' ' . $value['join_as'] : $value['table'];
                    $condition = vsprintf('%s.%s = %s.%s', [
                        $this->task['main_table'],
                        $value['foreign_key'],
                        $join_table_name,
                        $value['local_key']
                    ]);

                    $join_table[] = [$join, $condition, $value['join_type']];
                }
            }
        }


        // 筛选
        if (isset($this->task['where_field']['op']) && is_array($this->task['where_field']['op'])) {
            foreach ($this->task['where_field']['op'] as $key => $expression) {
                if (isset($this->task['where_field']['condition'][$key])) {
                    $where[$key] = [$expression, $this->task['where_field']['condition'][$key]];
                }
            }
        }

        // 排序
        if ($this->task['order_field'] && $this->task['order_type']) {
            $order = [
                $this->task['order_field'] => $this->task['order_type']
            ];
        }

        $res = Db::table($this->task['main_table'])->field($field)->join($join_table)->where($where);

        if ($build_type == 'count') {
            return $res->count();
        } elseif ($build_type == 'limit') {
            return $res->order($order)->limit($limit[0], $limit[1])->select(false);
        } elseif ($build_type == 'test') {
            return $res->order($order)->limit(10)->select(false);
        }

    }

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return ExportLib
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->_error ? __($this->_error) : '';
    }
}