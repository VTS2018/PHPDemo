<?php
/**
 * array_push：函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
 * 该函数等于多次调用 $array[] = $value。
 */

// 提示和注释
// 注释：即使数组中有字符串键名，您添加的元素也始终是数字键。（参见例子 2）
// 注释：如果用 array_push() 来给数组增加一个单元，还不如用 $array[] =，因为这样没有调用函数的额外负担。
// 注释：如果第一个参数不是数组，array_push() 将发出一条警告。这和 $var[] 的行为不同，后者会新建一个数组。

// 向数组尾部插入 "blue" 和 "yellow"：
echo "<h1>array_push</h1>";
$a = array("red","green");
$count = array_push($a, "blue", "yellow");
print_r($a);
echo $count; // 4
echo '<hr />';

// 带有字符串键名的数组：
$a = array("a" => "red","b" => "green");
array_push($a, "blue", "yellow");
print_r($a); // 添加的还是数字键 从0 1 开始
echo '<hr />';

/**
 * array_pop：删除数组中的最后一个元素
 * 返回数组的最后一个值。如果数组是空的，或者非数组，将返回 NULL。
 */
echo "<h1>array_pop</h1>";
$a = array("red","green","blue");
$returnValue = array_pop($a); // 返回被删除的元素blue
print_r($a); // blue 元素被删除了
echo $returnValue;//输出 blue
echo '<hr />';
?>


<?php
/**
 * array_key_exists() 函数检查某个数组中是否存在指定的键名，如果键名存在则返回 true，如果键名不存在则返回 false
 * 提示：请记住，如果您指定数组的时候省略了键名，将会生成从 0 开始并且每个键值对应以 1 递增的整数键名
 * key_exists
 */
echo "<h1>array_key_exists</h1>";
$a = array("Volvo" => "XC90","BMW" => "X5");

// key:要检查的键 arr：所在的数组
if (array_key_exists("Volvo", $a))
{
    echo "键存在！";
} 
else 
{
    echo "键不存在！";
}
echo '<hr />';

$a = array(
    "Volvo" => "XC90",
    "BMW" => "X5"
);

if (key_exists("Toyota", $a)) {
    echo "键存在！";
} else {
    echo "键不存在！";
}
echo '<hr />';

// 检查整数键名 "0" 是否存在于数组中
$a = array(
    "Volvo",
    "BMW"
);
if (array_key_exists(0, $a)) {
    echo "键存在！";
} else {
    echo "键不存在！";
}
echo '<hr />';

/**
 * in_array() 函数搜索数组中是否存在指定的值
 * 注释：如果 search 参数是字符串且 type 参数被设置为 TRUE，则搜索区分大小写
 * in_array(search,array,type)
 * 如果给定的值 search 存在于数组 array 中则返回 true。
 * 如果第三个参数设置为 true，函数只有在元素存在于数组中且数据类型与给定值相同时才返回 true
 * 如果没有在数组中找到参数，函数返回 false
 */

$people = array(
    "Bill",
    "Steve",
    "Mark",
    "David",
    "mark"
);
// 默认不区分大小写
if (in_array("Mark", $people)) {
    echo "匹配已找到";
} else {
    echo "匹配未找到";
}
echo '<hr/>';

//再看看
if (in_array("23", $people, TRUE)) {
    echo "匹配已找到<br>";
} else {
    echo "匹配未找到<br>";
}

if (in_array("Mark", $people, TRUE)) {
    echo "匹配已找到<br>";
} else {
    echo "匹配未找到<br>";
}

if (in_array(23, $people, TRUE)) {
    echo "匹配已找到<br>";
} else {
    echo "匹配未找到<br>";
}
echo '<hr />';

/**
 * array_keys() 函数返回包含数组中所有键名的一个新数组
 * 如果提供了第二个参数，则只返回键值为该值的键名
 * 如果 strict 参数指定为 true，则 PHP 会使用全等比较 (===) 来严格检查键值的数据类型
 * 参数列表
 * array_keys(array,value,strict)
 */

$a=array("Volvo"=>"XC90","BMW"=>"X5","Toyota"=>"Highlander");
//只返回三个键的数组
print_r(array_keys($a));//Array ( [0] => Volvo [1] => BMW [2] => Toyota ) 
echo '<hr />';

$a=array("Volvo"=>"XC90","BMW"=>"X5","Toyota"=>"Highlander");
//只返回这一个值的键名
print_r(array_keys($a,"Highlander"));//Array ( [0] => Toyota ) 
echo '<hr />';

//设置第三个参数来比较数据类型
$a=array(10,20,30,"10");
//说明元素10 在数组中出现了两次  一次是数字 一次是字符串
print_r(array_keys($a,"10",false));//Array ( [0] => 0 [1] => 3 ) 
echo '<hr />';

//设置TRUE 就检查数据类型了，只返回字符串格式的键名
print_r(array_keys($a,"10",true));//Array ( [0] => 3 ) 
echo '<hr />';

/**
 * array_values() 函数返回一个包含给定数组中所有键值的数组，但不保留键名
 * 被返回的数组将使用数值键，从 0 开始并以 1 递增
 */
$a=array("Name"=>"Bill","Age"=>"60","Country"=>"USA");
print_r(array_values($a));//Array ( [0] => Bill [1] => 60 [2] => USA ) 
echo '<hr />';

/**
 * ksort() 函数对 关联数组 按照 键名 进行升序排序
 * 提示：请使用 krsort() 函数对关联数组按照键名进行降序排序。
 * 提示：请使用 asort() 函数对关联数组按照键值进行升序排序。
 * 
 * 参数
 * ksort(array,sortingtype);
 * 
 * ksort() 函数按照键名对数组排序，为数组值保留原来的键
 * 可选的第二个参数包含附加的排序标志 可选参数指定如何对键值应用排序的规则。
 * 若成功，则返回 TRUE，否则返回 FALSE。
 * 
 */

$age=array("Steve"=>"56","Bill"=>"60","mark"=>"31");
ksort($age);//升序 Array ( [Bill] => 60 [Steve] => 56 [mark] => 31 )
print_r($age);
echo '<hr />';

krsort($age);//降序
print_r($age);
echo '<hr />';//降序 Array ( [mark] => 31 [Steve] => 56 [Bill] => 60 )

/**
 * asort() 函数对 关联数组 按照键值进行降序排序。
 * 提示：请使用 arsort() 函数对关联数组按照键值进行降序排序。
 * 提示：请使用 ksort() 函数对关联数组按照键名进行升序排序。
 * 
 */

$age=array("Bill"=>"60","Steve"=>"56","Mark"=>"31");
asort($age);//升序 Array ( [Mark] => 31 [Steve] => 56 [Bill] => 60 ) 
print_r($age);
echo '<hr />';

arsort($age);
print_r($age);//降序 Array ( [Bill] => 60 [Steve] => 56 [Mark] => 31 ) 
echo '<hr />';


/**
 * sort() 函数对 索引数组 进 行升序排序
 * 注释：本函数为数组中的 单元赋予新的键名 。原有的键名将被删除
 * 如果成功则返回 TRUE，否则返回 FALSE
 * 
 * sort(array,sortingtype);
 */
$cars=array("Volvo","BMW","Toyota");

sort($cars);
print_r($cars);//Array ( [0] => BMW [1] => Toyota [2] => Volvo ) 
echo '<hr />';

rsort($cars);
print_r($cars);//Array ( [0] => Volvo [1] => Toyota [2] => BMW ) 
echo '<hr />';


$numbers=array(4,6,2,22,11);

sort($numbers);
print_r($numbers);//Array ( [0] => 2 [1] => 4 [2] => 6 [3] => 11 [4] => 22 ) 
echo '<hr />';

rsort($numbers);
print_r($numbers);//Array ( [0] => 22 [1] => 11 [2] => 6 [3] => 4 [4] => 2 ) 
echo '<hr />';

