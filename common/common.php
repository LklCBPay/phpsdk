<?php
//强制设置页面编码为UTF-8
header("Content-type: text/html; charset=utf-8");
error_reporting(0);
//设置公共数据参数
define("_PATH_", dirname(__DIR__));
$config = include(_PATH_.'/data/config.php');
include(_PATH_.'/common/function.php');
include(_PATH_.'/common/AESUtil.php');
include(_PATH_.'/class/HttpClient.class.php');
//POST数据
$post = $_POST;$_POST = null;
$config['pk'] = rsa_ges($config['public_key'], 'public');
$config['rk'] = rsa_ges($config['private_key'], 'private');