<?php
Class Matrix
{
	const IDENTITY = 1;
	const SCALE = 2;
	const RX = 3;
	const RY = 4;
	const RZ = 5;
	const TRANSLATION = 6;
	const PROJECTION = 7;

	private $_tab;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('preset', $kwargs))
		{
			$this->_tab = array();
			if ($kwargs['preset'] == self::IDENTITY)
				$this->_construct_scale(1);
			else if ($kwargs['preset'] == self::SCALE && array_key_exists( 'scale', $kwargs))
				$this->_construct_scale($kwargs['scale']);
			else if ($kwargs['preset'] == self::RX && array_key_exists('angle', $kwargs))
				$this->_construct_rx($kwargs['angle']);
			else if ($kwargs['preset'] == self::RY && array_key_exists('angle', $kwargs))
				$this->_construct_ry($kwargs['angle']);
			else if ($kwargs['preset'] == self::RZ && array_key_exists('angle', $kwargs))
				$this->_construct_rz($kwargs['angle']);
			else if ($kwargs['preset'] == self::TRANSLATION && array_key_exists('vtc', $kwargs))
				$this->_construct_translation($kwargs['vtc']);
			else if ($kwargs['preset'] == self::PROJECTION
				&& array_key_exists('fov', $kwargs)
				&& array_key_exists('ratio', $kwargs)
				&& array_key_exists('near', $kwargs)
				&& array_key_exists('far', $kwargs))
				$this->_construct_projection($kwargs['fov'], $kwargs['ratio'], $kwargs['near'], $kwargs['far']);
		}
		else if (array_key_exists('val_matrix', $kwargs))
			$this->_setTab($kwargs['val_matrix']);
}

	public function		__destruct()
	{
		if (self::$verbose)
			echo "Matrix instance destructed.".PHP_EOL;
	}

	public function		__toString()
	{
		return ("\nM\t|vtcX\t|vtcY\t|vtcZ\t|vtxO\n"."--------------------------------------"
		."\nx\t|".round($this->_tab[0][0], 2)."\t|".round($this->_tab[0][1], 2)."\t|".round($this->_tab[0][2], 2)."\t|".round($this->_tab[0][3], 2)
		."\ny\t|".round($this->_tab[1][0], 2)."\t|".round($this->_tab[1][1], 2)."\t|".round($this->_tab[1][2], 2)."\t|".round($this->_tab[1][3], 2)
		."\nz\t|".round($this->_tab[2][0], 2)."\t|".round($this->_tab[2][1], 2)."\t|".round($this->_tab[2][2], 2)."\t|".round($this->_tab[2][3], 2)
		."\nw\t|".round($this->_tab[3][0], 2)."\t|".round($this->_tab[3][1], 2)."\t|".round($this->_tab[3][2], 2)."\t|".round($this->_tab[3][3], 2)."\n");
	}

	public static function	doc()
	{
		return file_get_contents("./Matrix.doc.txt");
	}

	public function			getTab()
	{
		return ($this->_tab);
	}

	public function transformVertex(Vertex $vtx)
	{
		$tmp_array = array();
		$tmp_array[0][0] = $vtx->getX();
		$tmp_array[1][0] = $vtx->getY();
		$tmp_array[2][0] = $vtx->getZ();
		$tmp_array[3][0] = $vtx->getW();
		return (new Vertex(array(
			'x' => $this->_mult_line_row($tmp_array, 0, 0),
			'y' => $this->_mult_line_row($tmp_array, 1, 0),
			'z' => $this->_mult_line_row($tmp_array, 2, 0),
			'color' => $vtx->getColor())));
	}
	public function transformMesh(array $mesh)
	{
		$result = array();
		foreach ($mesh as $key => $value)
		{
			$result[$key] = new Triangle(array(
				'A' => $this->transformVertex($value->getA()),
				'B' => $this->transformVertex($value->getB()),
				'C' => $this->transformVertex($value->getC())));
		}
		return ($result);
	}

	public function			transpose()
	{
		$result = array();
		foreach ($this->_tab as $i => $value)
		{
			foreach ($value as $j => $n)
				$result[$j][$i] = $n;
		}
		return (new Matrix(array('val_matrix' => $result)));
	}

	public function			mult(Matrix $rhs)
	{
		return (new Matrix(array('val_matrix' => array(
			array($this->_mult_line_row($rhs->getTab(), 0, 0)
				, $this->_mult_line_row($rhs->getTab(), 0, 1)
				, $this->_mult_line_row($rhs->getTab(), 0, 2)
				, $this->_mult_line_row($rhs->getTab(), 0, 3)),
			array($this->_mult_line_row($rhs->getTab(), 1, 0)
				, $this->_mult_line_row($rhs->getTab(), 1, 1)
				, $this->_mult_line_row($rhs->getTab(), 1, 2)
				, $this->_mult_line_row($rhs->getTab(), 1, 3)),
			array($this->_mult_line_row($rhs->getTab(), 2, 0)
				, $this->_mult_line_row($rhs->getTab(), 2, 1)
				, $this->_mult_line_row($rhs->getTab(), 2, 2)
				, $this->_mult_line_row($rhs->getTab(), 2, 3)),
			array($this->_mult_line_row($rhs->getTab(), 3, 0)
				, $this->_mult_line_row($rhs->getTab(), 3, 1)
				, $this->_mult_line_row($rhs->getTab(), 3, 2)
				, $this->_mult_line_row($rhs->getTab(), 3, 3))))));
	}
	private function		_mult_line_row(array $rhsM, $l, $r)
	{
		return ($this->_tab[$l][0] * $rhsM[0][$r]
				+ $this->_tab[$l][1] * $rhsM[1][$r]
				+ $this->_tab[$l][2] * $rhsM[2][$r]
				+ $this->_tab[$l][3] * $rhsM[3][$r]);
	}

	public function			_construct_projection($fov, $ratio, $near, $far)
	{
		$f = 1 / tan($fov * 0.5 * M_PI / 180);
		$this->_tab[0] = array($f / $ratio, 0, 0, 0);
		$this->_tab[1] = array(0, $f, 0, 0);
		$this->_tab[2] = array(0, 0, ($far + $near) / ($near - $far), 2 * $far * $near / ($far - $near));
		$this->_tab[3] = array(0, 0, -1, 0);
		if (self::$verbose)
			echo "Matrix PROJECTION preset instance constructed.".PHP_EOL;
	}

	private function		_construct_scale($scale)
	{
		$this->_tab[0] = array($scale, 0, 0, 0);
		$this->_tab[1] = array(0, $scale, 0, 0);
		$this->_tab[2] = array(0, 0, $scale, 0);
		$this->_tab[3] = array(0, 0, 0, 1);
		if (self::$verbose)
		{
			if ($scale != 1)
				echo "Matrix SCALE preset instance constructed.".PHP_EOL;
			else
				echo "Matrix IDENTITY preset instance constructed.".PHP_EOL;
		}
	}
	private function		_construct_rx($angle)
	{
		$this->_tab[0] = array(1, 0, 0, 0);
		$this->_tab[1] = array(0, cos($angle), -sin($angle), 0);
		$this->_tab[2] = array(0, sin($angle), cos($angle), 0);
		$this->_tab[3] = array(0, 0, 0, 1);
		if (self::$verbose)
		echo "Matrix Ox Rotation preset instance constructed.".PHP_EOL;
	}
	private function		_construct_ry($angle)
	{
		$this->_tab[0] = array(cos($angle), 0, sin($angle), 0);
		$this->_tab[1] = array(0, 1, 0, 0);
		$this->_tab[2] = array(-sin($angle), 0, cos($angle), 0);
		$this->_tab[3] = array(0, 0, 0, 1);
		if (self::$verbose)
			echo "Matrix Oy Rotation preset instance constructed.".PHP_EOL;
	}
	private function		_construct_rz($angle)
	{
		$this->_tab[0] = array(cos($angle), -sin($angle), 0, 0);
		$this->_tab[1] = array(sin($angle), cos($angle), 0, 0);
		$this->_tab[2] = array(0, 0, 1, 0);
		$this->_tab[3] = array(0, 0, 0, 1);
		if (self::$verbose)
			echo "Matrix Oz Rotation preset instance constructed.".PHP_EOL;
	}
	private function		_construct_translation(Vector $vtc)
	{
		$this->_tab[0] = array(1, 0, 0, $vtc->getX());
		$this->_tab[1] = array(0, 1, 0, $vtc->getY());
		$this->_tab[2] = array(0, 0, 1, $vtc->getZ());
		$this->_tab[3] = array(0, 0, 0, 1);
		if (self::$verbose)
			echo "Matrix TRANSLATION preset instance constructed.".PHP_EOL;
	}
	private function		_setTab(array $tab)
	{
		$this->_tab = $tab;
	}
}
?>
