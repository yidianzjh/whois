<?php
//error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
require_once 'conn.php';
class domain_info{
    private $db;
    private $result;
    private $result_array;
    private $n=0;
    private $count=0;
    private $page=0; //0为一页
    private $page_size=20;
    public function __construct()
    {
        $this->db=new db();
    }
    public function queryById($id=0)
    {
        if($id!=0)
        {
            $sql="SELECT * FROM `domain_info` WHERE `id`=".$id." ";
            $result=$this->db->query($sql);
            $this->n=0;
            $this->result_array=$result;
            $this->result=$result[0];

        }
        else
        {
            return false;
        }

    }
    public function countByKey($key=" ")
    {
        $sql="SELECT COUNT(*) as recordsCount FROM `domain_info` WHERE (`domain_name` LIKE '%".$key."%' OR `owner_email` LIKE '%".$key."%' OR `owner_name` LIKE '%".$key."%' )";

        $result=$this->db->query($sql);
        //$count=$result[0]->recordsCount;
        //echo "<br/>count=".$result[0]->recordsCount;
        //var_dump($result[0]->recordsCount);
        return $this->count=$result[0]->recordsCount;

    }
    public function pageByKey($key=" ")
    {
        if($this->count == 0)
        {
            $this->countByKey($key=" ");
        }
        else
        {
            $this->page=intval($this->count/$this->page_size);
            if(($this->count%$this->page_size)==0)
            {
                $this->page-=1;
            }
            //echo "\$this->count%\$this->page_size=".$this->count%$this->page_size;
        }
        return $this->page;

    }
    public function getPageSize()
    {
        return $this->page_size;
    }
    public function queryByKey($key=" ",$page=0)
    {
        if($page>$this->page)
            $page=$this->page;
        $sql="SELECT `domain_name`,`registrar`,`owner_organization`,`creation_date`,`expiration_date` FROM `domain_info` WHERE (`domain_name` LIKE '%".$key."%' OR `owner_email` LIKE '%".$key."%' OR `owner_name` LIKE '%".$key."%' )";
        $sql.=" LIMIT ".$this->page_size*$page.",".$this->page_size*($page+1)." ";
        $this->n=0;
        //echo $sql;
        $result=$this->db->query($sql);
        $this->result_array=$result;
        $this->result=$result[0];
        //var_dump($this->result);

    }
    public function getNextRecord()
    {
        if(isset($this->result_array[$this->n]))
        {
            $this->result=$this->result_array[$this->n];
            $this->n+=1;
            return true;
        }
        return false;
    }
    public function getId()
    {
        if(isset($this->result->id))
            return $this->result->id;
        return null;
    }
    public function getDomainName()
    {
        if(isset($this->result->domain_name))
            return $this->result->domain_name;
        return null;
    }
    public function getOwnerEmail()
    {
        if(isset($this->result->owner_email))
            return $this->result->owner_email;
        return null;
    }
    public function getOwnerName()
    {
        if(isset($this->result->owner_name))
            return $this->result->owner_name;
        return null;
    }
    public function getOwnerOrganization()
    {
        if(isset($this->result->owner_organization))
            return $this->result->owner_organization;
        return null;
    }
    public function getRregistrar()
    {
        if(isset($this->result->registrar))
            return $this->result->registrar;
        return null;
    }
    public function getWhoisServer()
    {
        if(isset($this->result->whois_server))
            return $this->result->whois_server;
        return null;
    }
    public function getCreationDate()
    {
        if(isset($this->result->creation_date))
            return date("Y-m-d",$this->result->creation_date);
        return null;
    }
    public function getExpirationDate()
    {
        if(isset($this->result->expiration_date))
            return date("Y-m-d",$this->result->expiration_date);
        return null;
    }
    public function getUpdatedDate()
    {
        if(isset($this->result->updated_date))
            return date("Y-m-d",$this->result->updated_date);
        return null;
    }
    public function getStatus()
    {
        if(isset($this->result->status))
            return $this->result->status;
        return null;
    }
    public function getNameservers()
    {
        if(isset($this->result->nameservers))
            return $this->result->nameservers;
        return null;
    }
    public function getMd5()
    {
        if(isset($this->result->md5))
            return $this->result->md5;
        return null;
    }
    public function getQueryTime()
    {
        if(isset($this->result->query_time))
            return date("Y-m-d",$this->result->query_time);
        return null;
    }
    public function getAll()
    {
        $this->getRawdata();
        $this->result=(object)($this->result);
        //echo "<br />this-result=";
        //var_dump($this->result);
        return $this->result;
    }
    public function getRawdata()
    {
        if(isset($this->result->rawdata))
            return $this->result->rawdata;
        $id=$this->getId();
        $sql="SELECT * FROM `registrar_info` WHERE `id`=".$id." ";
        //echo $sql;
        $result=$this->db->query($sql);
        //echo "<br/>3333333333=";
        //var_dump($result[0]);
        if(isset($result[0]->content))
        {
            //get_object_vars
            //$obj =
            //$obj = get_object_vars($this->result);
            //echo "<br/>obj=";
            //var_dump($obj);
            //$obj["rawdata"]=$result[0]->content;
            //$this->result=json_encode($obj);
            $this->result->rawdata=$result[0]->content;
            //echo "<br/>11111111=";
            //var_dump($this->result);
            //echo "<br/>7777777=";
            //echo $this->result->rawdata;
            //echo "<br/>7777777=";
            return $this->result->rawdata;
        }
        //echo "<br/>2222222=";
        return null;
    }

}
