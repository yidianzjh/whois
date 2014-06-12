<?php
// +----------------------------------------------------------------------
// | Quyun API Suite
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.quyun.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 江林锦 <jianglj@quyun.com>
// +----------------------------------------------------------------------
namespace Com\Quyun\Api;

use Think\Model\ViewModel as Think_ViewModel;

/**
 * API视图模型基类
 */
class ViewModel extends Think_ViewModel
{
    use ModelTrait;

    public function __construct($name='',$tablePrefix='',$connection='')
    {
        parent::__construct($name, $tablePrefix, $connection);

        if ($this->_autoGenMap)
        {
            $dbFields = $this->getDbFields();
            if ($dbFields)
            {
                $autoMap = array();
                foreach ($dbFields as $dbField)
                {
                    $fieldParts = explode('_', $dbField);
                    $fieldParts = array_map('ucfirst', $fieldParts);
                    $pascalField = implode('', $fieldParts);
                    $autoMap[$pascalField] = $dbField;
                }

                if ($autoMap)
                {
                    $this->_map = array_flip(array_merge(array_flip($autoMap), array_flip($this->_map)));
                }
            }
        }
    }

    /**
     * 获取数据表字段信息
     * @access public
     * @return array
     */
    public function getDbFields(){
    	$viewFields = array();
        foreach ($this->viewFields as $tableFields)
    	{
    		unset($tableFields['_on'], $tableFields['_table'], $tableFields['_type'], $tableFields['_as']);
    		$viewFields = array_merge($viewFields, $tableFields);
    	}
        return array_unique($viewFields);
    }

    // 取得主表表名信息
    // 返回 array($tableName, $tableAlias)
    protected function getMainTable()
    {
        $leftTables = array();
        foreach ($this->viewFields as $tableAlias=>$fields)
        {
            if ($fields['_type'] == 'LEFT')
            {
                $leftTables[] = $tableAlias;
            }
        }

        if (count($leftTables) > 0)
        {
            // 有多个left有，取第一个
            $tableAlias = $leftTables[0];
        }
        else
        {
            // 全是inner join，取定义中的第一个
            $tableAlias = key($this->viewFields);
        }
        return array($this->viewFields[$tableAlias]['_table'], $tableAlias);
    }
}
