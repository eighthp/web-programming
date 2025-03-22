<?php

$array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
$average = array_sum($array) / count($array);
echo "Среднее арифметическое: $average\n";

$sum = array_sum(range(1, 100));
echo "Сумма чисел от 1 до 100: $sum\n";

$arr_num = [1, 3, 5, 9, 13, 14, 54, 42];
$squareRoots = array_map('sqrt', $arr_num);
print_r($squareRoots);

$letters = range('a', 'z');
$numbers = range(1, 26);
$array = array_combine($letters, $numbers);
print_r($array);

$numbers = '1234567890';
$pairs = str_split($numbers, 2);
$sumOfPairs = array_sum($pairs);
echo "Сумма пар чисел: $sumOfPairs\n";
