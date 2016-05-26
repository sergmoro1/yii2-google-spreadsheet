<?php
/**
* @author sergmoro1@ya.ru
* @license MIT
* 
* Make array from xml for arrayDataProvider.
* 
*/

namespace frontend\models;

use sergmoro1\googless\models\GoogleBook;

class Book extends GoogleBook
{
	/*
	 * Convert xml with list of user's spreadsheets to array.
	 * @param xml
	 * @return array
	 */
    public function getSpreadsheets($xml)
    {
		$a = [];
		if(isset($xml->entry)) {
			for($i=0; $i<count($xml->entry); $i++)
			{
				$b = [];
				$entry = $xml->entry[$i];
				$b['id'] = substr($entry->id, strrpos($entry->id, '/') + 1);
				$b['title'] = $entry->title;
				$b['updated_at'] = strtotime($entry->updated);
				$b['editable'] = substr($entry->link[0]['href'], -4) == 'full' ? 1 : 0;
				$b['author'] = $entry->author->name;
				$b['email'] = $entry->author->email;
				$a[$i] = $b;
			}
		}
		return $a;
	}
}
