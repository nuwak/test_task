<?php
/**
 * Сложение двух больших чисело в столбик
 *
 * @param $num1
 * @param $num2
 * @return string Сумма чисел
 */
function sum(string $num1, string $num2): string
{
    $sum = '';
    $flag = 0;
    $num1Len = strlen($num1);
    $num2Len = strlen($num2);
    $maxLen = max($num1Len, $num2Len);

    if ($num1Len < $maxLen) {
        $num1 = str_repeat('0', $maxLen - $num1Len) . $num1;
    } else {
        $num2 = str_repeat('0', $maxLen - $num2Len) . $num2;
    }

    for ($i = $maxLen - 1; $i >= 0; $i--) {
        $sumTemp = $num1[$i] + $num2[$i] + $flag;
        if ($sumTemp >= 10) {
            $sumTemp -= 10;
            $flag = 1;
        } else {
            $flag = 0;
        }
        $sum[$i] = $sumTemp;
    }

    if ($flag === 1) {
        $sum = $flag . $sum;
    }

    return $sum;
}
