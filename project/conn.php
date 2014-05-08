<?php

/*
$host="localhost"; //使用sys_conf类的静态属性
$user="root";
$pwd="quyun.com";
$name="whois";
//建立与数据库的连接
$mysqli = new mysqli($host,$user,$pwd, $name);
/* check connection */
/*
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$mysqli->query("set names 'utf8'");//字符集的统一
*/

class db
{
//属性
    private $host; //服务器名
    private $user; //用户名
    private $pwd; //密码
    private $name; //数据库名
    private $mysqli; //连接标识

//__construct：构造函数，建立连接,在函数建立时自动调用建立，原则新建对象时不显式调用
    public function __construct()
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
    public function __destruct()
    {

    }

//返回值为对象数组，数组中的每一元素为一行记录构成的对象
    public function query($sql)
    {
        $query_result = $this->mysqli->query($sql); //查询数据
        if(is_bool($query_result))
            return $query_result;

        //var_dump($query_result);
        $result_array=array(); //返回数组
        $i=0; //数组下标
        while($row = mysqli_fetch_object($query_result))
        {
            $result_array[$i++]=$row;
        }//while

        return $result_array;
    }
//插入数据，返回id
    public function insert_id($sql)
    {
        $this->query($sql);
        return $this->mysqli->insert_id;
    }
//获得查询结果的纪录数函数
    public function result_query($sql)
    {
        $result=$this->mysqli->query($sql);
        $result_c=mysqli_num_rows($result);
        return $result_c;
    }
}

?>
