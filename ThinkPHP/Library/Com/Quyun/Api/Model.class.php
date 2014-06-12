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

use Think\Model as Think_Model;

/**
 * API模型基类
 */
class Model extends Think_Model
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
}
