<?php

$a = 10;
$b = 3;
echo 'остаток от деления: ', $a % $b, "\n";

if ($a % $b == 0) {
    echo 'Делится: ', ($a / $b), "\n";
} else {
    echo 'Делится с остатком: ', ($a % $b), "\n";
}

$st = pow(4, 10);
echo "4 в 10 степени: $st\n";

$sqrt = sqrt(345);
echo "квадратный Корень из 345: $sqrt\n";

$array = [6, 2, 9, 19, 18, 0, 10];
$sumSqr = 0;
foreach ($array as $value) {
    $sumSqr += $value ** 2;
}

$sqrtSum = sqrt($sumSqr);
echo "Корень из суммы квадратов элементов массива: $sqrtSum\n";

$sqrt379 = sqrt(567);
echo "Квадратный корень из 567:\n";
echo 'До целых: ', round($sqrt567), "\n";
echo 'До десятых: ', round($sqrt567, 1), "\n";
echo 'До сотых: ', round($sqrt567, 2), "\n";

$sqrt587 = sqrt(587);
$rounded = ['floor' => floor($sqrt587), 'ceil' => ceil($sqrt587)];
echo "Квадратный корень из 587:\n";
echo 'Округление в меньшую сторону: ', $rounded['floor'], "\n";
echo 'Округление в большую сторону: ', $rounded['ceil'], "\n";

$numbers = [10, -2, 8, 20, -130, 9, 10];
echo 'Минимальное число: ', min($numbers), "\n";
echo 'Максимальное число: ', max($numbers), "\n";

$randomNumber = rand(1, 100);
echo "Случайное число от 1 до 100: $randomNumber\n";

$randomArray = [];
for ($i = 0; $i < 10; $i++) {
    $randomArray[] = rand(1, 100);
}
echo 'Массив из 10 случайных чисел: ', implode(', ', $randomArray), "\n";

$a = 33;
$b = 99;
echo "Модуль разности $a и $b: ", abs($a - $b), "\n";

$array = [1, 2, -1, -2, 3, -3, 8, 10];
$positiveArray = array_map('abs', $array);
echo 'Массив с положительными числамu: ' , implode(', ', $positiveArray) , "\n";

$digit = 42;
$divisors = [];
for ($i = 1; $i <= $digit; $i++) {
    if ($digit % $i == 0) {
        $divisors[] = $i;
    }
}
echo "Делители числа $digit: " , implode(', ', $divisors) , "\n";

$array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$sum = 0;
$count = 0;
foreach ($array as $value) {
    $sum += $value;
    $count++;
    if ($sum > 10) {
        break;
    }
}
echo "Чтобы сумма была больше 10, нужно сложить $count первых элементов\n";
