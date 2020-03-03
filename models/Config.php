<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property string $pkOptionName
 * @property string $label
 * @property int $intValue
 * @property string $strValue
 * @property bool $exposeToUI
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['intValue'], 'integer'],
            [['exposeToUI'], 'boolean'],
            [['pkOptionName'], 'string', 'max' => 30],
            [['label'], 'string', 'max' => 1024],
            [['strValue'], 'string', 'max' => 10000],
            [['pkOptionName'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkOptionName' => 'Option Code',
            'label' => 'Description',
            'intValue' => 'Numeric Value',
            'strValue' => 'Text Value',
            'exposeToUI' => 'Expose To UI',
        ];
    }
    
    /*
     * assignEmailRecips - for the current email key, populate $to and $cc arrays.
     * The arrays are in the form taken by Swift_Mailer, which can be a mix of
     * sequential or associative:
     *      $to[0] => 'address@domain.com'
     *      $to['address@domamin.com'] => 'Firstname Lastname'
     */
    public function assignEmailRecips(&$to, &$cc)
    {
        if(preg_match('/.+EmailRecips$/', $this->pkOptionName))
        {
            $allRecips = preg_split('/(\s*[,;]\s*)+/', $this->strValue);
            foreach($allRecips as $recip) 
            {
                $isCC = substr($recip, 0, 3) == 'CC:';
                $addr = '';
                $name = '';
                if($isCC)
                    $recip = substr($recip, 3); 
                $bracketOpen = strpos($recip, '<');
                $bracketClose = strpos($recip, '>');
                if($bracketOpen && $bracketClose)
                {
                    $name = trim(substr($recip, 0, $bracketOpen));
                    $addr = substr($recip, $bracketOpen + 1, $bracketClose - $bracketOpen - 1 );
                }
                else
                    $addr = $recip;
                if($isCC)
                    if($name) $cc[$addr] = $name;
                    else $cc[] = $addr;
                else
                    if($name) $to[$addr] = $name;
                    else $to[] = $addr;
            }
        }
    }
    
    /*
     * substituteParams - substitute up to 5 parameters into the string
     * named by the current option key.
     */
    public function substituteParams($p1, $p2='', $p3='', $p4='', $p5='')
    {
        $return = str_replace('%1', $p1, $this->strValue);
        $return = str_replace('%2', $p2, $return);
        $return = str_replace('%3', $p3, $return);
        $return = str_replace('%4', $p4, $return);
        return str_replace('%5', $p5, $return);
    }
}
