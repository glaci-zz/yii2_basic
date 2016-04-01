<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "match2.users".
 *
 * @property integer $uid
 * @property integer $puid
 * @property string $account
 * @property string $nick
 * @property string $pw
 * @property string $email
 * @property string $status
 * @property string $post_code
 * @property string $user_ip
 * @property string $user_port
 * @property string $server_ip
 * @property string $create_time
 * @property string $expire_time
 * @property string $login_time
 */
class users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['puid'], 'integer'],
            [['account', 'pw', 'email', 'user_ip', 'user_port', 'server_ip', 'create_time'], 'required'],
            [['status'], 'string'],
            [['create_time', 'expire_time', 'login_time'], 'safe'],
            [['account', 'nick', 'user_ip', 'server_ip'], 'string', 'max' => 16],
            [['pw'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 128],
            [['post_code'], 'string', 'max' => 10],
            [['user_port'], 'string', 'max' => 5],
            [['account'], 'unique'],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'puid' => 'Puid',
            'account' => 'Account',
            'nick' => 'Nick',
            'pw' => 'Pw',
            'email' => 'Email',
            'status' => 'Status',
            'post_code' => 'Post Code',
            'user_ip' => 'User Ip',
            'user_port' => 'User Port',
            'server_ip' => 'Server Ip',
            'create_time' => 'Create Time',
            'expire_time' => 'Expire Time',
            'login_time' => 'Login Time',
        ];
    }

    /**
     * @inheritdoc
     * @return usersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new usersQuery(get_called_class());
    }

    public function getApp_push()
    {
        return $this->hasMany(AppPush::className(), ['uid' => 'uid']);
    }

}
