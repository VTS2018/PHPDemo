<?php
// 动作
$action = '';
// 图路径
$path = '';
// 内容
$content = '';
// 获取动作
$action = getFormString('action');

switch ($action)
{
    case 'checkserver':
        echo 'ok';
        break;
    
    case 'checkfile':
        $path = vts_get_form_string('path');
        echo vts_check_file_exist($path) == true ? 'true' : 'false';
        break;
    
    case 'getsize':
        $path = vts_get_form_string('path');
        echo vts_get_file_size($path);
        break;
    
    case 'getcontent':
        $path = vts_get_form_string('path');
        echo vts_get_content_fromfile($path);
        break;
    
    case 'savecontent':
        $path = vts_get_form_string('path');
        $content = vts_get_form_string('content');
        vts_save_content_tofile($path, $content);
        break;
    
    default:
        echo '';
        break;
}

/**
 * 从Post中获取内容
 *
 * @param string $name
 * @return string
 */
function vts_get_form_string($name)
{
    if(isset($_POST[$name]) && ! empty($_POST[$name]))
    {
        return $_POST[$name];
    }
    return '';
}

/**
 * 检查文件
 *
 * @param string $path
 * @return boolean
 */
function vts_check_file_exist($path)
{
    return file_exists($path);
}

/**
 * 获取文件大小
 *
 * @param string $path
 * @return number
 */
function vts_get_file_size($path)
{
    if(filesize($path))
    {
        return filesize($path);
    }
    return 0;
}

/**
 * 从文件中获取内容
 *
 * @param string $jspath
 * @return string
 */
function vts_get_content_fromfile($jspath)
{
    $myfile = fopen($jspath, "r") or die("Unable to open file!");
    $content = fread($myfile, filesize($jspath));
    fclose($myfile);
    return $content;
}

/**
 * 保存内容到文件
 * 
 * @param string $path
 * @param string $content
 */
function vts_save_content_tofile($path, $content)
{
    $myfile = fopen($path, "w") or die("Unable to open file!");
    fwrite($myfile, $content);
    fclose($myfile);
}