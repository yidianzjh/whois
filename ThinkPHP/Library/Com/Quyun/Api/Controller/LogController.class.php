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
namespace Com\Quyun\Api\Controller;

use Com\Quyun\Api\Controller,
    Com\Quyun\Api\Model\LogModel,
    Com\Quyun\Api\Model\LogViewModel,
    Com\Quyun\Api\Param;

class LogController extends Controller
{
    // 查询列表操作配置，可在继承后做修改
    protected $_listWhereRules  = null; // 自定义查询列表的where构建规则
    protected $_listOptions     = null; // 自定义查询列表选项

    // 查询详情操作配置，可在继承后做修改
    protected $_detailWhereRules  = null; // 自定义查询详情的where构建规则
    protected $_detailOptions     = null; // 自定义查询详情选项

    // 删除操作配置，可在继承后做修改
    protected $_deleteWhereRules  = null; // 自定义删除操作的where构建规则
    protected $_deteteOptions     = null; // 自定义删除选项

    // 查询API请求日志列表
    public function listAction()
    {
        $buildRules = array(
            'request_id'        =>  'RequestId',
            'request_method'    =>  'RequestMethod',
            'app_id'            =>  'AppId',
            'action'            =>  'Action',
            'raw_action'        =>  'RawAction',
            'code'              =>  'Code',
            'language'          =>  'Language',
            'isdebug'           =>  array('IsDebug', array('Enum', array('Enum'=>array('Y','N')))),
            'ipaddress'         =>  array(  // 请求来源IP地址
                                        'Param' => 'IpAddress',
                                        'Operator' => 'Like',
                                    ),
            'request_time'      =>  array(  // 请求时间，时间戳
                                        'Param' => array('RequestTime', array('Range', array('ItemType'=>'Timestamp'))),
                                        'Operator' => 'Between',
                                    ),
            'elapse_time'       =>  array(  // 消耗时间，整型，单位为微秒
                                        'Param' => array('ElapseTime', 'Range'),
                                        'Operator' => 'Between',
                                    ),
        );
        if (is_array($this->_listWhereRules)) $buildRules = array_merge($buildRules, $this->_listWhereRules);
        $where = Param::buildWhere($buildRules);

        $log = new LogModel;
        if (is_array($this->_listOptions)) $log->setListOptions($this->_listOptions);

        return $log->apiList($where);
    }

    // 查看单条请求日志的详细信息
    public function detailAction()
    {
        $buildRules = array(
            'request_id'    =>  array('RequestId', array('String', null, true)),
        );
        if (is_array($this->_detailWhereRules)) $buildRules = array_merge($buildRules, $this->_detailWhereRules);
        $where = Param::buildWhere($buildRules);

        $log = new LogViewModel;
        if (is_array($this->_detailOptions)) $log->setDetailOptions($this->_detailOptions);

        return $log->apiDetail($where);
    }

    // 删除某个时间点之前的API请求日志
    public function deleteAction()
    {
        // 最大只允许清除30天的日志
        $max = $_SERVER['REQUEST_TIME'] - 86400*30;
        $buildRules = array(
            'request_time'    =>  array('RequestTime', array('Timestamp', array('Max'=>$max), true)),
        );
        if (is_array($this->_deleteWhereRules)) $buildRules = array_merge($buildRules, $this->_deleteWhereRules);
        $where = Param::buildWhere($buildRules);

        $log = new LogModel;
        if (is_array($this->_deteteOptions)) $log->setDeleteOptions($this->_deteteOptions);

        return $log->apiDelete($where);
    }
}