<?php
/**
* @author sergmoro1@ya.ru
* @license MIT
* 
* Getting data from a Google Spreadsheet by REST API.
* See: https://developers.google.com/google-apps/spreadsheets/
* 
*/

namespace sergmoro1\googless\models;

class GoogleBook
{
	public static function curl($options)

	{
        $ch = curl_init();
		curl_setopt_array($ch, $options);
		$http_data = curl_exec($ch);
		curl_close($ch);
		return $http_data;
	}

	/*
	 * Get data from a Google Spreadsheet.
	 * Data structure depends on REST call.
	 * See: https://console.developers.google.com/apis/library
	 * @param $url requesting data
	 * for ex. https://spreadsheets.google.com/feeds/spreadsheets/private/full
	 * requesting a feed containing a list of the currently authenticated user's spreadsheets.
	 * @param access_token
	 * @return xml depends on request
	 */
	public function getXml($url, $access_token)
	{
		$http_data = $this->curl([
			CURLOPT_POST => false,
			CURLOPT_URL => $url . '?access_token=' . $access_token,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
		]);

		if($xml = simplexml_load_string($http_data))
			return $xml;
		else
			return $http_data;
	}
}
