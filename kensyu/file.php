<?php

function myreadfile($FilePath)
{
	$fp = fopen($FilePath,"r");
	while(!feof($fp))
	{
		$line = fgets($fp);
		print $line;
	}
	fclose($fp);	
}

print "ファイルパスを入力してください\n";
$stdin = trim(fgets(STDIN));  
myreadfile($stdin);

?>
