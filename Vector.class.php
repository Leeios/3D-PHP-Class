<?php
require_once 'Vertex.class.php';
Class Vector
{
	private $_w = 0.0;
	private $_x = 0;
	private $_y = 0;
	private $_z = 0;
	private $_normalized = 0;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('norm', $kwargs))
			$this->_normalized = $kwargs['norm'];
		if (array_key_exists('orig', $kwargs))
			$orig = $kwargs['orig'];
		else
			$orig = new Vertex(array('x' => 0, 'y' => 0, 'z' => 0));
		if (array_key_exists('dest', $kwargs))
			$dest = $kwargs['dest'];
		$this->_setVectorFromVertex($orig, $dest);
		if (self::$verbose)
			echo $this->__toString()." constructed.".PHP_EOL;
	}

	public function		__destruct()
	{
		if (self::$verbose)
			echo $this->__toString()." destructed.".PHP_EOL;
	}

	public function		__toString()
	{
		if (self::$verbose)
			return "Vector( x: ".round($this->_x, 2).", y: ".round($this->_y, 2).", z: ".round($this->_z, 2).", w: ".round($this->_w, 2)." )";
		else
			return "Vector( x: ".round($this->_x, 2).", y: ".round($this->_y, 2).", z: ".round($this->_z, 2)." )";
	}

	public static function	doc()
	{
		return file_get_contents("./Vector.doc.txt");
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

	public function		magnitude()
	{
		return (sqrt($this->_x * $this->_x + $this->_y * $this->_y + $this->_z * $this->_z));
	}

	public function		normalize()
	{
		if ($this->_normalized)
			return ($this);
		return (new Vector(
			array('dest' => new Vertex(array(
				'x' => $this->_x / $this->magnitude(),
				'y' => $this->_y / $this->magnitude(),
				'z' => $this->_z / $this->magnitude())),
				'norm' => 1)));
	}

	public function		add(Vector $rhs)
	{
		return (new Vector(
			array('dest' => new Vertex(array(
				'x' => $this->_x + $rhs->getX(),
				'y' => $this->_y + $rhs->getY(),
				'z' => $this->_z + $rhs->getZ())))));
	}
	public function		sub(Vector $rhs)
	{
		return (new Vector(
			array('dest' => new Vertex(array(
				'x' => $this->_x - $rhs->getX(),
				'y' => $this->_y - $rhs->getY(),
				'z' => $this->_z - $rhs->getZ())))));
	}
	public function		opposite()
	{
		return (new Vector(
			array('dest' => new Vertex(array(
				'x' => -$this->_x,
				'y' => -$this->_y,
				'z' => -$this->_z)))));
	}

	public function		scalarProduct($k)
	{
		return (new Vector(
			array('dest' => new Vertex(array(
				'x' => $this->_x * $k,
				'y' => $this->_y * $k,
				'z' => $this->_z * $k)))));
	}
	public function		dotProduct($rhs)
	{
		return ($this->_x * $rhs->getX()
				+ $this->_y * $rhs->getY()
				+ $this->_z * $rhs->getZ());
	}
	public function		cos(Vector $rhs)
	{
		return ($this->dotProduct($rhs) / ($this->magnitude() * $rhs->magnitude()));
	}
	public function		crossProduct($rhs)
	{
		$u = new Vertex(array('x' => $this->_y * $rhs->getZ(),
			'y' => $this->_z * $rhs->getX(),
			'z' => $this->_x * $rhs->getY()));
		$v = new Vertex(array('x' => $this->_z * $rhs->getY(),
			'y' => $this->_x * $rhs->getZ(),
			'z' => $this->_y * $rhs->getX()));
		return (new Vector(array('dest' => $u, 'orig' => $v)));
	}

	private function	_setVectorFromVertex(Vertex $orig, Vertex $dest)
	{
		$this->_x = $dest->getX() / $dest->getW() - $orig->getX() / $orig->getW();
		$this->_y = $dest->getY() / $dest->getW() - $orig->getY() / $orig->getW();
		$this->_z = $dest->getZ() / $dest->getW() - $orig->getZ() / $orig->getW();
	}
	private function	__clone()
	{
		return (new Vector(
			array('dest' => new Vertex(array(
				'x' => $this->_x,
				'y' => $this->_y,
				'z' => $this->_z))
				,'norm' => $this->norm)));
	}
}
?>
