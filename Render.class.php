<?php
Class Render
{
	const VERTEX = 1;
	const EDGE = 2;
	const RASTERIZE = 3;
	private $_img;
	private $_width;
	private $_height;
	private $_filename;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('width', $kwargs) && array_key_exists('height', $kwargs) && array_key_exists('filename', $kwargs))
		{
			$this->_img = imagecreatetruecolor($kwargs['width'], $kwargs['height']);
			$this->_filename = $kwargs['filename'];
			$this->_width = $kwargs['width'];
			$this->_height = $kwargs['height'];
		}
	}

	public function		__destruct()
	{
		if (self::$verbose)
			echo "Render instance destructed.".PHP_EOL;
	}

	public function		__toString()
	{
		return ("Render(\n".PHP_EOL);
	}

	public function		develop()
	{
		if (!imagepng($this->_img, $this->_filename))
			echo "Error while saving png\n".PHP_EOL;
	}

	public static function	doc()
	{
		return file_get_contents("./Render.doc.txt");
	}

	public function			renderMesh($mesh, $mode)
	{
		if ($mode == self::VERTEX)
			$this->_render_vertex($mesh);
		else if ($mode == self::EDGE || 1)
			$this->_render_edge($mesh);
		else if ($mode == self::RASTERIZE)
			$this->_render_rasterize($mesh);
	}
	public function			renderVertex($vtx)
	{
		imagesetpixel($this->_img, $vtx->getX() + $this->_width / 2, -$vtx->getY() + $this->_height / 2, $vtx->getColor()->toPngColor($this->_img));
	}

	private function		_render_vertex($mesh)
	{
		foreach ($mesh as $value)
		{
			$this->renderVertex($value->getA());
			$this->renderVertex($value->getB());
			$this->renderVertex($value->getC());
		}
	}
	private function		_render_edge($mesh)
	{
		$style = array();
		foreach ($mesh as $value)
		{
			$style = range($value->getA()->getColor()->toPngColor($this->_img)
				, $value->getB()->getColor()->toPngColor($this->_img)
				, 0x000F1);
			imagesetstyle($this->_img, $style);
			imageline($this->_img, -$value->getA()->getX() + $this->_width / 2, -$value->getA()->getY() + $this->_height / 2
				, -$value->getB()->getX() + $this->_width / 2, -$value->getB()->getY() + $this->_height / 2
				, IMG_COLOR_STYLED);
			imagesetstyle($this->_img, $style);
			imageline($this->_img, -$value->getA()->getX() + $this->_width / 2, -$value->getA()->getY() + $this->_height / 2
				, -$value->getC()->getX() + $this->_width / 2, -$value->getC()->getY() + $this->_height / 2
				, IMG_COLOR_STYLED);
			imagesetstyle($this->_img, $style);
			imageline($this->_img, -$value->getC()->getX() + $this->_width / 2, -$value->getC()->getY() + $this->_height / 2
				, -$value->getB()->getX() + $this->_width / 2, -$value->getB()->getY() + $this->_height / 2
				, IMG_COLOR_STYLED);
		}
	}
	private function		_render_rasterize($mesh)
	{
		imagesetpixel($this->_img, 50, 50, (new Color(array('rgb' => 0x40562F)))->toPngColor($this->_img));
		imagesetpixel($this->_img, 100, 100, (new Color(array('rgb' => 0x40E62F)))->toPngColor($this->_img));
		echo "WRONG".PHP_EOL;
	}
}
?>
