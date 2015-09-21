<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public static function shorten($text, $limit){
		$newText = substr(strip_tags($text), 0, $limit);
		$textL= strlen(strip_tags($text));
		$newTextL = strlen($newText);
		if($textL > $newTextL) {
			$newText = preg_replace('/\s+?(\S+)?$/', '', $newText);
			$newText .= '...';
		}
		return $newText;
	}

	public static function countTabbedContent($posts){
		$total = 0;
		foreach ($posts as $post) {
			$total += count($post);
		}
		return $total;
	}


}
