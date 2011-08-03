<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Basic pagination style, based on example in Kostache readme
 * @see https://github.com/zombor/KOstache#readme
 *
 * @package    Kostache
 * @category   Pagination
 * @author     Korney Czukowski
 * @copyright  (c) 2011 Korney Czukowski
 * @license    MIT License
 */
class Pagination_Basic extends Kostache_Pagination_Base
{
	public $items = array();

	/**
	 * @return Kohana_Pagination_Basic
	 */
	public function render()
	{
		// First.
		$first['title'] = 'first';
		$first['name'] = __('first');
		$first['url'] = ($this->pagination->first_page !== FALSE) ? $this->pagination->url($this->pagination->first_page) : FALSE;
		$this->items[] = $first;

		// Prev.
		$prev['title'] = 'prev';
		$prev['name'] = __('previous');
		$prev['url'] = ($this->pagination->previous_page !== FALSE) ? $this->pagination->url($this->pagination->previous_page) : FALSE;
		$this->items[] = $prev;

		// Numbers.
		for ($i=1; $i<=$this->pagination->total_pages; $i++)
		{
			$item = array();

			$item['num'] = TRUE;
			$item['name'] = $i;
			$item['url'] = ($i != $this->pagination->current_page) ? $this->pagination->url($i) : FALSE;
			$item['current'] = ($item['url']==FALSE); // added this so we can <strong> the current page
			$this->items[] = $item;
		}
		// Next.
		$next['title'] = 'next';
		$next['name'] = __('next');
		$next['url'] = ($this->pagination->next_page !== FALSE) ? $this->pagination->url($this->pagination->next_page) : FALSE;
		$this->items[] = $next;

		// Last.
		$last['title'] = 'last';
		$last['name'] = __('last');
		$last['url'] = ($this->pagination->last_page !== FALSE) ? $this->pagination->url($this->pagination->last_page) : FALSE;
		$this->items[] = $last;

		return $this;
	}
}
