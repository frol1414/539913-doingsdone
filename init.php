<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once ('functions.php');
require_once ('config/db.php');
require_once ('mysql_helper.php');
session_start();
$title_main = 'Дела в порядке';
$title_add_task = 'Добавление задачи';
$title_auth = 'Аутентификация';
$title_registration = 'Регистрация';
$title_add_project = 'Добавление проекта';


$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");
$project_list = [];
$task_list = [];
$content = '';


$user = !empty($_SESSION['user']) ?  $_SESSION['user'] : [];
$user_id = !empty($user['user_id']) ? $user['user_id'] : '';

$project_list = get_projects($link, $user_id);
$task_list = get_tasks_for_author_id ($link, $user_id);

?>