<?php
@header('Content-Type: text/html; charset=UTF-8');
/**
 * 学习要点
 * 学习载入文件指令的之间的区别
 *
 * include
 * 服务器端包含 (SSI) 用于创建可在多个页面重复使用的函数、页眉、页脚或元素。
 * include （或 require）语句会 获取指定文件中存在的所有文本/代码/标记，并复制到使用 include 语句的文件中
 * 【注意】仔细阅读上面这句话的含义，include是将一个文件的内容复制到文件中，本质上，相当于复制
 * 包含文件很有用，如果您需要在网站的多张页面上引用相同的 PHP、HTML 或文本的话
 *
 * require
 * 区别：
 *
 * include 和 require 语句是相同的，除了错误处理方面
 * require 会生成致命错误（E_COMPILE_ERROR）并停止脚本
 * include 只生成警告（E_WARNING），并且脚本会继续
 *
 * 请在此时使用 require：当文件被应用程序请求时。
 * 请在此时使用 include：当文件不是必需的，且应用程序在文件未找到时应该继续运行时。
 *
 * include_once
 * require_once
 * 作用
 * 可以减少代码的重复
 * nclude(_once)（"文件的路径"）与require(_once)（"文件的路径"）
 *
 * 理解
 * 说白了，就是用包含进来的文件中的内容 代替 include(_once),require(_once)那一行
 *
 * 注意
 * include/require 包含进来的文件必须要加<?php ?> 因为在包含时,首先理解文件内容是普通字符串,碰到<?php ?> 标签时,才去解释
 *
 * 路径
 * 可以用绝对路径，也可以用相对路径；windows下正反斜线都可以，linux下只认正斜线，所以最好用正斜线
 *
 * 区别
 * include是包含的意思，找不到文件时，会报warning的错误，然后程序继续往下执行
 * require是必须的意思，找不到文件时，会报fatal error （致命错误），程序停止往下执行
 * 加once后，系统会进行判断，如果已经包含，则不会再包含第二次
 *
 * 取舍
 * 比如是系统配置，缺少了，网站不让运行，自然用require，如果是某一段统计程序，少了，对网站只是少统计人数罢了，不是必须要的，可以用include
 * 而加不加once是效率上的区别，加上once，虽然系统帮你考虑了只加载一次，但系统的判断会是效率降低，
 * 因此，更应该在开发之初，就把目录结构调整好，尽量不要用_once的情况。
 *
 * 特殊用法
 * 利用include/require返回被包含页面的返回值
 * a.php页面中: ..... return $value;
 * b.php页面中:$v = include("a.php");
 * 这个用法在做网站配置的时候会偶尔碰到！
 */
?>

注意下面这两句代码要在Web浏览器中运行

<h1>Welcome to my home page!</h1>
<?php
include 'noFileExists.php';
echo "I have a $color $car.";
?>

<h1>Welcome to my home page!</h1>
<?php
require 'noFileExists.php';
echo "I have a $color $car.";
?>


