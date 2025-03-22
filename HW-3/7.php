<?php

function printStringReturnNumber()
{
    echo "Строка из функции \n";
    return 42;
}

$number_num = printStringReturnNumber();
echo "return: $number_num\n";
