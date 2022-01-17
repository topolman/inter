<?php
/**
 * 01. Алгоритм.
 * Вставить $a в индексный (простой) массив целых чисел после всех элементов, в которых есть цифра 2.
 * Новый массив создавать нельзя.
 * Использовать array_splice нельзя.
 */
$arr = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
$a = '$a';

function array_place ($array, $key, $value){
  return array_merge(
    array_slice($array,0,$key),
    array($value),
    array_slice($array,$key)
  );
}

foreach (array_reverse($arr, true) as $key => $value) {
  if (strpos($value,'2')!==false){
    $arr = array_place($arr, $key+1, $a);
  }
}

var_dump($arr);
?>