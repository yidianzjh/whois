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
    Com\Quyun\Api\Model\AppModel,
    Com\Quyun\Api\Param;

class AppController extends Controller
{
    // 查询应用列表
    public function listAction()
    {
        $where = Param::buildWhere(array(
            'app_id'    =>  'AppId',
            'app_name'  =>  array(
                                'Param' => 'AppName',
                                'Operator' => 'Like',
                            ),
        ));

        $app = new AppModel;
        return $app->apiList($where);
    }

    // 查看单个应用的详细信息
    public function detailAction()
    {
        $where = Param::buildWhere(array(
            'app_id'    => array('AppId', array('DigitString')),
        ));

        $app = new AppModel;
        return $app->apiDetail($where);
    }

    // 创建应用
    public function createAction()
    {
        $data = Param::buildData(array(
            'app_id'    => array('AppId', array('DigitString', null, false)),
            'app_name'  => array('AppName', array('String', array('MaxLength'=>100), true)),
            'app_key'   => array('AppKey', array('String', array('MaxLength'=>100), true)),
        ));

        $app = new AppModel;
        return $app->apiCreate($data);
    }

    // 更新应用信息
    public function updateAction()
    {
        $where = Param::buildWhere(array(
            'app_id'    => array('AppId', array('DigitString')),
        ));
        $data = Param::buildData(array(
            'app_name'  => array('AppName', array('String', array('MaxLength'=>100), false)),
            'app_key'   => array('AppKey', array('String', array('MaxLength'=>100), false)),
        ));

        $app = new AppModel;
        return $app->apiUpdate($data, $where);
    }

    // 删除某个应用
    public function deleteAction()
    {
        $where = Param::buildWhere(array(
            'app_id'    => array('AppId', array('DigitString')),
        ));

        $app = new AppModel;
        return $app->apiDelete($where);
    }
}