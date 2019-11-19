<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property int $fkOrgID
 * @property int $fkPersonID
 * @property string $title
 * @property bool $isPrimary
 *
 * @property Organization $fkOrg
 * @property Person $fkPerson
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fkOrgID', 'fkPersonID'], 'required'],
            [['fkOrgID', 'fkPersonID'], 'integer'],
            [['isPrimary'], 'boolean'],
            [['title'], 'string', 'max' => 80],
            [['fkOrgID', 'fkPersonID'], 'unique', 'targetAttribute' => ['fkOrgID', 'fkPersonID']],
            [['fkOrgID'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['fkOrgID' => 'pkOrgID']],
            [['fkPersonID'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fkPersonID' => 'pkPersonID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fkOrgID' => 'Fk Org I D',
            'fkPersonID' => 'Fk Person I D',
            'title' => 'Title',
            'isPrimary' => 'Is Primary',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['pkOrgID' => 'fkOrgID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['pkPersonID' => 'fkPersonID']);
    }

    static public function findByKeys($fkPersonID, $fkOrgID)
    {
        return Contact::find()
                ->where("fkPersonID = $fkPersonID AND fkOrgID = $fkOrgID")
                ->one();
    }
}
