<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'users';
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        // $identity = users::findIdentity($uid) ? new static(users::findIdentity($uid)) : null;
        // return $identity;
        return static::findOne(['uid' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // foreach (self::$users as $user) {
        //     if ($user['accessToken'] === $token) {
        //         return new static($user);
        //     }
        // }
        // return null;
        throw new NotSupportedException();//I don't implement this method because I don't have any access token column in my database
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByAcc($acc)
    {
        return static::findOne(['account' => $acc]);
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['username'], $username) === 0) {
        //         return new static($user);
        //     }
        // }
        // return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->uid;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        // return $this->authKey;
        throw new NotSupportedException();//You should not implement this method if you don't have authKey column in your database
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
        throw new NotSupportedException();//You should not implement this method if you don't have authKey column in your database
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($pw)
    {
        return $this->pw === $pw;
    }
}
