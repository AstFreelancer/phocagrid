<?php
	class Row
	{
		private $lst;
		private $W, $spacing;
		
		public function __construct($WW, $sspacing)
		{
			$this->lst = array();
			$this->W = $WW;
			$this->spacing = $sspacing;
		}
		public function add($ph)
		{
			$this->lst[] = new Photo($ph->get_filename(), $ph->get_x(), $ph->get_y());
		}
		public function cnt()
		{
			return count($this->lst);
		}
		public function get_sum_x()
		{
			$sum = 0;
			foreach ($this->lst as $photo)
				$sum += $photo->get_x();
			return $sum;
		}
		public function get_item($i)
		{
			return $this->lst[$i];
		}
		public function align()
		{
			switch ($this->cnt())
			{
				case 1: return $this->align1();
				case 2: return $this->align2();
				case 3: return $this->align3();
				case 4: return $this->align4();
				default: echo "Incorrect columns number!"; break;
			}
		}
		public function slice($offset, $length)
		{
			$tmp = new Row();
			$tmp->lst = array_slice($this->lst, $offset, $length);
			return $tmp;
		}
		public function align1()
		{
			$x = $this->lst[0]->get_x();
			$a = $this->W / $x;
			$this->lst[0]->multiply($a);
		}
		public function align2()//align a row, consisting of 2 images
		{
			$x1 = $this->lst[0]->get_x();
			$x2 = $this->lst[1]->get_x();
			$y1 = $this->lst[0]->get_y();
			$y2 = $this->lst[1]->get_y();
			$a2 = $this->W * $y1 / ($y2 * $x1 + $y1 * $x2);
			$a1 = $a2 * $y2 / $y1;
			$this->lst[0]->multiply($a1);
			$this->lst[1]->multiply($a2);

			$delta = $this->W - ($this->lst[0]->get_x() + $this->lst[1]->get_x() + $this->spacing);
			$this->lst[0]->set_x($this->lst[0]->get_x() + $delta);
		}
		public function align3()//align a row, consisting of 3 images
		{
			$x1 = $this->lst[0]->get_x();
			$x2 = $this->lst[1]->get_x();
			$x3 = $this->lst[2]->get_x();
			
			$y1 = $this->lst[0]->get_y();
			$y2 = $this->lst[1]->get_y();
			$y3 = $this->lst[2]->get_y();
			
			$a2 = $this->W * $y1 * $y3 / ($x1 * $y2 * $y3 + $x2 * $y1 * $y3 + $x3 * $y1 * $y2);
			$a1 = $a2 * $y2 / $y1;
			$a3 = $a2 * $y2 / $y3;
			
			$this->lst[0]->multiply($a1);
			$this->lst[1]->multiply($a2);
			$this->lst[2]->multiply($a3);
			
			$delta = $this->W - ($this->lst[0]->get_x() + $this->lst[1]->get_x() + $this->lst[2]->get_x() + 2 * $this->spacing);//between 3 items there are 2 spaces
			$this->lst[0]->set_x($this->lst[0]->get_x() + $delta);
		}
		public function align4()//align a row, consisting of 4 images
		{
			$x1 = $this->lst[0]->get_x();
			$x2 = $this->lst[1]->get_x();
			$x3 = $this->lst[2]->get_x();
			$x4 = $this->lst[3]->get_x();
			
			$y1 = $this->lst[0]->get_y();
			$y2 = $this->lst[1]->get_y();
			$y3 = $this->lst[2]->get_y();
			$y4 = $this->lst[3]->get_y();
			
			$a1 = $this->W * $y2 * $y3 * $y4 / ($x1 * $y2 * $y3 * $y4 + $x2 * $y1 * $y3 * $y4 + $x3 * $y1 * $y2 * $y4 + $x4 * $y1 * $y2 * $y3);
			$a2 = $a1 * $y1 / $y2;
			$a3 = $a1 * $y1 / $y3;
			$a4 = $a1 * $y1 / $y4;
			
			$this->lst[0]->multiply($a1);
			$this->lst[1]->multiply($a2);
			$this->lst[2]->multiply($a3);
			$this->lst[3]->multiply($a4);
			
			$delta = $this->W - ($this->lst[0]->get_x() + $this->lst[1]->get_x() + $this->lst[2]->get_x() + $this->lst[3]->get_x() + 3 * $this->spacing);//there are 3 spaces between 4 items
			$this->lst[0]->set_x($this->lst[0]->get_x() + $delta);
		}

		public function show()
		{
			$temp = $this->cnt();
			$output = "<div class=\"row\">";
			foreach ($this->lst as $photo)
				$output .= $photo->show();
			$output .= "</div>";
			return $output;
		}
	}
?>