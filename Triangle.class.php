<?php
Class Triangle
{
	private	$_a;
	private	$_b;
	private	$_c;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('A', $kwargs) && array_key_exists('B', $kwargs) && array_key_exists('C', $kwargs))
		{
			$this->_a = $kwargs['A'];
			$this->_b = $kwargs['B'];
			$this->_c = $kwargs['C'];
		}
	}

	public function		__destruct()
	{
		if (self::$verbose)
			echo "Triangle instance destructed.".PHP_EOL;
	}

	public function		__toString()
	{
		return ("Triangle(\n".PHP_EOL);
	}

	public static function	doc()
	{
		return file_get_contents("./Triangle.doc.txt");
	}

	public function				getA()
	{
		return ($this->_a);
	}
	public function				getB()
	{
		return ($this->_b);
	}
	public function				getC()
	{
		return ($this->_c);
	}
}
?>
