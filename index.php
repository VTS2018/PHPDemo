<?php

function function_name1()
{
    // 单入口程序 载入类文件 使用绝对路径 所以定义 __ROOT__ 常量
    define("__ROOT__", dirname(__FILE__));
    echo __ROOT__;
}

function function_name6()
{
    $html = file_get_contents('http://www.runoob.com');
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    
    // grab all the on the page
    $xpath = new DOMXPath($dom);
    $hrefs = $xpath->evaluate("/html/body//a");
    
    for($i = 0; $i < $hrefs->length; $i ++)
    {
        $href = $hrefs->item($i);
        $url = $href->getAttribute('href');
        echo $url . '<br />';
    }
}

// //////////////////////////////////////////////////////阅读至此//////////////////////////////////////////////////////
function get_os()
{
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;
    if(preg_match('/win/i', $agent) && strpos($agent, '95'))
    {
        $os = 'Windows 95';
    }
    else if(preg_match('/win 9x/i', $agent) && strpos($agent, '4.90'))
    {
        $os = 'Windows ME';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/98/i', $agent))
    {
        $os = 'Windows 98';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))
    {
        $os = 'Windows Vista';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))
    {
        $os = 'Windows 7';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))
    {
        $os = 'Windows 8';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))
    {
        $os = 'Windows 10';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))
    {
        $os = 'Windows XP';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))
    {
        $os = 'Windows 2000';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))
    {
        $os = 'Windows NT';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/32/i', $agent))
    {
        $os = 'Windows 32';
    }
    else if(preg_match('/linux/i', $agent))
    {
        $os = 'Linux';
    }
    else if(preg_match('/unix/i', $agent))
    {
        $os = 'Unix';
    }
    else if(preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))
    {
        $os = 'SunOS';
    }
    else if(preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))
    {
        $os = 'IBM OS/2';
    }
    else if(preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))
    {
        $os = 'Macintosh';
    }
    else if(preg_match('/PowerPC/i', $agent))
    {
        $os = 'PowerPC';
    }
    else if(preg_match('/AIX/i', $agent))
    {
        $os = 'AIX';
    }
    else if(preg_match('/HPUX/i', $agent))
    {
        $os = 'HPUX';
    }
    else if(preg_match('/NetBSD/i', $agent))
    {
        $os = 'NetBSD';
    }
    else if(preg_match('/BSD/i', $agent))
    {
        $os = 'BSD';
    }
    else if(preg_match('/OSF1/i', $agent))
    {
        $os = 'OSF1';
    }
    else if(preg_match('/IRIX/i', $agent))
    {
        $os = 'IRIX';
    }
    else if(preg_match('/FreeBSD/i', $agent))
    {
        $os = 'FreeBSD';
    }
    else if(preg_match('/teleport/i', $agent))
    {
        $os = 'teleport';
    }
    else if(preg_match('/flashget/i', $agent))
    {
        $os = 'flashget';
    }
    else if(preg_match('/webzip/i', $agent))
    {
        $os = 'webzip';
    }
    else if(preg_match('/offline/i', $agent))
    {
        $os = 'offline';
    }
    else if(preg_match('/iPhone OS 8/i', $agent))
    {
        $os = 'iOS 8';
    }
    else if(preg_match('/YisouSpider/i', $agent))
    {
        $os = '一搜引擎';
    }
    else if(preg_match('/Yahoo! Slurp/i', $agent))
    {
        $os = '雅虎引擎';
    }
    else if(preg_match('/iPhone OS 6/i', $agent))
    {
        $os = 'iOS 6';
    }
    else if(preg_match('/Baiduspider/i', $agent))
    {
        $os = '百度引擎';
    }
    else if(preg_match('/iPhone OS 10/i', $agent))
    {
        $os = 'iOS 10';
    }
    else if(preg_match('/Mac OS X 10/i', $agent))
    {
        $os = 'Mac OS 10';
    }
    else if(preg_match('/Ahrefs/i', $agent))
    {
        $os = 'Ahrefs SEO 引擎';
    }
    else if(preg_match('/JikeSpider/i', $agent))
    {
        $os = '即刻引擎';
    }
    else if(preg_match('/Googlebot/i', $agent))
    {
        $os = '谷歌引擎';
    }
    else if(preg_match('/bingbot/i', $agent))
    {
        $os = '必应引擎';
    }
    else if(preg_match('/iPhone OS 7/i', $agent))
    {
        $os = 'iOS 7';
    }
    else if(preg_match('/Sogou web spider/i', $agent))
    {
        $os = '搜狗引擎';
    }
    else if(preg_match('/IP-Guide.com Crawler/i', $agent))
    {
        $os = 'IP-Guide Crawler 引擎';
    }
    else if(preg_match('/VenusCrawler/i', $agent))
    {
        $os = 'VenusCrawler 引擎';
    }
    else if(preg_match('/iPad/i', $agent))
    {
        $os = 'iPad';
    }
    else
    {
        $os = $agent;
    }
    return $os;
}

function get_broswer_v1()
{
    // $sys = $_SERVER['HTTP_USER_AGENT']; //获取用户代理字符串
    $sys = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36';
    
    if(stripos($sys, "Firefox/") > 0)
    {
        preg_match("/Firefox/([^;)]+)+/i", $sys, $b);
        $exp[0] = "Firefox";
        $exp[1] = $b[1]; // 获取火狐浏览器的版本号
    }
    elseif(stripos($sys, "Maxthon") > 0)
    {
        preg_match("/Maxthon/([d.]+)/", $sys, $aoyou);
        $exp[0] = "傲游";
        $exp[1] = $aoyou[1];
    }
    elseif(stripos($sys, "Baiduspider") > 0)
    {
        $exp[0] = "百度";
        $exp[1] = '蜘蛛';
    }
    elseif(stripos($sys, "YisouSpider") > 0)
    {
        $exp[0] = "一搜";
        $exp[1] = '蜘蛛';
    }
    elseif(stripos($sys, "Googlebot") > 0)
    {
        $exp[0] = "谷歌";
        $exp[1] = '蜘蛛';
    }
    elseif(stripos($sys, "Android 4.3") > 0)
    {
        $exp[0] = "安卓";
        $exp[1] = '4.3';
    }
    elseif(stripos($sys, "MSIE") > 0)
    {
        preg_match("/MSIEs+([^;)]+)+/i", $sys, $ie);
        $exp[0] = "IE";
        $exp[1] = $ie[1]; // 获取IE的版本号
    }
    elseif(stripos($sys, "OPR") > 0)
    {
        preg_match("/OPR/([d.]+)/", $sys, $opera);
        $exp[0] = "Opera";
        $exp[1] = $opera[1];
    }
    elseif(stripos($sys, "Edge") > 0)
    {
        // win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge/([d.]+)/", $sys, $Edge);
        $exp[0] = "Edge";
        $exp[1] = $Edge[1];
    }
    elseif(stripos($sys, "Chrome") > 0)
    {
        preg_match('/Chrome\/([^\s]+)/i', $sys, $google);
        $exp[0] = "Chrome";
        $exp[1] = $google[1]; // 获取google chrome的版本号
    }
    elseif(stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0)
    {
        preg_match("/rv:([d.]+)/", $sys, $IE);
        $exp[0] = "IE";
        $exp[1] = $IE[1];
    }
    else if(stripos($sys, 'AhrefsBot') > 0)
    {
        $exp[0] = "AhrefsBot";
        $exp[1] = '蜘蛛';
    }
    else if(stripos($sys, 'Safari') > 0)
    {
        preg_match("/([d.]+)/", $sys, $safari);
        $exp[0] = "Safari";
        $exp[1] = $safari[1];
    }
    else if(stripos($sys, 'bingbot') > 0)
    {
        $exp[0] = "必应";
        $exp[1] = '蜘蛛';
    }
    else if(stripos($sys, 'WinHttp') > 0)
    {
        $exp[0] = "windows";
        $exp[1] = 'WinHttp 请求接口工具';
    }
    else if(stripos($sys, 'iPhone OS 10') > 0)
    {
        $exp[0] = "iPhone";
        $exp[1] = 'OS 10';
    }
    else if(stripos($sys, 'Sogou') > 0)
    {
        $exp[0] = "搜狗";
        $exp[1] = '蜘蛛';
    }
    else if(stripos($sys, 'HUAWEIM') > 0)
    {
        $exp[0] = "华为";
        $exp[1] = '手机端';
    }
    else if(stripos($sys, 'Dalvik') > 0)
    {
        $exp[0] = "安卓";
        $exp[1] = 'Dalvik虚拟机';
    }
    else if(stripos($sys, 'Mac OS X 10') > 0)
    {
        $exp[0] = "MAC";
        $exp[1] = 'OS X10';
    }
    else if(stripos($sys, 'Opera/9.8') > 0)
    {
        $exp[0] = "Opera";
        $exp[1] = '9.8';
    }
    else if(stripos($sys, 'JikeSpider') > 0)
    {
        $exp[0] = "即刻";
        $exp[1] = '蜘蛛';
    }
    else if(stripos($sys, 'Baiduspider') > 0)
    {
        $exp[0] = "百度";
        $exp[1] = '蜘蛛';
    }
    else
    {
        $exp[0] = $sys;
        $exp[1] = "";
    }
    return $exp[0] . ' ' . $exp[1];
}

function get_broswer_v2()
{
    global $_SERVER;
    $agent = $_SERVER['HTTP_USER_AGENT'];
    
    $browser = '';
    $browser_ver = '';
    
    if(preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs))
    {
        $browser = 'OmniWeb';
        $browser_ver = $regs[2];
    }
    
    if(preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs))
    {
        $browser = 'Netscape';
        $browser_ver = $regs[2];
    }
    
    if(preg_match('/safari\/([^\s]+)/i', $agent, $regs))
    {
        $browser = 'Safari';
        $browser_ver = $regs[1];
    }
    
    if(preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs))
    {
        $browser = 'Internet Explorer';
        $browser_ver = $regs[1];
    }
    
    if(preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs))
    {
        $browser = 'Opera';
        $browser_ver = $regs[1];
    }
    
    if(preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs))
    {
        $browser = '(Internet Explorer ' . $browser_ver . ') NetCaptor';
        $browser_ver = $regs[1];
    }
    
    if(preg_match('/Maxthon/i', $agent, $regs))
    {
        $browser = '(Internet Explorer ' . $browser_ver . ') Maxthon';
        $browser_ver = '';
    }
    if(preg_match('/360SE/i', $agent, $regs))
    {
        $browser = '(Internet Explorer ' . $browser_ver . ') 360SE';
        $browser_ver = '';
    }
    if(preg_match('/SE 2.x/i', $agent, $regs))
    {
        $browser = '(Internet Explorer ' . $browser_ver . ') 搜狗';
        $browser_ver = '';
    }
    
    if(preg_match('/FireFox\/([^\s]+)/i', $agent, $regs))
    {
        $browser = 'FireFox';
        $browser_ver = $regs[1];
    }
    
    if(preg_match('/Lynx\/([^\s]+)/i', $agent, $regs))
    {
        $browser = 'Lynx';
        $browser_ver = $regs[1];
    }
    
    if(preg_match('/Chrome\/([^\s]+)/i', $agent, $regs))
    {
        $browser = 'Chrome';
        $browser_ver = $regs[1];
    }
    
    if($browser != '')
    {
        return [
            'browser' => $browser,
            'version' => $browser_ver
        ];
    }
    else
    {
        return [
            'browser' => 'unknow browser',
            'version' => 'unknow browser version'
        ];
    }
}

function function_name_list()
{
    /**
     * list 把数组中的值赋给一些变量
     */
    // 定义一个函数
    $info = array(
        'coffee',
        'brown',
        'caffeine'
    );
    
    // 列出所有变量 ：将数组中的三个值赋值给三个变量
    list ($drink, $color, $power) = $info;
    
    echo "$drink is $color and $power makes it special.\n"; // coffee is brown and caffeine makes it special.
    echo '<hr />';
    
    // 列出他们的其中一个
    list ($drink, , $power) = $info;
    echo "$drink has $power.\n"; // coffee has caffeine
    echo '<hr />';
    
    // 或者让我们跳到仅第三个
    list (, , $power) = $info;
    echo "I need $power!\n"; // I need caffeine!
    echo '<hr />';
    
    // list() 不能对字符串起作用
    list ($bar) = "abcde";
    var_dump($bar); // NULL
    
    /*
     * //在mySQL中
     * $result = mysql_query("SELECT id, name, salary FROM employees", $conn);
     * while (list($id, $name, $salary) = mysql_fetch_row($result)) {
     * echo " <tr>\n" .
     * " <td><a href=\"info.php?id=$id\">$name</a></td>\n" .
     * " <td>$salary</td>\n" .
     * " </tr>\n";
     * }
     */
    echo '<hr />';
}

function function_name8()
{
    /**
     * ceil() 函数向上舍入为最接近的整数
     * 返回不小于 x 的下一个整数，x 如果有小数部分则进一位。
     * ceil() 返回的类型仍然是 float，因为 float 值的范围通常比 integer 要大
     * 想象一下 X轴
     * 在WP中用来计算分页数量
     */
    echo ceil(0.60); // 1
    echo '<br />';
    echo ceil(0.40); // 1
    echo '<br />';
    echo ceil(5); // 5
    echo '<br />';
    echo ceil(5.1); // 6
    echo '<br />';
    
    echo ceil(- 5.1); // -5
    echo '<br />';
    echo ceil(- 5.9); // -5
    echo '<br />';
    echo '<hr />';
    
    /**
     * abs() 函数返回一个数的绝对值
     * 返回参数 x 的绝对值。如果参数 x 是 float，则返回的类型也是 float，否则返回 integer（因为 float 通常比 integer 有更大的取值范围）
     */
    echo (abs(6.7)); // 6.7
    echo '<br />';
    echo (abs(- 3)); // 3
    echo '<br />';
    echo (abs(3)); // 3
    echo '<br />';
    echo '<hr />';
    
    /**
     * decbin:把十进制转换为二进制
     * 返回一个字符串，包含有给定 dec_number 参数的二进制表示。所能转换的最大数值为十进制的 4294967295，其结果为 32 个 1 的字符串
     *
     * bindec:把二进制转换为十进制
     * 将一个二进制数转换成 integer。可转换的最大的数为 31 位 1 或者说十进制的 2147483647。
     * PHP 4.1.0 开始，该函数可以处理大数值，这种情况下，它会返回 float 类型
     *
     *
     * dechex() 函数把十进制转换为十六进制
     * 返回一个字符串，包含有给定 binary_string 参数的十六进制表示。所能转换的最大数值为十进制的 4294967295，其结果为 "ffffffff"
     *
     * hexdec() 函数把十六进制转换为十进制
     *
     * 返回与 hex_string 参数所表示的十六进制数等值的的十进制数。
     * hexdec() 将一个十六进制字符串转换为十进制数。所能转换的最大数值为 7fffffff，即十进制的 2147483647。
     * PHP 4.1.0 开始，该函数可以处理大数字，这种情况下，它会返回 float 类型。
     * hexdec() 将遇到的所有非十六进制字符替换成 0。这样，所有左边的零都被忽略，但右边的零会计入值中
     *
     * decoct() 函数把十进制转换为八进制
     * octdec() 函数把八进制转换为十进制
     */
    
    echo decbin("3");
    echo '<br />'; // 11
    echo decbin("1");
    echo '<br />'; // 1
    echo decbin("1587");
    echo '<br />'; // 11000110011
    echo decbin("7");
    echo '<br />'; // 111
    echo '<hr />';
    
    /**
     * rand
     * rand(min,max)
     *
     * 如果没有提供可选参数 min 和 max，rand() 返回 0 到 RAND_MAX 之间的伪随机整数。
     * 例如，想要 5 到 15（包括 5 和 15）之间的随机数，用 rand(5, 15)。
     */
    
    echo rand();
    echo '<br />';
    echo rand();
    echo '<br />';
    echo rand(10, 100);
    echo '<br />';
    echo '<hr />';
    
    /**
     * round
     * round(x,prec)
     * 返回将 x 根据指定精度 prec （十进制小数点后数字的数目）进行四舍五入的结果。prec 也可以是负数或零（默认值）
     * 注释：PHP 默认不能正确处理类似 "12,300.2" 的字符串
     */
    
    echo (round(0.60));
    echo '<br />'; // 1
    echo (round(0.50));
    echo '<br />'; // 1
    echo (round(0.49));
    echo '<br />'; // 0
    echo (round(- 4.40));
    echo '<br />'; // -4
    echo (round(- 4.60));
    echo '<br />'; // -5
    echo '<hr />';
}

function function_name9()
{
    
    /**
     * 手册 http://www.w3school.com.cn/php/php_ref_string.asp
     * 学习要点
     * 字符的各种处理方法
     *
     * 长度
     * 连接 分割 查找 替换
     * 大小写
     * 格式化
     * 去空格
     *
     * 函数列表如下
     *
     * strlen
     * trim ltrim rtrim
     * explode implode
     * bin2hex pack hex2bin
     *
     * strtolower strtoupper
     * lcfirst ucfirst
     * ucwords
     *
     * strip_tags
     * substr
     *
     * stristr
     * strrchr
     * strstr
     *
     * 位置查找函数
     *
     * strpos
     * strrpos
     *
     * stripos
     * strripos
     *
     * sprintf
     */
    
    /**
     * strlen() 函数返回字符串的长度【其实并不实用】
     */
    $str = 'Hello World!你好';
    echo strlen($str); // 12+6 中文字符占三个长度 ；此时是18
    echo '<hr />';
    
    echo strlen("Hello"), "<br>"; // 5
    echo strlen("中午"), "<br>"; // 6 注意被代码页面是Unix 下UTF-8的编码
    echo mb_strlen($str, "utf-8"), "<br>"; // 14
    echo mb_strlen("中文", "utf-8"), "<br>"; // 2
    echo mb_internal_encoding(), "<br>"; // 得到内部的编码 UTF-8
    echo '<hr />';
    
    /**
     * trim() 函数移除字符串两侧的空白字符或其他预定义字符
     *
     * 语法 ：要处理的字符串 移除的字符列表
     * trim(string,charlist)
     *
     * 默认移除
     *
     * "\0" - NULL
     * "\t" - 制表符
     * "\n" - 换行
     * "\x0B" - 垂直制表符
     * "\r" - 回车
     * " " - 空格
     */
    // 移除字符串两侧的字符（"Hello" 中的 "He" 以及 "World" 中的 "d!"）：
    $str = "Hello World!";
    echo $str . "<br />"; // Hello World!
    echo trim($str, "Hed!"), "<br />"; // llo Worl
    echo trim("中文", "文"), "<br />"; // 中
    echo '<hr />';
    
    $str = "/\\2015/001/\\\\/";
    // 含义是删除掉 “/” 和 “\”两个字符
    echo trim($str, '\\/'); // 2 015/001 去掉开头和结尾的
                            // ////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * explode() 函数把字符串打散为数组
     * 注释："separator" 参数不能是空字符串
     * 注释：该函数是二进制安全的
     * 语法
     * explode(separator,string,limit)
     */
    // 以空格为分隔符分割字符串
    $str = "Hello world. I love Shanghai!";
    print_r(explode(" ", $str)); // Array ( [0] => Hello [1] => world. [2] => I [3] => love [4] => Shanghai! )
    echo '<hr />';
    
    $str = 'one,two,three,four';
    // 零 limit
    print_r(explode(',', $str, 0)); // Array ( [0] => one,two,three,four )
    echo '<hr />';
    // 正的 limit
    print_r(explode(',', $str, 2)); // Array ( [0] => one [1] => two,three,four )
    echo '<hr />';
    // 负的 limit
    print_r(explode(',', $str, - 1)); // Array ( [0] => one [1] => two [2] => three )
    echo '<hr />';
    
    /**
     * implode() 函数返回由数组元素组合成的字符串
     * 注释：implode() 函数接受两种参数顺序。但是由于历史原因，explode() 是不行的，您必须保证 separator 参数在 string 参数之前才行。
     * 注释：implode() 函数的 separator 参数是可选的。但是为了向后兼容，推荐您使用使用两个参数。
     * 注释：该函数是二进制安全的。
     * implode(separator,array)
     */
    $arr = array(
        'Hello',
        'World!',
        'I',
        'love',
        'Shanghai!'
    );
    echo implode(",", $arr); // Hello,World!,I,love,Shanghai!
    echo '<hr />';
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * bin2hex() 函数把 ASCII 字符的字符串转换为十六进制值。
     * 支持中文
     * 字符串可通过使用 pack() 函数再转换回去。
     *
     * hex2bin
     * 支持中文
     * 返回被转换字符串的 ASCII 字符，如果失败则返回 FALSE
     */
    
    $str = bin2hex("Shanghai中文");
    
    echo ($str); // 5368616e67686169
    echo '<hr />';
    
    echo pack("H*", $str); // Shanghai中文
    echo '<hr />';
    
    echo hex2bin($str); // Shanghai中文
    echo '<hr />';
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 大小写转换
     */
    echo strtolower("Hello WORLD."); // hello world.
    echo '<br />';
    echo strtoupper("Hello WORLD!"); // HELLO WORLD!
    echo '<br />';
    
    // 使第一个字符消息
    echo lcfirst("Hello world!"); // hello world!
    echo '<br />';
    // 使第一个字符大写
    echo ucfirst("hello world!"); // Hello world!
    echo '<br />';
    // 使每个单词的首字母大写
    echo ucwords("hello world"); // Hello World
    echo '<br />';
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 去除掉html标签和PHP标记
     */
    echo strip_tags("Hello <b>world!</b>"); // Hello world!
    echo '<br />';
    
    echo strip_tags("Hello <b><i>world!</i></b>", "<b>"); // 保留<b标签>
    echo '<br />';
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * substr() 函数返回字符串的一部分
     * 思考：负数允许从后面截取
     * 语法
     * substr(string,start,length)
     * 注释：如果 start 参数是负数且 length 小于或等于 start，则 length 为 0。
     * http://www.w3school.com.cn/php/func_string_substr.asp
     * start参数：正数 负数 0
     *
     * 返回字符串的提取部分，若失败则返回 FALSE，或者返回一个空字符串。
     */
    echo '<hr />';
    echo substr("Hello world", 6); // world 从0开始索引，返回从6开始到后面的字符
    echo '<hr />';
    
    echo substr("Hello world", 10) . "<br>"; // d
    echo substr("Hello world", 1) . "<br>"; // ello world
    
    echo substr("Hello world", 3) . "<br>"; // lo world
    echo substr("Hello world", 7) . "<br>"; // orld
    
    echo substr("Hello world", - 1) . "<br>"; // d
    echo substr("Hello world", - 10) . "<br>"; // ello world
    echo substr("Hello world", - 8) . "<br>"; // lo world
    echo substr("Hello world", - 4) . "<br>"; // orld
    
    echo '<hr />';
    echo substr("Hello world", 0, 10) . "<br>";
    echo substr("Hello world", 1, 8) . "<br>";
    echo substr("Hello world", 0, 5) . "<br>";
    echo substr("Hello world", 6, 6) . "<br>";
    
    echo substr("Hello world", 0, - 1) . "<br>";
    echo substr("Hello world", - 10, - 2) . "<br>";
    echo substr("Hello world", 0, - 6) . "<br>";
    echo substr("Hello world", - 2 - 3) . "<br>";
    echo '<hr />';
    
    // 中文的截取乱码
    echo substr("我是中国人", 0, 1); // 出现乱码
    echo '<hr />';
    echo substr("我是中国人", 0, 6); // 我是 为什么是3？中文UTP-8编码 对于substr 3个字节表示一个汉字
    echo '<hr />';
    // 输入字符串 起始位置 截取个数
    echo mb_substr("我是中国人", 0, 2, "utf-8"); // 我是 对于mb_substr 一个汉字 占一个字节
    echo '<br />';
    
    // 计算MD5值
    echo md5("Shanghai"), "<br>";
    echo '<hr />';
    // 替换 把 "Hello" 替换成 "world"：
    echo substr_replace("Hello", "world", 0), "<br>";
    // 中文乱码
    echo substr_replace("我是中国人一个", "中国", 1);
    
    echo '<br />';
    echo str_shuffle("我是中国人一个"); // 中文乱码
    
    echo '<br />';
    echo urlencode("我是一个中国人");
    echo '<br />';
    echo '<br />';
    echo '<br />';
    echo '<br />';
}

function chinesesubstr($str, $start, $len)
{
    header("Content-Type: text/html; charset=utf-8");
    $str = "107sadf网站工作室欢迎您！";
    $tmpstr = '';
    $strlen = $len - $start; // 定义需要截取字符的长度
    for($i = 0; $i < $strlen; $i ++)
    {
        // 使用循环语句，单字截取，并用$tmpstr.=$substr(？，？，？)加起来
        if(ord(substr($str, $i, 1)) > 0xa0)
        {
            // ord()函数取得substr()的第一个字符的ASCII码，如果大于0xa0的话则是中文字符
            $tmpstr .= substr($str, $i, 3); // 设置tmpstr递加，substr($str,$i,3)的3是指三个字符当一个字符截取(因为utf8编码的三个字符算一个汉字)
            $i += 2;
        }
        else
        {
            // 其他情况（英文）按单字符截取
            $tmpstr .= substr($str, $i, 1);
        }
    }
    return $tmpstr;
}

function function_name10()
{
    /**
     * 学习要点
     * stristr
     */
    
    /**
     * stristr 搜索某个字符串并返回剩余的字符串【夹带自身】
     * 未找到字符串返回false
     */
    
    // 查找 "world" 在 "Hello world!" 中的第一次出现，并返回字符串的剩余部分：
    echo stristr("Hello world!", "WORLD", false); // world!
    echo '<br />';
    
    echo stristr("Hello world!", "WORLD", true); // Hello
    echo '<br />';
    
    // 以 "o" 的 ASCII 值搜索字符串，并返回字符串的剩余部分
    echo stristr("Hello world!", 111); // o world!
    echo '<br />';
    echo stristr("Hello world!", "e"); // ello world!
    echo '<hr />';
    
    /**
     * strrchr 区分大小写
     * 返回从某个字符串在另一个字符串中最后一次出现的位置到主字符串结尾的所有字符
     */
    
    // 搜索 "Shanghai" 在字符串中的位置，并返回从该位置到字符串结尾的所有字符：
    echo strrchr("I love Shanghai!", "Shanghai"); // Shanghai!
    echo '<br />';
    
    echo strrchr("I love Shanghai!", "love"); // love Shanghai!
    echo '<br />';
    
    echo strrchr("I love Shanghai!", "Love"); // 无返回 未找到
    echo '<br />';
    
    // 支持二进制字符搜索
    // 以 "o" 的 ASCII 值搜索 "o" 在字符串中的位置，并返回从该位置到字符串结尾的所有字符
    echo strrchr("Hello world!", 101); // ello world!
    echo '<hr />';
    
    /**
     * strpos() 查找字符串在另一字符串中第一次出现的位置（区分大小写） 获取某个字符在字符串中的索引位置 从0开始
     * strrpos() 查找字符串在另一字符串中最后一次出现的位置（区分大小写）
     *
     * stripos() 查找字符串在另一字符串中第一次出现的位置（不区分大小写）
     * strripos() 查找字符串在另一字符串中最后一次出现的位置（不区分大小写
     */
    echo "\n";
    echo strpos("Hello", "H"), "\n"; // 0
    echo strpos("You love php, I love php too!", "php"); // 9
    echo "\n";
    
    echo strripos("You love php, I love php too!", "PHP"); // 21
    echo "\n";
}

function function_name11()
{
    /**
     * array()
     *
     * 索引数组 - 带有数字索引的数组
     *
     * 关联数组 - 带有指定的键的数组
     *
     * 多维数组 - 包含一个或多个数组的数组
     */
    echo "<h1>定义数组</h1>";
    $cars = array(
        "Volvo",
        "BMW",
        "Toyota"
    );
    echo "I like " . $cars[0] . ", " . $cars[1] . " and " . $cars[2] . ".";
    echo '<hr />';
    
    $age = array(
        "Bill" => "60",
        "Steve" => "56",
        "Mark" => "31"
    );
    echo "Bill is " . $age['Bill'] . " years old.";
    echo '<hr />';
    
    // 覆盖变量
    $cars = array(
        "Volvo1",
        "BMW1",
        "Toyota1"
    );
    $arrlength = count($cars);
    
    for($x = 0; $x < $arrlength; $x ++)
    {
        echo $cars[$x];
        echo "<br>";
    }
    echo '<hr />';
    
    $age = array(
        "Bill" => "60",
        "Steve" => "56",
        "Mark" => "31"
    );
    
    foreach ($age as $x => $x_value)
    {
        echo "Key=" . $x . ", Value=" . $x_value;
        echo "<br>";
    }
    echo '<hr />';
    
    // 二维数组：
    $cars = array(
        array(
            "Volvo",
            100,
            96
        ),
        array(
            "BMW",
            60,
            59
        ),
        array(
            "Toyota",
            110,
            100
        )
    );
    echo "<h1>遍历一个二维数组</h1>";
    
    // 外层循环出一个数组
    foreach ($cars as $arr)
    {
        // 在从数组元素中继续得到数组
        /*
         * foreach ($arr as $item)
         * {
         * echo $item . ' -';
         * }
         */
        
        foreach ($arr as $key => $val)
        {
            echo $key . "  " . $val . "<br />";
        }
    }
    echo '<hr />';
    
    /**
     * count() 函数返回数组中元素的数目。
     * count(array,mode);
     *
     * mode参数：
     * 0 - 默认。不对多维数组中的所有元素进行计数；
     * 1 - 递归地计数数组中元素的数目（计算多维数组中的所有元素）
     *
     * count() 函数计算数组中的单元数目或对象中的属性个数。
     * 对于数组，返回其元素的个数，对于其他值，返回 1。如果参数是变量而变量没有定义，则返回 0。
     * 如果 mode 被设置为 COUNT_RECURSIVE（或 1），则会递归底计算多维数组中的数组的元素个数。
     */
    
    echo "<h1>count:统计数组的元素</h1>";
    $carsArt = array(
        "Volvo",
        "BMW",
        "Toyota"
    );
    echo count($carsArt); // 3个元素
    echo '<br />';
    
    echo count($cars); // 3 统计出三个数组元素
    echo '<br />';
    
    echo count($cars, 1); // 12 统计出了所有元素
    
    /**
     * sizeof() 函数计算数组中的单元数目或对象中的属性个数
     * sizeof() 函数是 count() 函数的别名
     * 注释：当变量未被设置，或是变量包含一个空的数组，该函数会返回 0。可使用 isset() 变量来测试变量是否被设置
     */
    echo '<hr />';
    echo "<h1>sizeof:统计数组的元素</h1>";
    echo sizeof($carsArt); // 3个元素
    
    echo '<br />';
    echo sizeof($cars); // 3 统计出三个数组元素
    
    echo '<br />';
    echo sizeof($cars, 1); // 12 统计出了所有元素
}

function function_name12()
{
    // 定义一个数组
    $arr = array();
    
    // 为数组进行赋值
    $arr[] = 1;
    $arr[] = "2";
    $arr[] = true;
    $arr[] = 10.3;
    
    var_dump($arr);
    
    // 统计数组元素
    echo "数组的个数" . count($arr); // 数组的个数4
                                
    // 定义键值类型的数组
    $arr = array(
        "a" => 1,
        "b" => 2,
        "c" => 3
    );
    echo "\n";
    var_dump($arr);
    echo "\n";
    
    // $arr=array(1,2,3,4,5,7,9);
    
    /*
     * for ($i = 0; $i < count($arr); $i++) {
     * echo $arr[$i]."\n";
     * }
     */
    
    // 遍历数组
    foreach ($arr as $key => $value)
    {
        echo $key . ' ' . $value . "\n";
    }
    // a 1 b 2 c 3
}

function function_name13()
{
    // PHP 运算符
    
    // ///////////////////////////////////////////////////演示整型求余 取模 和四舍五入
    $a = 10;
    $b = 3;
    
    echo $a % $b; // 求玉树
    echo "\n";
    echo $a / $b; //
    
    echo "\n";
    echo intval(4.3);
    echo "\n";
    echo intval(4.6);
    
    echo "\n";
    echo "\n";
    echo round(3.4); // 3
    echo "\n";
    echo round(3.5); // 4
    echo "\n";
    echo round(3.6); // 4
    echo "\n";
    echo round(3.6, 0); // 4
    echo "\n";
    echo round(1.95583, 2); // 1.96
    echo "\n";
    echo round(1241757, - 3); // 1242000
    echo "\n";
    echo round(5.045, 2); // 5.05
    echo "\n";
    echo round(5.055, 2); // 5.06
    echo "\n";
    // ///////////////////////////////////////////////////////////
    $var1 = 10;
    
    $x = 2;
    $x += 3;
    echo $x;
    
    echo "\n";
    $y = $x + "10 Hello";
    echo $y; // 15
    
    echo "\n";
    $z = $x . "10 Hello";
    echo $z; // 510 Hello
    
    echo "\n";
    echo $x; // 5
    echo "\n";
    $x .= $x . "10 Hello"; // 哪一步先运算？
    echo $x; // 5510 Hello
    echo "\n全等于演示\n";
    
    $a1 = 123;
    $a2 = "123";
    $a3 = 123;
    
    var_dump($a1 === $a2); // bool(false)
    
    var_dump($a1 === $a3); // bool(true)
                           
    // ///////////////////////////////////////////////////演示数组运算符
    echo "\n\n";
    
    $arr1 = array(
        1,
        2,
        3
    );
    $arr2 = array(
        4,
        5,
        6
    );
    
    $arr3 = array(
        1,
        2,
        3
    );
    $arr4 = array(
        "1",
        2,
        3
    );
    $arr5 = array(
        "a" => 1,
        "b" => 2,
        "c" => 3
    );
    $arr6 = array(
        3,
        2,
        1
    );
    
    print_r($arr1);
    print_r($arr5);
    
    var_dump($arr1 === $arr2); // bool(false)
    var_dump($arr1 === $arr3); // bool(true)
    var_dump($arr1 === $arr4); // bool(false)
    var_dump($arr1 === $arr5); // bool(false)
    var_dump($arr1 == $arr6); // bool(false)
    
    $arr1_4 = $arr1 + $arr5;
    print_r($arr1_4);
}





function function_xml()
{
    // 资源和字符串的转换
    // 建立一种资源
    $fp = fopen("peoples.xml", "r");
    // 显示资源的类型 stream
    echo get_resource_type($fp);
    // 关闭一个资源
    fclose($fp);
}

function function_name16($param)
{
    /*
     * 学习要点
     * extract：将一个数组中的键值对 赋值给定义的变量
     * isset：变量是否设置了
     * empty:变量值是否为空
     */
    $a = 'Original';
    $b = '';
    $c = '';
    
    $my_array = array(
        "a" => "Cat",
        "b" => "Dog",
        "c" => "Horse"
    );
    // 将键值 "Cat"、"Dog" 和 "Horse" 赋值给变量 $a、$b 和 $c：
    extract($my_array);
    
    echo "\$a=" . $a;
    echo '<br />';
    
    // 在一行里输出多个变量值
    echo "\$a = $a; \$b = $b; \$c = $c";
    echo '<br />';
    
    echo "$a;$b;$c;";
}



function function_name18($param)
{
    /*
     * 学习要点
     * 1.
     */
    $basedir = dirname(__FILE__);
    
    // D:\wamp\www\Demo\other
    echo $basedir;
}

function function_name19($param)
{
    /*
     * 学习要点
     * 每次请求都会指定一次
     */
    if(! isset($wp_did_header))
    {
        
        $wp_did_header = true;
        
        // Load the WordPress library.
        // require_once( dirname(__FILE__) . '/wp-load.php' );
        
        // Set up the WordPress query.
        // wp();
        
        // Load the theme template.
        // require_once( ABSPATH . WPINC . '/template-loader.php' );
        echo 'OK';
    }
}

class WzHttp
{

    /*
     * 学习要点
     * curl_exec
     * get请求
     * 多输出了一个1
     */
    public static function curlPost($url, $data = array(), $timeout = 10)
    {
        // 判断是否是https
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        
        // 初始化curl
        $ch = curl_init();
        
        /*
         * $opt = array(
         * CURLOPT_URL => $url,
         * CURLOPT_POST => 1,
         * CURLOPT_HEADER => 0,
         * CURLOPT_POSTFIELDS => (array)$data,
         * CURLOPT_RETURNTRANSFER => 1,
         * CURLOPT_TIMEOUT => $timeout,
         * );
         */
        
        // 设置参数选项
        $opt = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false
        );
        // CURLOPT_POST=>true
        
        // https选项
        if($ssl)
        {
            $opt[CURLOPT_SSL_VERIFYHOST] = 2;
            $opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
        }
        
        // 设置选项
        curl_setopt_array($ch, $opt);
        
        // 返回数据
        $data = curl_exec($ch);
        
        // 关闭资源
        curl_close($ch);
        
        return $data;
    }

    public static function test()
    {
        // 创建一个新cURL资源
        $ch = curl_init();
        
        // 设置URL和相应的选项
        $options = array(
            CURLOPT_URL => 'http://www.runoob.com/',
            CURLOPT_HEADER => false
        );
        
        curl_setopt_array($ch, $options);
        
        // 抓取URL并把它传递给浏览器
        $data = curl_exec($ch);
        
        echo $data;
        
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
    }
}

// echo WzHttp::curlPost('https://raw.githubusercontent.com/weisay/theme/master/weisayheibai_latest_version.js');
function function_name_cookie($param)
{
    // 标准演示cookie
    // 设置一个最简单的浏览器回话cookie
    // setcookie("oc_php","my cookie");
    
    // 如果客户端访问时 没有收到一个cookie【没有检测到】 表示客户是第一次来访问 此时就向客户端 写入一个cookie
    if(! isset($_COOKIE["PHP_Cookie"]))
    {
        setcookie("PHP_Cookie", date("y-m-d H:i:s"));
        echo '欢迎访问!';
    }
    else
    {
        // 如果检测到了 就刷新cookie的值写入此次访问的时间 并设置cookie过期时间是60秒
        // 刷新值并刷新其时间
        setcookie("PHP_Cookie", date("y-m-d H:i:s"), time() + 60);
        echo "上次访问的时间：" . $_COOKIE["PHP_Cookie"];
        echo '<br />';
    }
    
    echo "本次访问的时间：" . date("y-m-d H:i:s");
    echo '<br />';
}

function function_name20($param)
{
    /*
     * echo $_ENV['OS'];//没有发现
     *
     * setcookie("name","value");
     *
     * $_COOKIE["name"];
     * $_GET["a"];
     * $_POST["b"];
     * $_FILES;
     *
     * session_start();
     * $c="123";
     * session_register("abc");
     * echo $_SESSION["abc"];
     */
    print_r($_SERVER);
    echo '<hr />';
    
    print_r($_REQUEST);
    echo '<hr />';
    
    print_r($_ENV);
    echo '<hr />';
    
    print_r($_COOKIE);
    echo '<hr />';
}

function data_type($param)
{
    // PHP数据类型
    $i = 100;
    var_dump($i);
    
    $f = 10.9;
    var_dump($f);
    
    $cars = array(
        "Volvo",
        "BMW",
        "SAAB"
    );
    var_dump($cars);
    echo '<hr>';
    
    // 定义一个最大的整型变量值
    $big_number = 2147483647; // 都是带有符号的 2的32次方 加上一个符号为 折中，所以存储的最大数值为2147483647
    var_dump($big_number); // 输出int 2147483647
    echo "<br>";
    
    // 定义一个超出整型变量的值
    $large_number = 2147483648;
    var_dump($large_number); // 输出 float 2147483648 超出范围之后将会自动转换为float类型
    echo "<br>";
    
    // 转整型
    echo '<hr>';
    $result = ((0.1 + 0.7) * 10);
    echo $result;
    var_dump((int) $result); // int 8 有点奇怪哦 注意将运算结果还有确定的表达式转为int类型时，会发生不可预料的结果
    
    echo '<hr>';
    echo '转化布尔型TRUE为字符串';
    echo "<br>";
    echo ((string) TRUE); // 输出 1
    var_dump((string) TRUE); // string '1' (length=1)
    echo "<br>";
    echo '<hr>';
    
    echo (string) 123;
    
    echo '<hr>';
    echo '转化数组为字符串';
    echo "<br>";
    $numbers = array(
        '1',
        '2',
        '3'
    );
    var_dump($numbers);
    echo "<br>";
    // var_dump((string)$numbers);//出现错误 Array to string conversion
    echo "<br>";
    
    // //////////////////////////////////////字符串是否能够转为对象
    // echo (object)"Hello";
    // var_dump((object)"Hello");
    // //////////////////////////////////////NULL转为字符串
    echo '<hr />';
    echo '转化NULL为字符串';
    echo "<br>";
    var_dump((string) NULL); // string '' (length=0)
    echo '<hr />';
    // //////////////////////////////////////测试NULL
    echo '<hr />';
    $a1 = null;
    $a2 = '';
    $a3 = ' ';
    echo '<hr />';
    
    if(is_null($a1))
    {
        echo '$a1' . 'yes';
    }
    else
    {
        echo '$a1' . 'NO';
    }
    
    echo '<hr />';
    
    if(is_null($a2))
    {
        echo '$a2' . 'yes';
    }
    else
    {
        echo '$a2' . 'NO';
    }
    echo '<hr />';
    if(is_null($a3))
    {
        echo '$a3' . 'yes';
    }
    else
    {
        echo '$a3' . 'NO';
    }
    echo '<hr />';
}

// //////////////////////////////////////

// 认识$GLOBALS 超全局变量
// print_r($GLOBALS);

function function_name97()
{
    // PHP基本语法
    
    // phpinfo();
    // 注释1
    // 注释1
    
    /*
     * 1
     * 2
     * 3
     */
    $str = "Hello world nihao!";
    echo $str;
    
    $strn = "";
    echo $strn;
    
    // isset 用来检测一个变量是否定义
    if(isset($strn))
        echo "Is set";
    else
        echo "No set";

    // global 关键字起到了传递参数的作用 在函数中
    function myfunction($v)
    {
        global $str;
        $strc = "123"; // 局部变量
        echo $str;
        echo $v;
    }
    
    myfunction("myfunction");
    echo '<hr />';

    function myfunction2()
    {
        echo $GLOBALS['str'];
    }
    myfunction2();
    
    echo '<hr>';
    // $GLOBALS 是一个全局的变量数据 我们可以查看一下它里面的数据
    // 发现其里面存储很多的值
    var_dump($GLOBALS);
}

