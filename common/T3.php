<?php require ('VTSCommon.php'); ?>

<?php
// 测试：is_ip_address
echo Utils::vts_is_ip_address("192.168.1.100"); // 4
echo "\n";

echo Utils::vts_is_ip_address('2001:0:2a7b:7ded:9f:1a5b:3f57:ff97'); // 6
echo "\n";

$var = Utils::vts_is_ip_address("192.168.1.1009");
echo (string) $var; // 此处奇怪 为什么不输出？

var_dump($var); // bool(false)
var_dump(Utils::vts_is_ip_address("192.168.1.100")); // int(4)
?>

<?php
// 测试：array2String
$returnData = array(
    'transType' => 'sales',
    'orderNo' => '201510198033887',
    'merNo' => '968',
    'terNo' => '88816',
    'currencyCode' => 'USD',
    'amount' => '98.14',
    'tradeNo' => 'HP1510190848271792',
    'respCode' => '00',
    'respMsg' => '00:success',
    'cn' => '这是一句中国话'
);
echo "\n";
echo Utils::vts_array2String($returnData);

echo "\n";
echo urldecode(Utils::vts_array2String($returnData));
echo "\n";
?>

<?php
echo "\n";
echo Utils::vts_get_online_ip();
echo "\n";
?>

<?php
// 定义key
$key = '1234abcd1234abcd1234abcd';
// 要加密的字符串
$str = "Hello World！";

$des = new DEScrypt24($key);
// 加密
$mis = $des->encrypt($str);
echo $mis;
echo "\n";

// 解密
echo $des->decrypt($mis);
echo "\n";
?>



<?php
// 中文值 按3个字节一个中文字
$imginfo = array(
    "_foldername" => "027-xianren/1001",
    "_mainimg" => "201605272253331315.jpg",
    "_detailimg" => "201605272253331315.jpg|201605272253331965.jpg",
    "_video" => "如果是中文呢AA"
);
echo "\n";
// 数组转json
$json = Utils::vts_array2json($imginfo);
echo $json;
echo "\n";

// 默认转换成一个对象
$jsonArr = Utils::vts_json2array($json);
var_dump($jsonArr);

// 转换成一个键值对数组
$jsonArr = Utils::vts_json2array($json, TRUE);
var_dump($jsonArr);
?>



<?php
echo "\n";
echo Utils::vts_get_file_name('../www/htdocs/your_image.jpg');
echo "\n";
echo Utils::vts_get_file_full_name('../www/htdocs/your_image.jpg');

echo "\n";
echo Utils::vts_get_file_extension('../www/htdocs/your_image.jpg');
echo "\n";
echo Utils::vts_get_file_dir('../www/htdocs/your_image.jpg');
?>



<?php
$post_data = array(
    'username' => 'stclai r2201',
    'password' => 'ha nd+a&n'
);
echo Utils::vts_create_post_data($post_data);
?>


<?php
echo "\n";
// 支持：中文 英文 特殊字符 德语 日语 俄罗斯语言
$string = '90sdfa中国<>! @#$%^&*()_+||\``öffentlich が発生する происходит  с гордостью ';
echo Utils::vts_string_length($string); // 28

echo "\n";
// 此种遍历方法会导致中文乱码
for($i = 0; $i < Utils::vts_string_length($string); $i ++)
{
    echo $string[$i] . "\n";
}

// 此种方法遍历逐个字符
print_r(Utils::vts_str_split_unicode($string));
// 表示两个一组
print_r(Utils::vts_str_split_unicode($string, 2));
?>


<?php
var_dump(Utils::vts_check_single_en_letter("A"));
var_dump(Utils::vts_check_single_en_letter("a"));

var_dump(Utils::vts_check_single_en_letter("B1"));
var_dump(Utils::vts_check_single_en_letter("aB"));

var_dump(Utils::vts_check_single_decimal_number("A"));
var_dump(Utils::vts_check_single_decimal_number("a"));

var_dump(Utils::vts_check_single_decimal_number("B1"));
var_dump(Utils::vts_check_single_decimal_number("aB"));

var_dump(Utils::vts_check_single_decimal_number("1"));
var_dump(Utils::vts_check_single_decimal_number("02"));
var_dump(Utils::vts_check_single_decimal_number("3"));
var_dump(Utils::vts_check_single_decimal_number("4"));
var_dump(Utils::vts_check_single_decimal_number(""));
var_dump(Utils::vts_check_single_decimal_number(" "));

?>










