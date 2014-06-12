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

/**
 * API模型特征Trait类
 */
trait ModelTrait
{
    // 是否自动生成$_map
    protected $_autoGenMap = true;

    // 操作默认选项
    private $_apiListDefaultOptions = array(    // List操作
        // 分页相关参数
        'DefaultOrder'          => 'asc',       // 接口不传入排序方向时，默认的排序方向
        'DefaultOrderBy'        => null,        // 接口不传入排序字段时，默认使用的排序字段
        'DefaultPageNumber'     => 1,           // 接口不传入页码时，默认使用的页码
        'DefaultPageSize'       => 10,          // 接口不传入页面大小时，默认使用的页面大小
        'AllowOrders'           => array('asc','desc','ASC','DESC'),    // 排序方向允许的值
        'AllowOrderBys'         => null,        // 排序字段允许的值，为null时，将判断字段是否属于Model
        'AllowPageSize'         => array(1, 100),   // 允许的页面大小的范围
        'PaginationParamReplace'=> null,        // 分页参数名称替换规则，格式为array('默认参数名'=>'自定义参数名')

        // 返回数据相关参数
        'RowsKey'               => 'Rows',      // 返回结果集的键名
        'ExcludeFields'         => null,        // 从模型中获取时，排除哪些字段，支持使用逗号分隔的字段字符串或字段数组
        'ResultParamReplace'    => null,        // 返回结果参数名称替换规则，格式为array('默认参数名'=>'自定义参数名')
    );
    private $_apiDetailDefaultOptions = array(  // Detail操作
        'RowKey'                => 'Row',       // 返回结果键名，null表示不使用键名
        'ExcludeFields'         => null,        // 从模型中获取时，排除哪些数据库字段，支持使用逗号分隔的字段字符串或字段数组
    );
    private $_apiCreateDefaultOptions = array(  // Create操作
        'AutoGenId'             => true,        // 是否自动生成$_apiIdField指定的ID字段
        'AutoGenIdLen'          => 16,          // 自动生成的ID字段的长度
        'IdKey'                 => 'Id',        // 返回新记录的键名，null表示不使用键名
    );
    private $_apiUpdateDefaultOptions = array(  // Create操作
        'RowCountKey'           => 'RowCount',  // 返回结果键名，null表示不返回更新的行数
        'ErrorOnZero'           => false,       // 没有记录被更新时，是否返回错误
    );
    private $_apiDeleteDefaultOptions = array(  // Delete操作
        'RowCountKey'           => 'RowCount',  // 返回结果键名，null表示不返回删除的行数
        'ErrorOnZero'           => true,        // 没有记录被删除时，是否返回错误
        'SoftDelete'            => false,       // 是否软删除（设置记录标志位）
        'SoftDeleteData'        => null,        // 软删除时需要修改的数据表的数据
    );

    // 模型默认选项
    protected $_apiFieldsMapEnabled = true; // 是否启用模型的字段映射
    protected $_apiIntFields        = null; // 类型是整型的模型字段，支持使用逗号分隔的字段字符串或字段数组
    protected $_apiIdField          = null; // 唯一标识对象的ID字段在模型中的名称，为null表示无ID字段

    // 自定义操作选项
    protected $_apiListOptions = array();   // List操作
    protected $_apiDetailOptions = array(); // Detail操作
    protected $_apiCreateOptions = array(); // Create操作
    protected $_apiUpdateOptions = array(); // Update操作
    protected $_apiDeleteOptions = array(); // Delete操作

    // 自定义List操作选项
    // 将会合并到现有的_apiListOptions中
    public function setListOptions($options)
    {
        // PaginationParamReplace和ResultParamReplace配置做合并，不做覆盖
        foreach (array('PaginationParamReplace', 'ResultParamReplace') as $configKey)
        {
            if (is_array($this->_apiListOptions[$configKey]) && is_array($options[$configKey]))
            {
                $options[$configKey] = array_merge($this->_apiListOptions[$configKey], $options[$configKey]);
            }
        }
        $this->_apiListOptions = array_merge($this->_apiListOptions, $options);
    }

    // 获取List操作选项
    // 层级结构，配置优先级：
    // setListOptions() > _apiListOptions > API_LIST_DEFAULT_OPTIONS > _apiListDefaultOptions
    public function getListOptions()
    {
        $defaultOptions = $this->_apiListDefaultOptions;

        $cOptions = C('API_LIST_DEFAULT_OPTIONS');
        if (is_array($cOptions)) $defaultOptions = array_merge($defaultOptions, $cOptions);

        $options = $this->_apiListOptions;

        // PaginationParamReplace和ResultParamReplace配置做合并，不做覆盖
        foreach (array('PaginationParamReplace', 'ResultParamReplace') as $configKey)
        {
            if (is_array($defaultOptions[$configKey]))
            {
                if (is_array($options[$configKey]))
                {
                    $options[$configKey] = array_merge($defaultOptions[$configKey], $options[$configKey]);
                }
            }
        }
        return array_merge($defaultOptions, $options);
    }

    // 自定义Detail操作选项
    // 将会合并到现有的_apiDetailOptions中
    public function setDetailOptions($options)
    {
        $this->_apiDetailOptions = array_merge($this->_apiDetailOptions, $options);
    }

    // 获取Detail操作选项
    // 层级结构，配置优先级：
    // setDetailOptions() > _apiDetailOptions > API_DETAIL_DEFAULT_OPTIONS > _apiDetailDefaultOptions
    public function getDetailOptions()
    {
        $defaultOptions = $this->_apiDetailDefaultOptions;

        $cOptions = C('API_DETAIL_DEFAULT_OPTIONS');
        if (is_array($cOptions)) $defaultOptions = array_merge($defaultOptions, $cOptions);

        return array_merge($defaultOptions, $this->_apiDetailOptions);
    }

    // 自定义Create操作选项
    // 将会合并到现有的_apiCreateOptions中
    public function setCreateOptions($options)
    {
        $this->_apiCreateOptions = array_merge($this->_apiCreateOptions, $options);
    }

    // 获取Create操作选项
    // 层级结构，配置优先级：
    // setCreateOptions() > _apiCreateOptions > API_CREATE_DEFAULT_OPTIONS > _apiCreateDefaultOptions
    public function getCreateOptions()
    {
        $defaultOptions = $this->_apiCreateDefaultOptions;

        $cOptions = C('API_CREATE_DEFAULT_OPTIONS');
        if (is_array($cOptions)) $defaultOptions = array_merge($defaultOptions, $cOptions);

        return array_merge($defaultOptions, $this->_apiCreateOptions);
    }

    // 自定义Update操作选项
    // 将会合并到现有的_apiUpdateOptions中
    public function setUpdateOptions($options)
    {
        $this->_apiUpdateOptions = array_merge($this->_apiUpdateOptions, $options);
    }

    // 获取Update操作选项
    // 层级结构，配置优先级：
    // setUpdateOptions() > _apiUpdateOptions > API_UPDATE_DEFAULT_OPTIONS > _apiUpdateDefaultOptions
    public function getUpdateOptions()
    {
        $defaultOptions = $this->_apiUpdateDefaultOptions;

        $cOptions = C('API_UPDATE_DEFAULT_OPTIONS');
        if (is_array($cOptions)) $defaultOptions = array_merge($defaultOptions, $cOptions);

        return array_merge($defaultOptions, $this->_apiUpdateOptions);
    }

    // 自定义Delete操作选项
    // 将会合并到现有的_apiDeleteOptions中
    public function setDeleteOptions($options)
    {
        $this->_apiDeleteOptions = array_merge($this->_apiDeleteOptions, $options);
    }

    // 获取Delete操作选项
    // 层级结构，配置优先级：
    // setDeleteOptions() > _apiDeleteOptions > API_DELETE_DEFAULT_OPTIONS > _apiDeleteDefaultOptions
    public function getDeleteOptions()
    {
        $defaultOptions = $this->_apiDeleteDefaultOptions;

        $cOptions = C('API_DELETE_DEFAULT_OPTIONS');
        if (is_array($cOptions)) $defaultOptions = array_merge($defaultOptions, $cOptions);

        return array_merge($defaultOptions, $this->_apiDeleteOptions);
    }

    // 根据字段名映射
    public function parseRowsFieldsMap($rows)
    {
        foreach ($rows as $i=>$row)
        {
            $rows[$i] = $this->parseFieldsMap($row);
        }
        return $rows;
    }

    // 获取指定模型的模型字段列表
    public function getModelFields($excludeFields=null)
    {
        $fields = $this->_map ? array_keys($this->_map) : array();
        if (is_null($excludeFields)) return $fields;

        if (is_string($excludeFields))
        {
            $excludeFields = explode(',', $excludeFields);
            $excludeFields = array_map('trim', $excludeFields);
        }
        if (is_array($excludeFields))
        {
            $diff = array_diff($fields, $excludeFields);
            $fields = array_intersect($fields, $diff);
        }
        return $fields;
    }

    // 根据指定模型的字段名，获取映射后的数据表字段名
    public function getDbField($modelField)
    {
        return $this->_map && isset($this->_map[$modelField]) ? $this->_map[$modelField] : false;
    }

    // 查询列表操作
    // 查询条件相关参数
    // $scope：指定命名范围，将作为model->scope的参数
    // $where：查询条件，将作为model->where()的参数
    // $return：失败时是否返回false，默认为false，表示抛出异常
    public function apiList($where=null, $scope=null, $return=false)
    {
        $options = $this->getListOptions();

        // 读取API接口参数
        $paramReplace = array(
            'Order'     => 'Order',
            'OrderBy'   => 'OrderBy',
            'PageNumber'=> 'PageNumber',
            'PageSize'  => 'PageSize',
        );
        if (is_array($options['PaginationParamReplace']))
        {
            $paramReplace = array_merge($paramReplace, $options['PaginationParamReplace']);
        }

        // Order参数验证
        $order = I($paramReplace['Order'], $options['DefaultOrder']);
        if ( ! is_null($order))
        {
            if ( ! in_array($order, $options['AllowOrders']))
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramReplace['Order'].':Unrecognized', 'The specified '.$paramReplace['Order'].' is unrecognized!');
            }
        }

        // OrderBy参数验证
        $orderBy = I($paramReplace['OrderBy'], $options['DefaultOrderBy']);
        if ( ! is_null($orderBy))
        {
            $allowOrderBys = is_array($options['AllowOrderBys'])
                 ? $options['AllowOrderBys']
                 : $this->getModelFields($options['ExcludeFields']);
            if ( ! in_array($orderBy, $allowOrderBys))
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramReplace['OrderBy'].':NotAllow', 'The specified '.$paramReplace['OrderBy'].' column is not allowed!');
            }
        }

        // 其它分页参数验证
        $pageNumber = I($paramReplace['PageNumber'], $options['DefaultPageNumber'], 'int');
        $pageSize = I($paramReplace['PageSize'], $options['DefaultPageSize'], 'int');
        list($minAllow, $maxAllow) = $options['AllowPageSize'];
        if ($pageSize < $minAllow || $pageSize > $maxAllow)
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramReplace['PageSize'].':NotAllow', 'The specified '.$paramReplace['PageSize'].' is not allowed!');
        }

        // 返回参数替换
        $paramReplace = array(
            'TotalCount'    => 'TotalCount',
            'PageCount'     => 'PageCount',
            'PageNumber'    => 'PageNumber',
            'PageSize'      => 'PageSize',
            'Rows'          => 'Rows',
        );
        if (is_array($options['ResultParamReplace']))
        {
            $paramReplace = array_merge($paramReplace, $options['ResultParamReplace']);
        }

        $totalCount = null;
        if (is_string($paramReplace['TotalCount']) || is_string($paramReplace['PageCount']))
        {
            // 计算记录总数
            if ( ! is_null($scope)) $this->scope($scope);
            if ( ! is_null($where)) $this->where($where);
            $totalCount = $this->count();
        }

        // 如果总数为0，则不再执行select操作以提升性能
        //if ($totalCount === 0)
        {
            if (method_exists($this, 'getMainTable'))
            {
                // 视图模型
                list($tableName, $tableAlias) = $this->getMainTable();
                $this->table($tableName.' '.$tableAlias);
            }
            else
            {
                // 普通模型
                $tableName = $tableAlias = $this->getTableName();
            }

            // 分页子查询，仅取出主键
            $pk = $this->getPk();
            if ( ! is_null($scope)) $this->scope($scope);
            if ( ! is_null($where)) $this->where($where);
            if ( ! is_null($orderBy)) $this->order($this->getDbField($orderBy).' '.$order);
            $subQuery = $this->field("{$pk} AS {$pk}_t")->page($pageNumber, $pageSize)->select(false);

            // 根据主键值关联取出记录
            $tempTableName = $tableAlias.'_t';
            if ( ! is_null($options['ExcludeFields'])) $this->field($options['ExcludeFields'], true);
            if ( ! is_null($orderBy)) $this->order($this->getDbField($orderBy).' '.$order);
            $rows = $this->join("{$subQuery} `{$tempTableName}` ON `{$tempTableName}`.`{$pk}_t`={$tableAlias}.`{$pk}`")->select();
            if ($rows === false) return array();

            // 字段名映射
            if ($this->_apiFieldsMapEnabled)
            {
                $rows = $this->parseRowsFieldsMap($rows);
            }

            // 整型处理
            if ( ! is_null($this->_apiIntFields))
            {
                $intFields = $this->_apiIntFields;
                if (is_string($intFields)) $intFields = explode(',', $intFields);
                foreach ($rows as $i=>$row)
                {
                    foreach ($intFields as $intField)
                    {
                        if ( ! isset($row[$intField])) continue;
                        $rows[$i][$intField] = (int)$row[$intField];
                    }
                }
            }
        }

        $result = array();
        if (is_string($paramReplace['TotalCount'])) $result[$paramReplace['TotalCount']] = $totalCount;
        if (is_string($paramReplace['PageCount']))
        {
            $pageCount = (int)(($totalCount+$pageSize-1)/$pageSize);
            $result[$paramReplace['PageCount']] = $pageCount;
        }
        if (is_string($paramReplace['PageNumber'])) $result[$paramReplace['PageNumber']] = $pageNumber;
        if (is_string($paramReplace['PageSize'])) $result[$paramReplace['PageSize']] = $pageSize;

        $rowsKey = $options['RowsKey'];
        if ( ! is_string($rowsKey)) $rowsKey = 'Rows';
        $result[$rowsKey] = $rows;

        return $result;
    }

    // 查询详情操作
    // $return：失败时是否返回false，默认为false，表示抛出异常
    public function apiDetail($where, $scope=null, $return=false)
    {
        $options = $this->getDetailOptions();

        // 读取记录
        if ( ! is_null($scope)) $this->scope($scope);
        if ( ! is_null($options['ExcludeFields'])) $this->field($options['ExcludeFields'], true);
        $row = $this->where($where)->find();

        if ( ! $row)
        {
            if ($return) return false;
            APIE('ObjectNotExist', 'Object does not exist!');
        }

        // 字段名映射
        if ($this->_apiFieldsMapEnabled)
        {
            $row = $this->parseFieldsMap($row);
        }

        // 整型处理
        if ( ! is_null($this->_apiIntFields))
        {
            $intFields = $this->_apiIntFields;
            if (is_string($intFields)) $intFields = explode(',', $intFields);
            foreach ($intFields as $intField)
            {
                if ( ! isset($row[$intField])) continue;
                $row[$intField] = (int)$row[$intField];
            }
        }

        $result = array();
        if (is_string($options['RowKey']))
        {
            $result[$options['RowKey']] = $row;
        }
        else
        {
            $result = $row;
        }

        return $result;
    }

    // 创建操作
    // $return：失败时是否返回false，默认为false，表示抛出异常
    public function apiCreate($data, $return=false)
    {
        $options = $this->getCreateOptions();

        // 检查ID是否冲突
        if ( ! is_null($this->_apiIdField))
        {
            $idField = $this->getDbField($this->_apiIdField);
            if ( ! isset($data[$idField]))
            {
                // 自动生成不重复的ID
                if ($options['AutoGenId'])
                {
                    $data[$idField] = $this->getUniqId($idField, $options['AutoGenIdLen']);
                    if ($data[$idField] === false)
                    {
                        if ($return) return false;
                        APIE('InternalError:GenIdFailed', 'Generate unique ID failed!');
                    }
                }
            }
            else
            {
                // 检查传入的ID是否重复
                if ($this->where(array($idField=>$data[$idField]))->count() > 0)
                {
                    if ($return) return false;
                    APIE('ObjectDuplicated:'.$this->_apiIdField, $this->_apiIdField.' already exists!');
                }
            }
        }

        // 插入数据
        if ($data)
        {
            $this->create($data);   // 执行Model中的自动操作
            $insertId = $this->add();
        }
        else
        {
            $insertId = false;
        }
        if ($insertId === false)
        {
            if ($return) return false;
            APIE('InternalError:InsertFailed', 'Insert failed!');
        }

        // 决定要返回的ID
        if ( ! is_null($this->_apiIdField))
        {
            $insertId = $data[$idField];
        }

        $result = array();
        if (is_string($options['IdKey']))
        {
            $result[$options['IdKey']] = $insertId;
        }
        else
        {
            $result = $insertId;
        }

        return $result;
    }

    // 更新操作
    // $return：失败时是否返回false，默认为false，表示抛出异常
    public function apiUpdate($data, $where, $scope=null, $return=false)
    {
        $options = $this->getUpdateOptions();

        // 检查记录是否存在
        if ( ! is_null($scope)) $this->scope($scope);
        if ($this->where($where)->count() == 0)
        {
            // 如果条件仅为一条，且为ID字段
            if (count($where) == 1 && key($where) == $this->getDbField($this->_apiIdField))
            {
                if ($return) return false;
                APIE('ObjectNotExist', 'Object does not exist!');
            }
            else if ($options['ErrorOnZero'])
            {
                if ($return) return false;
                APIE('NoObjectUpdated', 'No object is updated!');
            }
        }

        // 更新数据
        if ( ! is_null($scope)) $this->scope($scope);
        if ($data)
        {
            $this->create($data);   // 执行Model中的自动操作
            $rowCount = $this->where($where)->save();
            if ($rowCount === false)
            {
                if ($return) return false;
                APIE('InternalError:UpdateFailed', 'Update failed!');
            }
        }
        else
        {
            $rowCount = 0;
        }
        if ($rowCount === 0 && $options['ErrorOnZero'])
        {
            if ($return) return false;
            APIE('NoObjectUpdated', 'No object is updated!');
        }

        $result = array();
        if (is_string($options['RowCountKey']))
        {
            $result[$options['RowCountKey']] = $rowCount;
        }

        return $result;
    }

    // 删除操作
    // $return：失败时是否返回false，默认为false，表示抛出异常
    public function apiDelete($where, $scope=null, $return=false)
    {
        $options = $this->getDeleteOptions();

        // 删除记录
        if ( ! is_null($scope)) $this->scope($scope);
        if ($options['SoftDelete'] && is_array($options['SoftDeleteData']))
        {
            $rowCount = $this->where($where)->save($options['SoftDeleteData']);
        }
        else
        {
            $rowCount = $this->where($where)->delete();
        }

        if ($rowCount == 0 && $options['ErrorOnZero'])
        {
            if ($return) return false;
            APIE('NoObjectDeleted', 'No object is deleted!');
        }

        $result = array();
        if (is_string($options['RowCountKey']))
        {
            $result[$options['RowCountKey']] = $rowCount;
        }

        return $result;
    }

    // 根据表字段，获取一个不重复的ID值
    public function getUniqId($idField, $idLen=16, $maxTries=10)
    {
        $retries = 0;

        while (1)
        {
            // 生成一个不重复的编号
            $id = '';
            for ($i=0; $i < $idLen; $i++)
            {
                $id .= chr(mt_rand(0x30, 0x39));
            }

            $where[$idField] = $id;
            $rs = $this->field($idField)->where($where)->find();

            if ( ! $rs) return $id;
            if (++$retries > $maxTries) return false;
        }
    }
}
