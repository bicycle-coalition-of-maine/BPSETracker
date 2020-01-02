<?php

namespace app\models;

use Yii;
		
/**
* This model class is not actually based on a table.
* It extends Person to implement the IdentityInterface.
*
*   $id = Person.pkPersonID
*   $username is Person.email
*   $password is Person.password
*/

class User extends \app\models\Person implements \yii\web\IdentityInterface
{
    public $username;
    public $authKey;
    public $accessToken;
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $identity = self::findOne($id);
        $identity->username = $identity->email;
        return $identity;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $identity = self::findOne(['email' => $username, 'isAdmin' => true]);
        if($identity) $identity->username = $identity->email;
        return $identity;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->pkPersonID;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //$hash = '$2y$13$wR50kbDPGeagau1wXPO45uDApsjjUxiKEIXqIp6ZfxsLPpkpoqXxS';
        //$hash = $this->password;
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
        //return Yii::$app->getSecurity()->validatePassword($password, '$2y$13$wR50kbDPGeagau1wXPO45uDApsjjUxiKEIXqIp6ZfxsLPpkpoqXxS');
        //return $this->password === $password;
    }
}
