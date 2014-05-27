<?php
Class Camera
{
	private	$_origin;
	private	$_ratio;
	private	$_tT;
	private	$_tR;
	private	$_view_matrix;
	private	$_proj_matrix;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('origin', $kwargs) && array_key_exists('orientation', $kwargs))
			$this->_construct_view_matrix($kwargs['origin'], $kwargs['orientation']);
		if (array_key_exists('fov', $kwargs) && array_key_exists('near', $kwargs)
			&& array_key_exists('far', $kwargs))
		{
			if (array_key_exists('width', $kwargs) && array_key_exists('height', $kwargs))
				$kwargs['ratio'] = $kwargs['width'] / $kwargs['height'];
			$this->_ratio = $kwargs['ratio'];
			$kwargs['preset'] = Matrix::PROJECTION;
			$this->_proj_matrix = new Matrix($kwargs);
		}
	}

	public function		__destruct()
	{
		if (self::$verbose)
			echo "Camera instance destructed.".PHP_EOL;
	}

	public function		__toString()
	{
		return ("Camera(\n+ Origine : ".$this->_origin."\n+ tT:".$this->_tT."+ tR:".$this->_tR."+ tR->mult( tT ):".$this->_view_matrix."+ Proj:".$this->_proj_matrix.")".PHP_EOL);
	}

	public static function	doc()
	{
		return file_get_contents("./Camera.doc.txt");
	}

	public function			watchVertex(Vertex $worldVertex)
	{
		$tmp = $this->_proj_matrix->transformVertex($this->_tR->transformVertex($worldVertex));
		return (new Vertex(array(
			'x' => $tmp->getX() * $this->_ratio,
			'y' => $tmp->getY(),
			'z' => $tmp->getZ(),
			'color' => $tmp->getColor())));
	}
	public function			watchMesh(array $worldMesh)
	{
		$result = array();
		foreach ($worldMesh as $key => $value)
		{
			$result[$key] = new Triangle(array(
				'A' => $this->watchVertex($value->getA()),
				'B' => $this->watchVertex($value->getB()),
				'C' => $this->watchVertex($value->getC())));
		}
		return ($result);
	}

	private function		_construct_view_matrix(Vertex $origin, Matrix $orientation)
	{
		$this->_origin = $origin;
		$this->_tT = new Matrix(array(
			'preset' => Matrix::TRANSLATION, 'vtc' => (new Vector(array(
				'dest' => $origin)))->opposite()));
		$this->_tR = $orientation->transpose();
		print($this->_tT);
		print($this->_tR);
		$this->_view_matrix = $this->_tR->mult($this->_tT);
		if (self::$verbose)
			echo "Camera instance constructed.".PHP_EOL;
	}
}
?>
