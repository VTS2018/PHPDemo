<?php

/**
 * array_map() 函数将用户自定义函数作用到数组中的每个值上，并返回用户自定义函数作用后的带有新值的数组
 * 回调函数接受的参数数目应该和传递给 array_map() 函数的数组数目一致
 * 提示：您可以向函数输入一个或者多个数组。
 * 
 * 参数
 * array_map(myfunction,array1,array2,array3...)
 * 
 * 思考：其实这种机制在asp.net中list数据结构中也存在，并不奇怪
 * List.foreach
 * 从这一点来看：asp.net的学习难度要比PHP大
 * 
 * 疑问：接收两个参数无效果
 */

// 使用用户自定义函数来改变数组的值
function myfunction($v)
{
    return ($v * $v);
}

$a = array(
    1,
    2,
    3,
    4,
    5
);

$b = array(
    9,
    8,
    7,
    6,
    5
);

// 使用两个数组：
// 把用户定义的函数传递到 函数中去，函数中的每个元素将自动 应用这个函数
print_r(array_map("myfunction", $a)); // Array ( [0] => 1 [1] => 4 [2] => 9 [3] => 16 [4] => 25 )
echo '<hr />';

// 这个回调函数接收两个参数，那么传递的也是两个参数
function myfunction2($v1, $v2)
{
    if ($v1 === $v2) {
        return "same";
    }
    return "different";
}

$c1 = array(
    "Horse",
    "Dog",
    "Cat"
);

$c2 = array(
    "Cow",
    "Dog",
    "Rat"
);
print_r(array_map("myfunction2", $c1, $c2)); // Array ( [0] => different [1] => same [2] => different )

echo '<hr />';

// 将数组首字母改为大写
function myfunction3($v)
{
    $v = strtoupper($v);
    return $v;
}

$a = array(
    "Animal" => "horse",
    "Type" => "mammal"
);
print_r(array_map("myfunction3", $a));
echo '<hr />';

// 将函数名赋值为 null 时
$a1 = array(
    "Dog",
    "Cat"
);
$a2 = array(
    "Puppy",
    "Kitten"
);

//得到合并之后的数据
print_r(array_map(null, $a1, $a2));
// Array ( [0] => Array ( [0] => Dog [1] => Puppy ) [1] => Array ( [0] => Cat [1] => Kitten ) )
?>