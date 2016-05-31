<?php
//バブルソート

	function bublesort($array)
	{
		$size = count($array);
		for( $i = 0; $i < $size; $i++  )
		{
			for( $j = $size -1 ; $j > $i; $j-- )
			{
				if( strcmp($array[$j] , $array[$j-1]) < 0 )
				{
					$tmp = $array[$j];
					$array[$j] = $array[$j-1];
					$array[$j-1] = $tmp;
				}
			}
		}
		return $array;
	}

	$array = array(
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
	);

	foreach( $array as $num )
	{
		printf("%s\n",$num);
	}
	echo "バブルソート\n";


	$array = bublesort($array);

	foreach( $array as $num )
	{
		printf("%s\n",$num);
	}


?>
