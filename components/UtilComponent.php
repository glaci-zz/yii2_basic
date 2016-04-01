<?php
namespace app\components;


use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class UtilComponent extends Component
{
	public function baseResponse($json_data, $status_code)
	{
		// return response
		$response = Yii::$app->response;
		$response->format = yii\web\Response::FORMAT_JSON;
		$response->setStatusCode($status_code);
		$response->data = $json_data;
		return $response;
	}

	public function successResponse($message)
	{
		// for common success response
		$success_data = ['status' => 200, 'message' => $message];
		return $this->baseResponse($success_data, 200);
	}

	public function errorResponse($message)
	{
		// for common error response
		$error_data = ['status' => 400, 'message' => $message];
		return $this->baseResponse($error_data, 400);
	}
}

?>