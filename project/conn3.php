<?php
include 'getDomainInfo.php';
$DI=new domainInfo();
$domain_name=$DI->getDomainName();
//echo $domain_name."<br />";

$registrar=$DI->getRegistrar();
//echo $registrar."<br />";

$whois_server=$DI->getWhoisServer();
//echo $whois_server."<br />";

$name_server=$DI->getNameservers();

$status=$DI->getStatus();
//echo $status."<br />";

$updated_date=$DI->getUpdatedDate();
//echo $updated_date."<br />";

$creation_date=$DI->getCreationDate();
//echo $creation_date."<br />";

$expiration_date=$DI->getExpirationDate();
//echo $expiration_date."<br />";

$owner_name= $DI->getOwnerName();

$owner_organization=$DI->getOwnerOrganization();

$owner_email=$DI->getOwnerEmail();

$rawdata=$DI->getRawdata();
$md51=md5($rawdata);
class db
{
//属性
    private $host; //服务器名
    private $user; //用户名
    private $pwd; //密码
    private $name; //数据库名
    private $mysqli; //连接标识

//__construct：构造函数，建立连接,在函数建立时自动调用建立，原则新建对象时不显式调用
    function __construct()
    {
        $this->host="localhost"; //使用sys_conf类的静态属性
        $this->user="root";
        $this->pwd="quyun.com";
        $this->name="whois";
//建立与数据库的连接
        $this->mysqli = new mysqli($this->host,$this->user,$this->pwd,$this->name);
        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $this->mysqli->query("set names 'utf8'");//字符集的统一
    }
//__destruct：析构函数，断开连接,在函数执行完毕时自动调用析构。实现关闭数据库的连接，保证数据库数据的安全
    function __destruct()
    {

    }

//返回值为对象数组，数组中的每一元素为一行记录构成的对象
    function query($sql)
    {
        $query_result = $this->mysqli->query($sql); //查询数据
        if(is_bool($query_result))
            return $query_result;

        var_dump($query_result);
        $result_array=array(); //返回数组
        $i=0; //数组下标
        while($row = mysqli_fetch_object($query_result))
        {
            $result_array[$i++]=$row;
        }//while

        return $result_array;
    }
//插入数据，返回id
    function insert_id($sql)
    {
        $this->query($sql);
        return $this->mysqli->insert_id;
    }
//获得查询结果的纪录数函数
    function result_query($sql)
    {
        $result=$this->mysqli->query($sql);
        $result_c=mysqli_num_rows($result);
        return $result_c;
    }
}

$db=new db();
$sql="INSERT INTO `domain_info`\n"
    ."(`domain_name`, `owner_email`, `owner_name`, `owner_organization`, `registrar`, `whois_server`, `creation_date`, `expiration_date`, `updated_date`, `status`, `nameservers`, `md5`, `query_time`) \n"
    ."VALUES (\"".$domain_name."\",\"".$owner_email."\",\"".$owner_name."\",\"".$owner_organization."\",\"".$registrar."\",\"".$whois_server."\",".strtotime($creation_date).",".strtotime($expiration_date).",".strtotime($updated_date).",\"".$status."\",\"".$nameservers."\",\"".$md51."\",".time().")";
echo $sql;
echo "</br>";
echo "</br>";
echo $db->insert_id($sql);
?>