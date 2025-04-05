<?php
$str = "aha ac123a aeea adca axea axx2a";
preg_match_all('/a..a/', $str, $matches);
print_r($matches[0]);
?>