<?php
error_reporting(7);

/**
 *
 * @author Administrator
 *        
 */
class DB_MySQL
{

    var $querycount = 0;

    function error()
    {
        return mysql_error();
    }

    function DB_MySQL()
    {
        $this->connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PCONNECT);
    }

    function geterrno()
    {
        return mysql_errno();
    }

    function insert_id()
    {
        $id = mysql_insert_id();
        return $id;
    }

    function connect($servername, $dbusername, $dbpassword, $dbname, $usepconnect = false)
    {
        if($usepconnect)
            @mysql_pconnect($servername, $dbusername, $dbpassword) or die($this->error());
        else
            @mysql_connect($servername, $dbusername, $dbpassword) or die($this->error());
        
        if($this->version() > '4.1')
        {
            $charset = DATACHARSET;
            mysql_query("SET character_set_connection=$charset, character_set_results=$charset, character_set_client=binary");
        }
        
        @mysql_select_db($dbname) or die($this->error());
    }

    function select_db($dbname)
    {
        return @mysql_select_db($dbname);
    }

    function query($sql)
    {
        $query = @mysql_query($sql) or die($this->error());
        $this->querycount ++;
        return $query;
    }

    function fetch_array($sql, $type = 'assoc')
    {
        $result = $this->query($sql);
        if($type == 'assoc')
        {
            return @mysql_fetch_assoc($result);
        }
        else if($type == 'row')
        {
            return @mysql_fetch_row($result);
        }
        else
            return @mysql_fetch_array($result);
    }

    function fetch_row($sql)
    {
        $result = $this->query($sql);
        return @mysql_fetch_row($result);
    }

    function fetch_one_array($sql, $type = 'assoc')
    {
        $record = $this->fetch_array($sql, $type);
        return $record;
    }

    function fetch_all_array($sql, $type = 'assoc')
    {
        $records = array();
        $result = $this->query($sql);
        if($type == 'assoc')
        {
            while ($row = @mysql_fetch_assoc($result))
            {
                $records[] = $row;
            }
        }
        else if($type == 'row')
        {
            while ($row = @mysql_fetch_row($result))
            {
                $records[] = $row;
            }
        }
        else
            while ($row = @mysql_fetch_array($result))
            {
                $records[] = $row;
            }
        return $records;
    }

    function fetch_one($sql, $type = 'row')
    {
        $record = $this->fetch_one_array($sql, $type);
        Return $record[0];
    }

    function num_rows($query)
    {
        $query = @mysql_num_rows($query);
        return $query;
    }

    function free_result($query)
    {
        $query = @mysql_free_result($query);
        return $query;
    }

    function affected_rows()
    {
        return mysql_affected_rows();
    }

    function fetch_page_query($sql, $page = 1, $pagesize = 20)
    {
        if($page === null)
            return $this->query($sql);
        $countSQL = preg_replace('|select.*from|i', 'select count(*) as count from', $sql, 1);
        $count = (int) $this->fetch_one($countSQL);
        $data['count'] = $count;
        $data['pageSize'] = (int) $pagesize < 1 ? 20 : (int) $pagesize;
        $data['pageCount'] = ceil($count / $data['pageSize']);
        $data['page'] = $data['pageCount'] == 0 ? 0 : ((int) $page < 1 ? 1 : (int) $page);
        $data['page'] = $data['page'] > $data['pageCount'] ? $data['pageCount'] : $data['page'];
        $start = ($data['page'] == 0) ? 0 : ($data['page'] - 1) * $data['pageSize'];
        $selectPageSQL = $sql . ' limit ' . $start . ',' . $data['pageSize'];
        
        $data['data'] = $this->fetch_all_array($selectPageSQL);
        return $data;
    }

    function close()
    {
        return @mysql_close();
    }

    function version()
    {
        return @mysql_get_server_info();
    }

    function halt($msg, $sql = "")
    {
        $message = "<html>\n<head>\n";
        $message .= "<meta content=\"text/html; charset=gb2312\" http-equiv=\"Content-Type\">\n";
        $message .= "<STYLE TYPE=\"text/css\">\n";
        $message .= "body,td,p,pre {\n";
        $message .= "font-family : Verdana, sans-serif;font-size : 11px;\n";
        $message .= "}\n";
        $message .= "</STYLE>\n";
        $message .= "</head>\n";
        $message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";
        
        $message .= "ݿ: " . htmlspecialchars($msg) . "\n<p>";
        $message .= "<b>Mysql error description</b>: " . $this->error() . "\n<br>";
        $message .= "<b>Mysql error number</b>: " . $this->geterrno() . "\n<br>";
        $message .= "<b>Date</b>: " . date("Y-m-d @ H:i", time()) . "\n<br>";
        $message .= "<b>Query</b>: " . $sql . "\n<br>";
        $message .= "<b>Script</b>: http://" . $_SERVER['HTTP_HOST'] . getenv("REQUEST_URI") . "\n<br>";
        
        $message .= "</body>\n</html>";
        die($message);
        exit();
    }
}
