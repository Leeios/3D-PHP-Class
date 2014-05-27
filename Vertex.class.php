<?php
require_once 'Color.class.php';
Class Vertex
{
	private $_color;
	private $_x = 0;
	private $_y = 0;
	private $_z = 0;
	private $_w = 1.0;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('color', $kwargs))
			$this->_color = $kwargs['color'];
		else
			$this->_color = new Color(array('red' => 255, 'green' => 255, 'blue' => 255));
		if (array_key_exists('w', $kwargs))
			$this->_w = $kwargs['w'];
		if (array_key_exists('x', $kwargs)
			&& array_key_exists('y', $kwargs)
			&& array_key_exists('z', $kwargs))
		{
			$this->_x = $kwargs['x'];
			$this->_y = $kwargs['y'];
			$this->_z = $kwargs['z'];
			if (self::$verbose)
				echo $this->__toString()." constructed.".PHP_EOL;
		}
	}

	public function		__destruct()
	{
		if (self::$verbose)
			echo $this->__toString()." destructed.".PHP_EOL;
	}

	public function		__toString()
	{
		if (self::$verbose)
			return "Vertex( x: ".round($this->_x, 2).", y: ".round($this->_y, 2).", z: ".round($this->_z, 2).", w: ".round($this->_w, 2).", ".$this->_color." )";
		else
			return "Vertex( x: ".round($this->_x, 2).", y: ".round($this->_y, 2).", z: ".round($this->_z, 2).", w: ".round($this->_w, 2)." )";
	}

	public static function	doc()
	{
		return file_get_contents("./Vertex.doc.txt");
	}

	public function		getX()
	{
		return $this->_x;
	}
	public function		getY()
	{
		return $this->_y;
	}
	public function		getZ()
	{
		return $this->_z;
	}
	public function		getW()
	{
		return $this->_w;
	}
	public function		getColor()
	{
		return $this->_color;
	}

	public function		setX($value)
	{
		$this->_x = $value;
	}
	public function		setY($value)
	{
		$this->_y = $value;
	}
	public function		setZ($value)
	{
		$this->_z = $value;
	}
	public function		setW($value)
	{
		$this->_w = $value;
	}
	public function		setColor($value)
	{
		$this->_color = $value;
	}
}
?>
