<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EmailTest extends Model {
    
    public $to, $msg;
    
    public function attributeLabels() {
        return ['to' => 'To', 'msg' => 'Optional Message'];
    }
    
    public function rules() {
        return [
            ['to', 'email'],
            ['to', 'required'],
            ['msg', 'string'],
        ];
    }
}
