<?php
	class Photo
	{
		private $x;
		private $y;
		private $filename;
		public function __construct($ff, $xx, $yy)
		{
			$this->filename = $ff;
			$this->x = $xx;
			$this->y = $yy;
		}

		public function get_x()
		{
			return $this->x;
		}
		public function set_x($xx)
		{
			$this->x = $xx;
		}
		public function get_y()
		{
			return $this->y;
		}
		public function get_filename()
		{
			return $this->filename;
		}
		public function multiply($coef)
		{
			$this->x = ceil($coef * $this->x);
			$this->y = ceil($coef * $this->y);
		}
		public function show()
		{
			$arr = explode("/", $this->filename);
			if ($arr)
			{
				$file = array_pop($arr);
				$dir = implode("/", $arr);
				$url = "images/phocagallery/$dir/thumbs/phoca_thumb_l_$file";
			}
			else
				$url = "images/phocagallery/thumbs/phoca_thumb_l_{$this->filename}";
			
			return "<div class=\"item\" style=\"background-image:url(".JRoute::_($url)."); width:{$this->x}px; height:{$this->y}px\"><a class=\"highslide \" href=\"".JRoute::_("images/phocagallery/{$this->filename}")."\" onclick=\"return hs.expand(this, {slideshowGroup: 'groupC',  wrapperClassName: 'rounded-white', outlineType: 'rounded-white', dimmingOpacity: 0,  align: 'center',  transitions: ['expand', 'crossfade'], fadeInOut: true});\"></a></div>";
		}
	}