<?php

namespace app\modules\app\controllers;

use Yii;
use app\models\AppPush;
use app\models\users;

class PushController extends \yii\rest\ActiveController
{

	public $modelClass = 'app\models\AppPush';

	public function actions() {
		$actions = parent::actions();
		unset($actions['create']);
		return $actions;
	}

	public function actionCreate() {
		$post = Yii::$app->request->getBodyParams();
		$device_token_duplicate = false;
		$device_token = !empty($post['device_token']) ? $post['device_token'] : '';
		if (empty($device_token)) {
			return Yii::$app->util->errorResponse('device_token cannot not be empty');
		}
		$device_token_old = !empty($post['device_token_old']) ? $post['device_token_old'] : '';
		$os_type = !empty($post['os_type']) ? $post['os_type'] : '';
		if (empty($os_type)) {
			return Yii::$app->util->errorResponse('os_type cannot not be empty');
		}
		// find user by account
		$user = Users::find()->where(['account' => $post['account']])->with('app_push')->one();
		if (empty($user)) {
			return Yii::$app->util->errorResponse('Not found user by account: '.$post['account']);
		}

		// check device_token is exist then dont save
		foreach ($user->app_push as $app_push) {
			if ($device_token === $app_push->device_token) {
				$device_token_duplicate = true;
			}
		}

		if (!$device_token_duplicate) {
			if (empty($device_token_old)) {
				// 紀錄新的device_token
				$app_push = new AppPush();
				$app_push->load($post, '');
				$app_push->uid = $user->uid;
				$app_push->save();
			} else {
				// device_token有變更，將舊的覆蓋為新的
				$app_push = AppPush::find()->where(['device_token' => $device_token_old])->one();
				if (empty($app_push)) {
					return Yii::$app->util->errorResponse('Not found app_push by device_token_old: '.$device_token_old);
				}
				$app_push->device_token = $device_token;
				$app_push->save();
			}
		}

		return Yii::$app->util->successResponse('registered success');
	}

	public function actionPush() {
		$post = Yii::$app->request->getBodyParams();
		// 有帶device_token就直接使用
		$device_token = !empty($post['device_token']) ? $post['device_token'] : '';
		$message = !empty($post['message']) ? $post['message'] : '';
		$os_type = !empty($post['os_type']) ? $post['os_type'] : '';
		$push_array = ['android' => [], 'ios' => []];

		if (empty($device_token)) {
			// find user by account
			$user = Users::find()->where(['account' => $post['account']])->with('app_push')->one();
			if (empty($user)) {
				return Yii::$app->util->errorResponse('Not found user by account: '.$post['account']);
			}

			// 找出此user所有的device_token以及對應os
			foreach ($user->app_push as $app_push) {
				if ($app_push->os_type === 'android') {
					array_push($push_array['android'], $app_push->device_token);
				} elseif ($app_push->os_type === 'ios') {
					array_push($push_array['ios'], $app_push->device_token);
				}
			}

			// do push
			if (!empty($push_array['android'])) {
				$this->pushByGcm($push_array['android'], $message);
			}
			if (!empty($push_array['ios'])) {
				$this->pushByApns($push_array['ios'], $message);
			}

		} else {
			// 依帶上來的device_token及os直接push
			if ($os_type === 'android') {
				$this->pushByGcm($device_token, $message);
			} elseif ($os_type === 'ios') {
				$this->pushByApns($device_token, $message);
			}
		}

		return Yii::$app->util->successResponse('push finish');
	}

	protected function pushByGcm($device_token, $message) {
		// push notification by gcm
		$gcm = Yii::$app->gcm;
		$gcm->sendMulti($device_token, $message);
		if (!$gcm->success) {
			Yii::warning(json_encode($gcm), __METHOD__);
		}
	}

	protected function pushByApns($device_token, $message) {
		// push notification by apns
		$apns = Yii::$app->apns;
		$apns->sendMulti($device_token, $message);
		if (!$apns->success) {
			Yii::warning(json_encode($apns), __METHOD__);
		}
	}

}

?>