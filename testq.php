<?php
namespace saber;

use saber\WorkWechat\Factory;

include 'vendor\autoload.php';

$app =Factory::WorkWx([]);
$res = $app->user->get('13558675584');

var_dump($res->getBody()->getContents());
