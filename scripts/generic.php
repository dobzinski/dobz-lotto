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
$select = array();
$row = array();
if (strpos($limit, '-')) {
	$lenght = explode('-', $limit);
	$first = $lenght[0];
	$last = $lenght[1];
	$table = range($first, $last);
}
if (count($list)>0) {
	foreach($list as $k) {
		if (!empty($k)) {
			$numbers[] = $k;
		}
	}
	if (count($numbers)>0) {
		for($i=$first; $i<=$last; $i++) {
			if (in_array($i, $numbers)) {
				$select[] = $i;
			}
		}
		if (count($select)>0) {
			$i = 0;
			while($i<$total) {
				shuffle($select);
				$n = 0;
				foreach($select as $k) {
					if ($n<$max) {
						$row[$i][] = $k;
					} else {
						break;
					}
					$n++;
				}
				$i++;
			}
		}
		if (count($row)>0) {
			foreach($row as $k) {
				$card = array();
				$n = count($k);
				if ($n<$max) {
					$card = $k;
					shuffle($table);
					foreach($table as $t) {
						if (!in_array($t, $k)) {
							if ($n<$max) {
								array_push($card, $t);
							}
							$n++;
						}
					}
				} else {
					$card = $k;
				}
				asort($card);
				$col = 0;
				foreach($card as $v) {
					echo str_pad($v, $format, '0', STR_PAD_LEFT);
					if (count($card)>($col+1)) {
						echo " - ";
					}
					$col++;
				}
				echo "\n";
			}
		}
	}
} else {
	$i = 0;
	while($i<$total) {
		shuffle($table);
		$n = 0;
		foreach($table as $t) {
			if ($n<$max) {
				$row[$i][] = $t;
			} else {
				break;
			}
			$n++;
		}
		$i++;
	}
	if (count($row)>0) {
		foreach($row as $k) {
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
}
exit(0);

?>
