<?php
require_once 'system/config.php';  //подключаем файл конфигураций и начинаем его выполнение
$WEB_APP['view'] = new view_smarty();    //подключаем шаблонизатор Smarty
$router = new router(); // запускаем маршрутизацию
$router->set_module_path('modules'); // задаем папку с модулями
$router->delegate();
$adodb->Close(); //Закрываем БД
?>