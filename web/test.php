<?php

$arr = [2, 4, 3, 543, 23, 35, 21, 12];
echo "<pre>";
print_r($arr); echo "simple array"; 
echo "<br />";
asort($arr);
echo "<pre>";
print_r($arr); echo "asort";
echo "<br />";
arsort($arr);
print_r($arr);
echo "<br />";
echo "arsort";
echo "<br />";
ksort($arr);
echo "<pre>";
print_r($arr); echo "ksort";
echo "<br />";
krsort($arr);
print_r($arr);
echo "<br />";
echo "krsort";