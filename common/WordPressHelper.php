<?php

/**
 * 自己开发的WordPress辅助工具
 * 学习要点
 *
 * $_GET['action']
 * mysql_affected_rows
 * 获取系统时间
 */
@header('Content-Type: text/html; charset=UTF-8');

// 载入wp-config.php
require 'wp-config.php';

// 定义执行的动作
$action = '';
$action = $_GET['action'];

/**
 * 分支语句执行
 */
switch ($action)
{
    case 'update_post_status':
        // 获取当前时间
        date_default_timezone_set('PRC');
        $curTime = date('Y-m-d H:i:s', time());
        
        echo '当前系统时间' . $curTime;
        // $curTime='2016-07-10 00:00:00';
        echo '<hr />';
        
        // 创建帮助助手
        $helper = new SqlHelper();
        
        // 排除掉自动草稿
        $cmd = "select ID from `wp_posts` where post_title!='自动草稿 ' and post_date<='" . $curTime . "'";
        $update_post_status_sql = 'update `wp_posts` set post_status=\'publish\' where ID in (select a.ID from (' . $cmd . ') as a)';
        
        echo '当前查询语句' . $update_post_status_sql;
        echo '<hr />';
        
        $result = $helper->execute_dql($update_post_status_sql);
        echo '更新结果为：' . $result;
        $helper->close_connect();
        
        break;
    
    case 'get_server_time':
        // 获取当前时间
        date_default_timezone_set('PRC');
        $curTime = date('Y-m-d H:i:s', time());
        echo '当前系统时间' . $curTime;
        // $curTime='2016-07-10 00:00:00';
        echo '<hr />';
        
        break;
    
    case 'list_post_id_name':
        
        $cmd = "select ID,post_title from `wp_posts`";
        
        $helper = new SqlHelper();
        
        // 获得查询结果
        $result = $helper->execute_dql($cmd);
        
        // 遍历出所有的ID和title
        while ((list ($id, $name) = mysql_fetch_row($result)) != false)
        {
            echo 'ID:' . $id . ' name:' . $name . '<br />';
        }
        
        break;
    default:
        echo 'OK';
        break;
}

?>

<?php

class SqlHelper
{

    public $conn;

    public $dbname = DB_NAME;

    public $username = DB_USER;

    public $password = DB_PASSWORD;

    public $host = DB_HOST;

    public $charset = DB_CHARSET;

    public function __construct()
    {
        // 1.创建连接
        $this->conn = mysql_connect($this->host, $this->username, $this->password);
        
        if(! $this->conn)
        {
            die("连接失败" . mysql_error());
        }
        
        // 2.设置字符集
        $status = mysql_set_charset($this->charset, $this->conn);
        
        echo '设置字符集：' . $status;
        echo '<hr />';
        
        // 3.选择活动的数据库
        $status = mysql_select_db($this->dbname, $this->conn);
        
        echo '数据库连接：' . $status;
        echo '<hr />';
    }

    // 执行dql语句
    public function execute_dql($sql)
    {
        $res = mysql_query($sql, $this->conn) or die(mysql_error());
        
        echo '受影响的行数：' . mysql_affected_rows();
        echo '<hr />';
        
        return $res;
    }

    // 执行dql语句，但是返回的是一个数组
    public function execute_dql2($sql)
    {
        $arr = array();
        $res = mysql_query($sql, $this->conn) or die(mysql_error());
        
        // 把$res=>$arr 把结果集内容转移到一个数组中.
        while ($row = mysql_fetch_assoc($res))
        {
            $arr[] = $row;
        }
        // 这里就可以马上把$res关闭.
        mysql_free_result($res);
        return $arr;
    }

    // 考虑分页情况的查询,这是一个比较通用的并体现oop编程思想的代码
    // $sql1="select * from where 表名 limit 0,6";
    // $sql2="select count(id) from 表名"
    public function exectue_dql_fenye($sql1, $sql2, $fenyePage)
    {
        
        // 这里我们查询了要分页显示的数据
        $res = mysql_query($sql1, $this->conn) or die(mysql_error());
        // $res=>array()
        $arr = array();
        // 把$res转移到$arr
        while (($row = mysql_fetch_assoc($res)) != false)
        {
            $arr[] = $row;
        }
        
        mysql_free_result($res);
        
        $res2 = mysql_query($sql2, $this->conn) or die(mysql_error());
        
        if($row = mysql_fetch_row($res2))
        {
            $fenyePage->pageCount = ceil($row[0] / $fenyePage->pageSize);
            $fenyePage->rowCount = $row[0];
        }
        
        mysql_free_result($res2);
        
        // 把导航信息也封装到fenyePage对象中
        $navigate = "";
        if($fenyePage->pageNow > 1)
        {
            $prePage = $fenyePage->pageNow - 1;
            $navigate = "<a href='{$fenyePage->gotoUrl}?pageNow=$prePage'>上一页</a>&nbsp;";
        }
        if($fenyePage->pageNow < $fenyePage->pageCount)
        {
            $nextPage = $fenyePage->pageNow + 1;
            $navigate .= "<a href='{$fenyePage->gotoUrl}?pageNow=$nextPage'>下一页</a>&nbsp;";
        }
        
        $page_whole = 10;
        $start = floor(($fenyePage->pageNow - 1) / $page_whole) * $page_whole + 1;
        $index = $start;
        // 整体每10页向前翻
        // 如果当前pageNow在1-10页数，就没有向前翻动的超连接
        if($fenyePage->pageNow > $page_whole)
        {
            $navigate .= "&nbsp;&nbsp;<a href='{$fenyePage->gotoUrl}?pageNow=" . ($start - 1) . "'>&nbsp;&nbsp;<<&nbsp;&nbsp;</a>";
        }
        // 定$start 1---》10 floor((pageNow-1)/10)=0*10+1 11->20
        // floor((pageNow-1)/10)=1*10+1 21-30 floor((pageNow-1)/10)=2*10+1
        for(; $start < $index + $page_whole; $start ++)
        {
            $navigate .= "<a href='{$fenyePage->gotoUrl}?pageNow=$start'>[$start]</a>";
        }
        
        // 整体每10页翻动
        $navigate .= "&nbsp;&nbsp;<a href='{$fenyePage->gotoUrl}?pageNow=$start'>&nbsp;&nbsp;>>&nbsp;&nbsp;</a>";
        // 显示当前页和共有多少页
        $navigate .= " 当前页{$fenyePage->pageNow}/共{$fenyePage->pageCount}页";
        
        // 把$arr赋给$fenyePage
        $fenyePage->res_array = $arr;
        $fenyePage->navigate = $navigate;
    }

    // 执行dml语句
    public function execute_dml($sql)
    {
        // 返回-1：表示失败；其他的返回受影响的行数
        $b = mysql_query($sql, $this->conn) or die(mysql_error());
        
        if(! $b)
        {
            return 0; // 失败
        }
        else
        {
            if(mysql_affected_rows($this->conn) > 0)
            {
                return 1; // 表示执行ok
            }
            else
            {
                return 2; // 表示没有行受到影响
            }
        }
    }

    /**
     * 关闭连接的方法
     */
    public function close_connect()
    {
        if(! empty($this->conn))
        {
            mysql_close($this->conn);
        }
    }
}
?>