<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
require_once('photo.php');
require_once('row.php');

class PlgContentPhocagrid extends JPlugin
{
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$matches = array();
		$regex = "|\{phocagrid categoryid=([0-9]*)\}|";
		if (false == preg_match($regex, $row->text, $matches))
			return;
		$output = $this->get_output($matches[1]);
		$row->text = preg_replace($regex, $output, $row->text, 1);
		$document = JFactory::getDocument();
		$document->addStyleSheet("plugins/content/phocagrid/styles.css");
		$document->addStyleSheet("highslide/highslide.css");
		$document->addScript("highslide/highslide-full.js");
		$document->addScript("highslide/mobile.js");
	}
	private function get_output($catid)
	{
		$plugin = JPluginHelper::getPlugin('content', 'phocagrid');
		if ($plugin)
		{
			$params = new JRegistry($plugin->params);
			$W = $params->get('width', 500);
			$Ncols = $params->get('ncols', 4);//2, 3, 4
		}
		else
		{
			$W = 500;
			$Ncols = 4;
		}
		$spacing = 1;
		$maxNcols = 4;
		
		$db = JFactory::getDBO();
		$photos = array();
		$query = 'SELECT id, filename'
							.' FROM #__phocagallery'
							.' WHERE catid=' . $catid
							.' AND published = 1'
							.' AND approved = 1'
							.' ORDER BY ordering';
		$db->setQuery($query);
		$result = $db->loadObjectList();
		foreach ($result as $r)
		{
			$res = getimagesize("images/phocagallery/".$r->filename);
			$photos[] = new Photo($r->filename, $res[0], $res[1]);
		}
		$table = array();
		$newRow = new Row($W, $spacing);
		foreach ($photos as $p)
		{
			$newRow->add($p);
			if ($newRow->cnt() == $Ncols)
			{
				$newRow->align();
				$table[] = $newRow;
				$newRow = new Row($W, $spacing);
			}
		}
		$cnt = $newRow->cnt();
		switch ($cnt)
		{
			case 0: break; // ok
			case 1:
				if (count($table) < 1)
				{
					$newRow->align();
					$table[] = $newRow;
					break;
				}
				$table[count($table) - 1]->add($newRow->get_item(0));
				if ($table[count($table) - 1]->cnt() > $maxNcols)
				{
					$pos = floor($Ncols / 2);
					$start = $table[count($table) - 1]->slice(0, $pos);
					$end = $table[count($table) - 1]->slice($pos, $table[count($table) - 1]->cnt() - $pos);
				
					$start->align();
					$end->align();
					array_pop($table);
					$table[] = $start;
					$table[] = $end;
				}
				else
					$table[count($table) - 1]->align();
				break;
			case 2: case 3: case 4: $newRow->align(); $table[] = $newRow; break;
			default: echo "Incorrect columns number!"; break;
		}
		$output = "<div class=\"container\">";
		foreach ($table as $r)
			$output .= $r->show();
		$output .= "</div>";
		return $output;
	}
}
?>