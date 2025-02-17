<?php

require_once(__DIR__ . '/core/App.php');

$app = new App();

$app->autoload();
$app->config();
$app->start();
