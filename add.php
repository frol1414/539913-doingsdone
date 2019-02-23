<?php
require_once ('functions.php');
require_once ('init.php');
require_once ('mysql_helper.php');
$user = 2;
$project_list = get_projects($link, $user);
$task_list = get_tasks_for_author_id ($link, $user);
$task = [];
$errors = [];
// ----- Валидация -----
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Экранируем спецсимволы
    if (!empty($_POST)) {
        $task = $_POST;
    }
    $required = ['name', 'project'];
// ----- Обязаьтельные поля -----
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }
// ----- Проверка полей -----
    if (empty($errors['name']) and strlen($task['name']) > 128) {
        $errors['name'] = 'Название не может быть длиннее 128 символов';
    }
    $task_name = $task['name'];
    $project_name = $task['project'];
// ----- Валидация даты -----
    if (empty($task['date'])) {
        $deadline = 'null';
    }
    elseif (empty($errors['date']) and strtotime($task['date']) < time()) {
        $errors['date'] = 'Дата не может быть раньше текущей';
    }
    else {
        $deadline = $task['date'];
    }
// ----- Загрузка файла -----
    if (is_uploaded_file($_FILES['preview']['tmp_name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = uniqid();
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $file = $path;
    }
    else {
        $file = 'null';
    }
    if (empty($errors)) {
        add_task_form($link, $task_name, $file, $deadline, $user, $project_name);
        header("Location: /539913-doingsdone/index.php");
    }
}

$page_content = include_template('add.php', [
    'task' => $task,
    'errors' => $errors,
    'project_list' => $project_list
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'task_list' => $task_list,
    'project_list' => $project_list,
    'title' => $title
]);
print($layout_content);
?>