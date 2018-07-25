<?php

/*
 * 学习要点
 * 递归函数
 */

function draw($total, $line = 1, $row = 1, $result = "<table border=2><tr>")
{
    if($line > $total)
    {
        return;
    }
    else
    {
        $result .= "<td>$line</td>";
        $line ++;
        $row ++;
        draw($total, $line, $row, $result); // 原书中这个地方使用了引用符号
    }
    echo $result .= "</tr></table>";
}
draw(20);
