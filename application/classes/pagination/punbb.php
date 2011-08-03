<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Basic pagination style like in PunBB
 * @preview  Displayed 31-60 of 150
 * @preview  Pages: 1 … 4 5 6 7 8 … 15
 *
 * @package    Kostache
 * @category   Pagination
 * @author     Korney Czukowski
 * @copyright  (c) 2011 Korney Czukowski
 * @license    MIT License
 */
class Kohana_Pagination_PunBB extends Kostache_Pagination_Base
{
	public $items = array();

	/**
	 * @return string
	 */
	public function displayed()
	{
		return __('Displayed :start-:end of :total', array(
			':start' => $this->pagination->current_first_item,
			':end' => $this->pagination->current_last_item,
			':total' => $this->pagination->total_items,
		));
	}

	/**
	 * @return string
	 */
	public function pages()
	{
		return __('Pages');
	}

	/**
	 * @return Kohana_Pagination_Basic
	 */
	public function render()
	{
		// First
		if ($this->pagination->current_page > 3)
		{
			$this->items[] = array(
				'name' => '1',
				'url' => ($this->pagination->first_page !== FALSE) ? $this->pagination->url($this->pagination->first_page) : FALSE,
			);
			if ($this->pagination->current_page != 4)
			{
				$this->items[] = array('name' => '&hellip;');
			}
		}
		// Middle part
		for ($i = $this->pagination->current_page - 2, $stop = $this->pagination->current_page + 3; $i < $stop; ++$i)
		{
			if ($i < 1 OR $i > $this->pagination->total_pages)
			{
				continue;
			}
			$item = array();
			$item['name'] = $i;
			if ($this->pagination->current_page == $i)
			{
				$item['num'] = TRUE;
			}
			else
			{
				$item['url'] = $this->pagination->url($i);
			}
			$this->items[] = $item;
		}
		// Last
		if ($this->pagination->current_page <= $this->pagination->total_pages - 3)
		{
			if ($this->pagination->current_page != $this->pagination->total_pages - 3)
			{
				$this->items[] = array('name' => '&hellip;');
			}
			$this->items[] = array(
				'name' => $this->pagination->total_pages,
				'url' => $this->pagination->url($this->pagination->total_pages),
			);
		}

		return $this;
	}
}
