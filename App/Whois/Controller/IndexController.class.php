<?php

namespace Whois\Controller;

use Com\Quyun\Api\Controller;
use Common\Common\DomainInfo;
use Whois\Model\DomainInfoModel;

class IndexController extends Controller
{
    public function indexAction()
    {
        $config['css_path'] = '/css/';
        $config['image_path'] = '/images/';
        $config['js_path'] = '/js/';
        $this->assign($config);
        $this->display('head');
        $this->display('index');
        $this->display('foot');
    }

    public function searchAction()
    {
        $domainName = I('DomainName', '', '');
        if (!$domainName)
            APIE('MissingParam:DomainName');

        $domainInfoModel = new DomainInfoModel;
        $where['name'] = $domainName;
        $DI = $domainInfoModel->where($where)->find();
        if ($DI)
        {
            if ((time() - intval($DI['update_time'])) > 3600*24*7)
            {
                $domainInfo = new DomainInfo($domainName);
                $data = $domainInfo->get_all_data();
                if ($data['registered'] == false)
                {
                    $sqlData['is_registered'] = 'N';
                }
                else
                {
                    $sqlData['is_registered'] = 'Y';
                }
                $sqlData['name'] = $data['domain_name'];
                $sqlData['update_time'] = time();
                $domainInfoModel->apiUpdate($sqlData, $where);
                return ($data);
            }
            return $DI;
        }
        else
        {
            $domainInfo = new DomainInfo($domainName);
            $data = $domainInfo->get_all_data();
            if ($data['registered'] == false)
            {
                $sqlData['is_registered'] = 'N';
            }
            else
            {
                $sqlData['is_registered'] = 'Y';
            }
            $sqlData['name'] = $data['domain_name'];
            $sqlData['create_time'] = time();
            $sqlData['update_time'] = time();
            $domainInfoModel->apiCreate($sqlData, true);
            echo ($domainInfoModel->getLastSql());
            return ($data);
        }
    }
}