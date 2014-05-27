<?php
Class Color
{
	public $red = 0;
	public $green = 0;
	public $blue = 0;
	public static $verbose = false;

	public function		__construct(array $kwargs)
	{
		if (array_key_exists('rgb', $kwargs))
		{
			$this->red = (int)$kwargs['rgb'] & 0xFF;
			$this->green = (int)($kwargs['rgb'] >> 8) & 0xFF;
			$this->blue = (int)($kwargs['rgb'] >> 16) & 0xFF;
		}
		else
		{
			if (array_key_exists('red', $kwargs)
				&& array_key_exists('green', $kwargs)
				&& array_key_exists('blue', $kwargs))
			{
				$this->red = $kwargs['red'];
				$this->green = $kwargs['green'];
				$this->blue = $kwargs['blue'];
			}
		}
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
		return "Color( red: ".intval($this->red).", green: ".intval($this->green).", blue: ".intval($this->blue)." )";
	}

	public static function	doc()
	{
		return file_get_contents("./Color.doc.txt");
	}

	public function		add(Color $rhs)
	{
		return (new Color(array('red' => $this->red + $rhs->red,
		'green' => $this->green + $rhs->green,
		'blue' => $this->blue + $rhs->blue)));
	}

	public function		sub(Color $rhs)
	{
		return (new Color(array('red' =>$this->red - $rhs->red,
		'green' => $this->green - $rhs->green,
		'blue' => $this->blue - $rhs->blue)));
	}

	public function		mult($f)
	{
		return (new Color(array('red' =>$this->red * $f,
		'green' => $this->green * $f,
		'blue' => $this->blue * $f)));
	}

// color = getColorAlreadyAllocatedInPNGImage( img, r, g, b )
// IF color == -1
// IF numberOfColorsInPNGImage( img ) >= 255
// color = getPNGImageClosestColor( img, r, g, b )
// ELSE
// color = allocateNewColorInPNGImage( img, r, g, b )
// RETURN color


	public function		toPngColor($img)
	{
		$color = imagecolorexact($img ,$this->red ,$this->green , $this->blue);
		if ($color == -1)
		{
			if (imagecolorstotal($img) >= 255)
				$color = imagecolorclosest($img ,$this->red ,$this->green , $this->blue);
			else
				$color = imagecolorallocate($img ,$this->red ,$this->green , $this->blue);
		}
		return ($color);
	}
}
?>
