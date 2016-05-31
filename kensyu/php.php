<?php

	printf("%s\n","Hello World");

	//変数
	$num = 10;
	$num = "Hello";
	$num1 = 5;
	$num2 = "5asfewg";
	$num2(2);



	printf("%s\n",$num);

	if( $num1 === $num2 )
	{
		print "等しい\n";
	}
	else
	{
		print "等しくない\n";
	}

	$array_list =
	[
		1,2,3,4,5
	];

	$array_list = array(1,2,3,4,5);

	$array_list = array
		(
			"AA" => "A",
			"BB" => "B",
			"CC" => "C"
		);
	printf("%s\n",$array_list["AA"]);
?>
