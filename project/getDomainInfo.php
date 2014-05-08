<?php
class domainInfo{
    private $Parser;
    private $result;
    public function __construct()
    {
        require_once 'DomainParser/Parser.php';
        require_once 'WhoisParser/Parser.php';

        $this->Parser = new Novutec\WhoisParser\Parser();
        $this->Parser->setFormat('object');
        $this->result = $this->Parser->lookup($_GET["domainName"]);
    }
    public function isRegistered()
    {
        if(isset($this->result->registered))
            return $this->result->registered;
        return false;
    }
    public function getDomainName()
    {
        if(isset($this->result->name))
            return $this->result->name;
        return "";
    }
    public function getOwnerEmail()
    {
        if(isset($this->result->contacts->owner[0]->email))
            return $this->result->contacts->owner[0]->email;
        return "";
    }
    public function getOwnerName()
    {
        if(isset($this->result->contacts->owner[0]->name))
            return $this->result->contacts->owner[0]->name;
        return "";
    }
    public function getOwnerOrganization()
    {
        if(isset($this->result->contacts->owner[0]->organization))
            return $this->result->contacts->owner[0]->organization;
        return "";
    }

    public function getRregistrar()
    {
        if(isset($this->result->registrar->name))
            return $this->result->registrar->name;
        return "";
    }
    public function getWhoisServer()
    {
        if(isset($this->result->whoisserver))
            return $this->result->whoisserver;
        return "";
    }
    public function getCreationDate()
    {
        if(isset($this->result->created))
            return date("Y-m-d",strtotime($this->result->created));
        return "";
    }
    public function getExpirationDate()
    {
        if(isset($this->result->expires))
            return date("Y-m-d",strtotime($this->result->expires));
        return "";
    }
    public function getUpdatedDate()
    {
        if(isset($this->result->changed))
            return date("Y-m-d",strtotime($this->result->changed));
        return "";
    }
    public function getStatus()
    {
        $status="";
        if(is_array($this->result->status))
        {
            foreach($this->result->status as $tmp )
            {
                $status.=$tmp."<br />";
            }
        }
        else
        {
            if(isset($this->result->status))
                $status=$this->result->status;
            else
                $status="";
        }
        return $status;
    }
    public function getNameservers()
    {
        $nameserver="";
        if(is_array($this->result->nameserver))
        {
            foreach($this->result->nameserver as $tmp )
            {
                $nameserver.=$tmp."<br />";
            }
        }
        else
        {
            if(isset($this->result->nameserver))
                $nameserver =$this->result->nameserver;
            else
                $nameserver="";
        }
        return $nameserver;
    }
    public function getRawdata()
    {
        $rawdata="";
        if(is_array($this->result->rawdata))
        {
            foreach($this->result->rawdata as $tmp )
            {
                $rawdata.=$tmp."<br />";
            }
        }
        else
        {
            if(isset($this->result->rawdata))
                $rawdata=$this->result->rawdata;
            else
                $rawdata="";
        }
        return $rawdata;
    }
    public function getRawdataArray()
    {
        $rawdata_array="";
        if(is_array($this->result->rawdata_array))
        {
            foreach($this->result->rawdata_array as $tmp )
            {
                $rawdata_array.=$tmp."<br />";
            }
        }
        else
        {
            if(isset($this->result->rawdata_array))
                $rawdata_array=$this->result->rawdata_array;
            else
                $rawdata_array="";
        }
        return $rawdata_array;
        //return $this->result->rawdata_array;
    }
    public function getAllData()
    {
        return $this->result;
    }
}


?>