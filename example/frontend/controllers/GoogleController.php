<?php
/**
* @author sergmoro1@ya.ru
* @license MIT
* 
* Getting a list of user's (registered in a Google) spreadsheets.
* 
*/

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use sergmoro1\googless\controllers\Oauth2Controller;
use yii\data\ArrayDataProvider;
use frontend\models\Book;
use yii\web\NotFoundHttpException;

class GoogleController extends Oauth2Controller
{
	/*
	 * List of your spreadsheets.
	 * @param start if false, save way back - action that should be redirect after authorization.
	 * if true - authorizaion is complete. 
	 */
    public function actionIndex($start = false)
    {
		$session = Yii::$app->session;
		
		// first time save way back
		if(!$start) {
			$session->set('back_action', 'google/index');
			$session->close();
			$this->redirect(['google/oauth']);
		}
		
		$access_token = $session->get('access_token');
		
		$model = new Book();
		$xml = $model->getXml(self::SCOPE . 'spreadsheets/private/full', $access_token);
		
		$dataProvider = new ArrayDataProvider([
			'allModels' => $model->getSpreadsheets($xml),
			'pagination' => [
				'pageSize' => 5,
			],
			'sort' => [
				'attributes' => ['updated_at'],
			],
		]);
		
		if(isset($xml->title))
		{
			list($title, $email) = explode('-', $xml->title);

			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'title' => $title,
				'owner' => $email,
			]);
		} else
			throw new NotFoundHttpException('The requested model does not exist.');
    }
    
	/*
	 * View selected spreadsheet in iframe.
	 * @param id spreadsheet's id
	 */
    public function actionView($id)
    {
		return $this->render('view', ['id' => $id]);
	}
}
