<?php
require_once ('functions.php');
require_once ('init.php');
require_once ('mysql_helper.php');

// ----- Ошибка на подключение -----
if (!$link) {
    print(mysqli_connect_error());
    exit('Сайт временно не доступен.');
}
// ----- Проверка на наличие id категории -----
$project_list = get_projects($link, $user_id);
$projects_id = null;
if (isset($_GET['projects_id'])) {
    $projects_id = (int) $_GET['projects_id'];
    if (!none_id($projects_id, $project_list)) {
        print("HTTP/1.0 404 Not Found");
        exit();
    }
}
// ----- Изменение статуса задачи -----
if (isset($_GET['task_id']) && isset($_GET['check'])) {
    if (!change_task_status($link, $_GET['task_id'], $_GET['check'], $user_id)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    } 
}

// ----- Показ выполненных задач -----
$show_complete_tasks = 0;
if (isset($_GET['show_completed'])) {
   $show_complete_tasks = $_GET['show_completed'];
}
// ----- Фильтр задач -----
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;

// ----- Подключение контента -----
if (!empty($_SESSION['user'])) {
    $page_content = include_template('index.php', [
    	'task_list' => get_tasks_for_user_filter($link, (int)$user_id, (int)$projects_id, $filter),
        'show_complete_tasks' => $show_complete_tasks,
        'filter' => $filter
    ]);

    $layout_content = include_template('layout.php', [
    	'content' => $page_content,
    	'title' => $title_main,
    	'task_list' => get_tasks_for_author_id($link, $user_id), 
    	'project_list' => get_projects($link, $user_id)
	]);
}
else {
    $page_content = include_template('guest.php', [
    ]);

	$layout_content = include_template('layout.php', [
    	'content' => $page_content,
    	'title' => $title_main,
    	'task_list' => '', 
    	'project_list' => ''
	]);
}
print($layout_content);
?>