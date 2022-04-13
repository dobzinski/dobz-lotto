#!/usr/bin/php
<?php
/******************************************

Robson Dobzinski
robson.dobzinski@gmail.com
---
2022-03-10

How to use:
  ~$ ./script.php \
	$1 (Limits numbers) \
	$2 (Number limit) \
	$3 (Total of rows / Loop) \
	$4 (OPTIONAL: List of fixed numbers: use comma or space to separate) \
	$5 (OPTIONAL: Use to format "0" left chars, default are two)

Examples:

  # No inform my numbers, random 1 to 60, select 6, and 10 loop
  ~$ ./script.php 1-60 6 10 
  
  # Inform list of numbers, input after 20 loop
  ~$ ./script.php 1-25 15 20 1,2,3,4,5
  
  # Another format to inform the numbers list
  ~$ ./script.php 1-25 15 20 '2 4 6'
  
  # Start 90 to 110, will ignore 80 and 150, ignore 91 duplicate, and format 3 chars (084)
  ~$ ./script.php 90-110 5 10 '84, 91, 100, 150, 91' 3

***/

function prepare($array) {
	global $table, $max, $map, $data, $row, $repeat;
	$card = array();
	if (count($array)>=$max) {
		$n = 0;
		shuffle($array);
		foreach($array as $k) {
			if ($n<$max) {
				$card[] = $k;
				$n++;
			} else {
				break;
			}
		}
	} else if (count($array)>0) {
		$n = 0;
		shuffle($array);
		foreach($array as $k) {
			if (count($array)<$max) {
				$card[] = $k;
				$n++;
			} else {
				break;
			}
		}
		if ($n<$max) {
			shuffle($table);
			foreach($table as $t) {
				if (!in_array($t, $card)) {
					if ($n<$max) {
						array_push($card, $t);
					}
					$n++;
				}
			}
		}
	} else {
		$n = 0;
		shuffle($table);
		foreach($table as $t) {
			if ($n<$max) {
				$card[] = $t;
				$n++;
			} else {
				break;
			}
		}
	}
	$i = '';
	$index = $card;
	asort($index);
	foreach($index as $s) {
		$i .= $s;
	}
	if (!in_array($i, $map)) {
		$map[] = $i;
		$repeat = 0;
		foreach($card as $k) {
			$data[$row][] = $k;
		}
		$row++;
	} else {
		$repeat++;
		if ($repeat>999) {
			echo "Sorry, the input number list are too low!\n";
			exit(3);
		}
		prepare($array);
	}
	return;
}
function result($array) {
	global $format;
	if (count($array)>0) {
		echo "\n";
		echo "Result:\n";
		echo "-----------\n";
		echo "\n";
		foreach($array as $k) {
			asort($k);
			$col = 0;
			foreach($k as $v) {
				echo str_pad($v, $format, '0', STR_PAD_LEFT);
				if (count($k)>($col+1)) {
					echo " - ";
				}
				$col++;
			}
			echo "\n";
		}
	}
	return;
}

if (!isset($argv[3])) {
	echo "Please, check parameters!\n";
	exit(1);
}
$limit = trim($argv[1]);
$max = trim($argv[2]);
$total = trim($argv[3]);
if (isset($argv[4])) {
	$list = str_replace(' ', ',', trim($argv[4]));
	$list = explode(',', $list);
} else {
	$list = array();
}
$format = (!isset($argv[5]) ? 2 : $argv[5]);
$numbers = array();
$card = array();
$map = array();
$data = array();
if (strpos($limit, '-')) {
	$lenght = explode('-', $limit);
	$first = $lenght[0];
	$last = $lenght[1];
	$table = range($first, $last);
	if ($first>=$last) {
		echo "Please, check the range ($limit)!\n";
		exit(2);
	}
}
if (count($list)>0) {
	foreach($list as $k) {
		if (!empty($k)) {
			if ($k>$first && $k<$last) {
				$numbers[] = (int)$k;
			}
		}
	}
	if (count($numbers)>0) {
		for($i=0; $i<count($numbers); $i++) {
			if (!in_array($numbers[$i], $card)) {
				$card[] = $numbers[$i];
			}
		}
	}
}
$row = 0;
$repeat = 0;
while($row<$total) {
	prepare($card);
}
result($data);

echo "\n";
exit(0);

?>
