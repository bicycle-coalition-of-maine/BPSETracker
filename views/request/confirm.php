<?php

use app\models\Request;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\Request */
/* @var $table string */

$this->title = $title;

$this->registerCSSFile('/css/request.css');

?>

<div class='request-confirm'>

    <h1><?= $this->title ?></h1>
    
    <p style='font-size: larger;'><?= $msg ?></p>
    
    <h2>Submitted information:</h2>
    
    <table><?= $table ?></table>
    
    <p style='margin-top: 1em;'><a href='http://www.bikemaine.org'>Back to BCM Home</a></p>
    
</div>
    