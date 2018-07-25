<?php

function compress_html()
{
    // 问题：PHP中如何压缩HTML去掉页面中的空白换行？
    
    /* 当前页面代码顶部 */
    
    // 开启php缓存
    if(extension_loaded('zlib'))
    {
        ob_end_clean();
        ob_start('ob_gzhandler');
    }
    else
    {
        ob_end_clean();
        ob_start();
    }
    
    // ...省略中间的html或php代码...
    require 'index.html';
    
    /* 当前页面代码底部 */
    // 获取脚本执行完后在缓存中的代码
    $content = ob_get_contents();
    
    // 进行去格式压缩代码
    // 替换掉\r\n \n \t 空白
    $content = ltrim(rtrim(preg_replace(array(
        "/> *([^ ]*) *</",
        "//",
        "'/\*[^*]*\*/'",
        "/\r\n/",
        "/\n/",
        "/\t/",
        '/>[ ]+</'
    ), array(
        ">\\1<",
        '',
        '',
        '',
        '',
        '',
        '><'
    ), $content)));
    
    // 清空关闭缓存，不直接输出到浏览器
    ob_end_clean();
    
    // 输出到浏览器
    echo $content;
}
compress_html();
// 还不够完美
