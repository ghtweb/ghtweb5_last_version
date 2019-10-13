<?php $assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.install.assets'), false, -1, YII_DEBUG) ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GHTWEB install</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $assetsUrl ?>/css/style.css"/>

</head>
<body>

<div class="wrapper"><?php echo $content ?></div>

</body>
</html>