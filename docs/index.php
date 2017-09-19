<?php

include_once 'src/class/digitsToString.php';
$number = 1396.1232001;
$date = '1396.05.18';
echo $number.'<br>'.$date.'<br>';
$digits = new digitsToString('fa');
echo $digits->convert($number).'<br>';
echo $digits->dateConvert($date).'<br>';

$sample = new digitsToString();
echo $sample->convert($number).'<br>';
echo $sample->dateConvert($date).'<br>';