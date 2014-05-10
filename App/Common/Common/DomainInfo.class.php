<?php

namespace Common\Common;

class DomainInfo
{
    private $timeout;
    private $result_string = '';
    private $result;
    private $format_result;

    public function __construct($domain_name = NULL)
    {
        if($domain_name !== NULL)
        {
            $this->lookup($domain_name);
        }
    }


    public function lookup($p_domain_name)
    {

        $domain_name = $p_domain_name;
        if( !empty($domain_name) )
        {
            $pageURL = "http://whoisproxy.alichacha.com/?domain_name=".rawurlencode($domain_name);
            $this->result_string = $this->_url($pageURL);
            $this->result = json_decode($this->result_string);

        }

        $this->formatting();
    }

    public function get_string()
    {
        return $this->result_string;
    }

    public function _url($Date)
    {
        $ch = curl_init();
        //$timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, "$Date");
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $contents = curl_exec($ch);
        curl_close($ch);
        return $contents;
    }

    private function formatting()
    {
        $this->format_result["registered"] = $this->is_registered();
        $this->format_result["domain_name"] = $this->get_domain_name();
        $this->format_result["owner_email"] = $this->get_owner_email();
        $this->format_result["owner_name"] = $this->get_owner_name();
        $this->format_result["owner_organization"] = $this->get_owner_organization();
        $this->format_result["registrar"] = $this->get_registrar();
        $this->format_result["whois_server"] = $this->get_whois_server();
        $this->format_result["creation_date"] = $this->get_creation_date();
        $this->format_result["expiration_date"] = $this->get_expiration_date();
        $this->format_result["updated_date"] = $this->get_updated_date();
        $this->format_result["status"] = $this->get_status();
        $this->format_result["nameserver"] = $this->get_nameserver();
        $this->format_result["rawdata"] = $this->get_rawdata();
        $this->format_result["rawdata_array"] = $this->get_rawdata_array();
        return $this->format_result;
    }

    public function get_field($p_field = '')
    {
        if(isset($this->format_result[$p_field]))
            return $this->format_result[$p_field];

        return '';
    }

    public function get_all_data()
    {
        return $this->format_result;
    }

    public function is_registered()
    {
        if(isset($this->result->registered))
            return $this->result->registered;
        return false;
    }

    public function get_domain_name()
    {
        if(isset($this->result->name))
            return $this->result->name;
        return "";
    }

    public function get_owner_email()
    {
        if(isset($this->result->contacts->owner[0]->email))
            return $this->result->contacts->owner[0]->email;
        return "";
    }

    public function get_owner_name()
    {
        if(isset($this->result->contacts->owner[0]->name))
            return $this->result->contacts->owner[0]->name;
        return "";
    }

    public function get_owner_organization()
    {
        if(isset($this->result->contacts->owner[0]->organization))
            return $this->result->contacts->owner[0]->organization;
        return "";
    }

    public function get_registrar()
    {
        if(isset($this->result->registrar->name))
            return $this->result->registrar->name;
        return "";
    }

    public function get_whois_server()
    {
        if(isset($this->result->whoisserver))
            return $this->result->whoisserver;
        return "";
    }

    public function get_creation_date()
    {
        if(isset($this->result->created))
            return date("Y-m-d",strtotime($this->result->created));
        return "";
    }

    public function get_expiration_date()
    {
        if(isset($this->result->expires))
            return date("Y-m-d",strtotime($this->result->expires));
        return "";
    }

    public function get_updated_date()
    {
        if(isset($this->result->changed))
            return date("Y-m-d",strtotime($this->result->changed));
        return "";
    }

    public function get_status()
    {
        if(!empty($this->result->status))
        {

            return $this->result->status;

        }

        return '';
    }

    public function get_nameserver()
    {
        if(!empty($this->result->nameserver))
        {
            return $this->result->nameserver;
        }

        return '';
    }

    public function get_rawdata()
    {
        if(!empty($this->result->rawdata))
        {
            return $this->result->rawdata;

        }

        return '';
    }

    public function get_rawdata_array()
    {
        if(!empty($this->result->rawdata_array))
        {
            return $this->result->rawdata_array;

        }

        return '';
    }


}

/* End of file domain_info.php */
/* Location: ./application/controllers/.php */