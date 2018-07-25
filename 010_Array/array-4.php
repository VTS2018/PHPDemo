<?php
/**
 * each() 函数返回 当前元素  的键名和键值，并将内部指针向前移动
 * 返回的数组中包括的四个元素：键名为 0，1，key 和 value。单元 0 和 key 包含有数组单元的键名，1 和 value 包含有数据
 * 如果内部指针越过了数组范围，本函数将返回 FALSE
 * 
 * 相关函数
 * 
 * current() - 返回数组中的当前元素的值
 * end()     - 将内部指针指向数组中的最后一个元素，并输出
 * next()    - 将内部指针指向数组中的下一个元素，并输出
 * prev()    - 将内部指针指向数组中的上一个元素，并输出
 * reset()   - 将内部指针指向数组中的第一个元素，并输出
 * 
 * 思考：这些函数封装了对指针的操作
 */
$people = array(
        "Bill",
        "Steve",
        "Mark",
        "David"
);
print_r(each($people));

/*
 * Array
 * (
 * [1] => Bill
 * [value] => Bill
 * [0] => 0
 * [key] => 0
 * )
 */
echo '<hr />';
reset($people);

// 下面这种格式不标准
while ((list ($key, $val) = each($people)) != false) {
    echo "$key => $val<br>";
}
echo '<hr />';

// 重置内部指针指向第一个元素
reset($people);
while ((list ($key, $val) = each($people)) != false) {
    echo "$key => $val<br>";
}
echo '<hr />';

// 演示所有相关的方法
$people = array(
        "Bill",
        "Steve",
        "Mark",
        "David"
);

echo current($people) . "<br>"; // 当前元素是 Bill
echo next($people) . "<br>"; // Bill 的下一个元素是 Steve

echo current($people) . "<br>"; // 现在当前元素是 Steve
echo prev($people) . "<br>"; // Steve 的上一个元素是 Bill

echo end($people) . "<br>"; // 最后一个元素是 David
echo prev($people) . "<br>"; // David 之前的元素是 Mark

echo current($people) . "<br>"; // 目前的当前元素是 Mark

echo reset($people) . "<br>"; // 把内部指针移动到数组的首个元素，即 Bill
echo next($people) . "<br>"; // Bill 的下一个元素是 Steve

print_r(each($people)); // 返回当前元素的键名和键值（目前是 Steve），并向前移动内部指针
echo '<hr />';

reset($people);
echo '<hr />';
while (($item = next($people)) != false) {
    echo $item . '-';
}
// Steve-Mark-David-
echo '<hr />';
/**
 * current() - 返回数组中的当前元素的值
 * 每个数组中都有一个内部的指针指向它的"当前"元素，初始指向插入到数组中的第一个元素
 * 提示：该函数不会移动数组内部指针。要做到这一点，请使用 next() 和 prev() 函数
 * current() 函数返回当前被内部指针指向的数组元素的值，并不移动指针。如果内部指针指向超出了单元列表的末端，current() 返回 FALSE
 */

?>

<?php
/**
 * array_rand
 * 返回包含随机键名的数组：
 *
 * 数组,键名 参数可选
 * array_rand(array,number)
 */

$a = array(
        "red",
        "green",
        "blue",
        "yellow",
        "brown"
);

$random_keys = array_rand($a, 3);

echo $a[$random_keys[0]] . "<br>";
echo $a[$random_keys[1]] . "<br>";
echo $a[$random_keys[2]];
echo '<hr />';

$a = array(
        "a" => "red",
        "b" => "green",
        "c" => "blue",
        "d" => "yellow"
);

//从数组返回一个随机键
print_r(array_rand($a, 1));
echo '<hr />';

//返回包含随机字符串键名的数组
$a=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
print_r(array_rand($a,2));
echo '<hr />';




?>
