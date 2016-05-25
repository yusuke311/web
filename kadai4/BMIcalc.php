<?php
class BMICalc
{
	private $height;
	private $weight;
	private $BMI;
	
	function __construct()
	{
		$this->BMI = 0;
	}
	public function Calc()
	{
		$this->BMI =  $this->weight / pow($this->height/100,2);
	}
	public function SetHeight($height)
	{
		$this->height = $height;
	}
	public function SetWeight($weight)
	{
		$this->weight = $weight;
	}
	public function GetBMI()
	{
		return $this->BMI;
	}
}
?>
