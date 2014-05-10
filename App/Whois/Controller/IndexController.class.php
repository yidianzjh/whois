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
        $domainName = I('DomainName');
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
        $sqlData['update_time'] = 0;
        $domainInfoModel = new DomainInfoModel();
        $domainInfoModel->apiCreate($sqlData);
        return ($data);
    }
}