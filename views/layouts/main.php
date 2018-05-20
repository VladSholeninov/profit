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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php
	
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js');
        $this->registerJsFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
        $this->registerJsFile('/web/js/BootstrapAlert.js');

        $this->head()
    ?>
</head>
<body>

<?php $this->beginBody() ?>

<div id='content-site'>
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"> Профит </p>
    </div>
</footer>

<!--  шаблон для уведомления bsalert  -->
<div id="alert_placeholder"></div>
		
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
