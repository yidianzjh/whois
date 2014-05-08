<?php
/* 
数据库类文件：class_database.php 
数据库操作类，本类是其他类操作的基础，即其他类函数的实现一般情况下通过数据库类实现 

*/

class data_class
{
//属性 
    private $host; //服务器名
    private $user; //用户名
    private $pwd; //密码
    private $name; //数据库名
    private $connection; //连接标识

//方法 
//__get()：获取属性值 
    function __get($property_name){
        if(isset($this->$property_name))
        {
            return($this->$property_name);
        }
        else
        {
            return(NULL);
        }
    }
//__set()：设置单个私有数据属性值，用于少量的修改数据 
    function __set($property_name, $value)
    {
        $this->$property_name = $value;
    }
//__construct：构造函数，建立连接,在函数建立时自动调用建立，原则新建对象时不显式调用 
    function __construct()
    {
        $this->host="localhost"; //使用sys_conf类的静态属性
        $this->user="root";
        $this->pwd="quyun.com";
        $this->name="whois";
//建立与数据库的连接 
        $this->connection=mysql_connect ($this->host,$this->user,$this->pwd);//建立连接
        mysql_query("set names 'utf8'",$this->connection);//字符集的统一
        mysql_select_db("$this->name", $this->connection); //选择数据库
    }
//__destruct：析构函数，断开连接,在函数执行完毕时自动调用析构。实现关闭数据库的连接，保证数据库数据的安全 
    function __destruct()
    {
        mysqli_close($this->connection);
    }
//增删改：参数$sql为Insert update 
    function execute($sql)
    {
        mysql_query($sql);
//echo "写入数据库成功了"; 
//echo "我是dataclass类的execute函数"; 
    }//execute
//查：参数$sql为Insert语句 
//返回值为对象数组，数组中的每一元素为一行记录构成的对象 
    function query($sql)
    {
        $result_array=array(); //返回数组
        $i=0; //数组下标
        $query_result=@mysqli_query($sql,$this->connection); //查询数据
        while($row=@mysqli_fetch_object($query_result))
        {
            $result_array[$i++]=$row;
        }//while
        return $result_array;
    }
//插入数据，返回id
    function insert_id($sql)
    {
        $result=@mysqli_query($sql,$this->connection);
        return mysqli_insert_id($this->connection);
    }
//获得查询结果的纪录数函数 
    function result_query($sql)
    {
        $result=mysql_query($sql);
        $result_c=mysql_num_rows($result);
        return $result_c;
    }
}
?>