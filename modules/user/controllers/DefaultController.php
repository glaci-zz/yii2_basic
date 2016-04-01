<?php

namespace app\modules\user\controllers;

use Yii;
use yii\base\Behavior;
use yii\web\Response;
use yii\web\Controller;
use app\models\User;

class DefaultController extends Controller
{

    // //if using restful and activecontroller
    // public $modelClass = 'app\models\User';

    //if web need enable, can be disable if need debug, cant set in config/web.php
    public $enableCsrfValidation = false;


    //if want all return same format
    public function behaviors()
    {
       // $behaviors = parent::behaviors();
       // $behaviors['contentNegotiator']['formats']['text/plain'] = Response::FORMAT_RAW;
       // return $behaviors;

        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                //'only' => ['view', 'index'],  // in a controller
                // if in a module, use the following IDs for user actions
                // 'only' => ['user/view', 'user/index']
                'formats' => [
                    // 'application/json' => Response::FORMAT_JSON,
                    'text/plain' => Response::FORMAT_RAW,
                ],
                // 'languages' => [
                //     'en',
                //     'de',
                // ],
            ],
        ];

    }//eof behaviors


    public function actionIndex()
    {
        echo 'this is index';
    }


    public function actionLogin()
    {

        //parse json

        $json = file_get_contents('php://input');
        $json_ary = json_decode($json, true);
        $acc = $json_ary['account'];
        $pw = $json_ary['pw'];

print_r($json);


        // //standard response format
        // \Yii::$app->response->statusCode = 200;
        // \Yii::$app->response->headers->add('Content-type', 'application/json');
        // \Yii::$app->response->headers->set('Accept', 'application/json');
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // \Yii::$app->response->data = ['some', 'array', 'of', 'data' => ['associative', 'array']];
        // \Yii::$app->response->send();


        // // whether the current user is a guest (not authenticated)
        // $guest = Yii::$app->user->isGuest;
        // // not logged in
        // if($gest){
        //     $pw_check = User::validatePassword($pw);
        //     //password check
        //     if(!$pw_check){
        //         //return error if pw wrong
        //         Yii::$app->response->statusCode = 401;
        //         //data
        //         Yii::$app->response->data = ['error' => 'pw_wrong'];
        //     }
        //     // logs in the user
        //     Yii::$app->user->login($identity, 60*60*24);
        // }else{
        //     //logged in , return user info data in json
        //     Yii::$app->response->statusCode = 200;
        //     //data
        //     Yii::$app->response->data = ['account' => Yii::$app->user->identity->account];
        // }

    }//eof login


    public function actionLogout()
    {
        Yii::$app->user->logout();

    }
}
