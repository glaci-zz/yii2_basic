<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "match2.app_push".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $os_type
 * @property string $device_token
 * @property string $expire_date
 * @property string $create_user
 * @property string $create_date
 * @property string $update_user
 * @property string $update_date
 */
class AppPush extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_push';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'os_type'], 'required'],
            [['id'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['device_token'], 'string', 'max' => 200],
            [['os_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'UID',
            'os_type' => 'OS_Type',
            'device_token' => 'Device Token',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_date'],
                ],
                'value' => function() {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    public function getUsers($value='')
    {
        return $this->hasOne(Users::className(), ['uid' => 'uid']);
    }
}
