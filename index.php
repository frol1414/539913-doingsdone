<?php
require_once ('functions.php');
require_once ('init.php');
require_once ('mysql_helper.php');
$user = 2;

// ----- Ошибка на подключение -----
if ($link == false) {
    print(mysqli_connect_error());
}
// ----- Проверка на наличие id категории -----
$project_list = get_projects($link, $user);
$projects_id = null;
if (isset($_GET['projects_id'])) {
    $projects_id = (int) $_GET['projects_id'];
    if (!none_id($projects_id, $project_list)) {
        print("HTTP/1.0 404 Not Found");
        exit();
    }
}

// ----- Подключение контента -----
$page_content = include_template('index.php', [
    'task_list' => get_tasks_for_author_id_and_project($link, $user, $projects_id), 
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'task_list' => get_tasks_for_author_id($link, $user), 
    'project_list' => get_projects($link, $user)
]);
print($layout_content);
?>