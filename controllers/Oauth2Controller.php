<?php
/**
* @author sergmoro1@ya.ru
* @license MIT
* 
* Google Sheets OAuth2 autorization.
* 
*/

namespace sergmoro1\googless\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use sergmoro1\googless\models\GoogleBook;

class Oauth2Controller extends Controller
{
	const SCOPE = 'https://spreadsheets.google.com/feeds/';
    const ACCOUNTS_OAUTH2 = 'https://accounts.google.com/o/oauth2/v2/auth';
    const OAUTH2_TOKEN = 'https://accounts.google.com/o/oauth2/token';

	/*
	 * First authorization stage - getting code by CLIENT_ID.
	 * The Scope defines future operations.
	 */
	protected function getAuthorizationCode()
	{
		$params = [
			'response_type' => 'code',
			'access_type' => 'offline', // the app needs to use Google API in the background
			'approval_prompt' => 'force',
			'client_id' => \Yii::$app->params['clientId'],
			'redirect_uri' => \Yii::$app->params['redirectUri'],
			'scope' => self::SCOPE,
		];

		$this->redirect(self::ACCOUNTS_OAUTH2 . '?' . http_build_query($params));
	}

	/*
	 * Second authorization stage - recieving token.
	 * @param code from prev stage.
	 */
	protected function getAccessToken($code)
	{
		$http_data = GoogleBook::curl([
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
				'code' => $code,
				'client_id' => \Yii::$app->params['clientId'],
				'client_secret' => \Yii::$app->params['clientSecret'],
				'redirect_uri' => \Yii::$app->params['redirectUri'],
				'grant_type' => 'authorization_code'
			],
			CURLOPT_URL => self::OAUTH2_TOKEN,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true
		]);

		$response = json_decode($http_data);
		
		if(isset($response->refresh_token)) {
			// Refresh tokens are for long term user and should be stored
			// They are granted first authorization for offline access
			file_put_contents("../runtime/GmailToken.txt", $response->refresh_token);
		}
		
		// The access token should be used first else invalid_grant error
		$session = Yii::$app->session;
		$session->set('access_token', $response->access_token);
		$session->close();
		
		$this->redirect(['google/oauth']);
	}

	/*
	 * This is RedirectURI action.
	 * @param code - Authorization Code
	 */
    public function actionOauth($code = null)
    {
		if($code) {
			// Exchange Authorization Code for OAuth Token
			$this->getAccessToken($code);
		} else {
			$session = Yii::$app->session;
			if($access_token = $session->get('access_token')) {
				$back_action = $session->get('back_action');
				$this->redirect([$back_action, 'start' => 1]);
			} else {
				$this->getAuthorizationCode();
			}
		}
    }
}
