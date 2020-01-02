<?php

$string = "あけおめ！";
$input = htmlspecialchars($string);

include_once(__DIR__."/Lupin.php");

$lupin = new Lupin;
$lupin->get_lupin($input);
