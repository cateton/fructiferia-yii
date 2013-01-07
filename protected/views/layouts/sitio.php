<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link href="<?php echo Yii::app()->baseUrl; ?>/css/reset.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->baseUrl; ?>/css/960_24_col.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->baseUrl; ?>/css/text.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->baseUrl; ?>/css/sitio.principal.css" rel="stylesheet" type="text/css" />

        <title>FRUCTIFERIA</title>
    </head>
    <body>
        <div class="container_24">
            <div class="grid_24" style="margin: 20px 0px 20px 0px;">
                <div style="width: 50%; float: left;">
                    <?php echo CHtml::image(Yii::app()->baseUrl . '/images/logo.png', 'Fructiferia'); ?>
                </div>
                <div style="width: 50%; float: left;"></div>
            </div>
            
            <div class="grid_24 nav_container">
                <ul id="nav">
                    <li>Inicio</li>
                </ul>
            </div>

            <div class="grid_24 header-block-2">
                <ul class="links">
                    <li><a href="" title="Mi Cuenta">Mi Cuenta</a></li>
                    <li><a href="" title="Mis Favoritos">Mis Favoritos</a></li>
                    <li><a href="" title="Mi Carro">Mi Carro</a></li>
                    <li><a href="" title="Terminar Pedido">Terminar Pedido</a></li>
                    <li><a href="" title="Log In">Log In</a></li>
                </ul>
                <p class="welcome-msg">Bienvenido a nuestra tienda on-line</p>            
                <br class="clear">
            </div>

            <div class="grid_24" style="height: 10px;"></div>

            <?php echo $content; ?>
        </div>
    </body>
</html>
