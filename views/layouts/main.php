<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <style>
        
        .dropdown-submenu ul {
            list-style-type: none;
            margin-left: 30px;
            padding: 0;
        }
        
        .dropdown-submenu a {
            color: graytext;
        }
    </style>
    
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if( Yii::$app->user->isGuest )
        echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Admin', 'url' => ['/site/login']],
                    ['label' => 'Invoice', 'url' => ['/site/invoice']],
                ]
            ]);
    else        
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Admin Home', 'url' => ['/site/admin']],
                ['label' => 'Edit', 'items' => [
                    ['label' => 'People', 'url' => ['/person/index'], 'items' => [
                        ['label' => 'Instructor Status', 'url' => ['/inst-status/index']],
                        ['label' => 'Availability', 'url' => ['/inst-availability/index']],
                        ['label' => 'LCI Status', 'url' => ['/inst-lci/index']],
                        ['label' => 'Age Groups', 'url' => ['/inst-age/index']],
                        ['label' => 'Types of Instruction', 'url' => ['/inst-activity/index']],
                        ['label' => 'Riding Disciplines', 'url' => ['/inst-ride-type/index']],
                        ['label' => 'Mechanical Knowledge', 'url' => ['/inst-mechanical/index']],
                        ['label' => 'Medical Knowledge', 'url' => ['/inst-medical/index']],
                    ]],
                    ['label' => 'Organizations', 'url' => ['/org/index']],
                    ['label' => 'Events', 'url' => ['/event/index'], 'items' => [
                        ['label' => 'Types', 'url' => ['/event-type/index']],
                        ['label' => 'Age Groups', 'url' => ['/event-age/index']],
                        ['label' => 'Settings', 'url' => ['/event-setting/index']],
                    ]],
                    ['label' => 'Invoices', 'url' => ['/invoice/index']],
                    ['label' => 'Master Data', 'items' => [
                        ['label' => 'City Attributes', 'url' => ['/city-county']],
                        ['label' => 'Rates', 'url' => ['/rate/index']],
                        ['label' => 'Mileage', 'url' => ['/mileage/index']],
                        ['label' => 'Configuration', 'url' => ['/config/index']],
                    ]],
                ]],
                ['label' => 'Actions', 'items' => [
                    ['label' => 'Assign Instructors', 'url' => ['/event/requests']],
                    ['label' => 'Approve Invoices', 'url' => ['/invoice/index', 'show' => 'N']],
                    ['label' => 'Report on Activity', 'url' => ['#']],
                ]],
                ['label' => 'About', 'url' => ['/site/about']],
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ]
        ]
    );

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Bicycle Coalition of Maine <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
