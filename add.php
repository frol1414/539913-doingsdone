<?php
require_once ('functions.php');
require_once ('init.php');
require_once ('mysql_helper.php');

if (!$user) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

$task = [];
$errors = [];
// ----- Валидация -----
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST)) {
        $task = $_POST;
    }


// ----- Обязательное поле -----
    if (empty($task['name'])) {
        $errors['name'] = 'Это поле надо заполнить';
    }
// ----- Проверка полей -----
    if (empty($errors['name']) and strlen($task['name']) > 64) {
        $errors['name'] = 'Название не может быть длиннее 64 символов';
    }
    $task_name = $task['name'];

    $project_name = null;
        if (!empty($task['project'])) {
            $project_name = $task['project'];
        }
// ----- Валидация даты -----
    if (empty($task['date'])) {
        $deadline = null;
    }
    elseif (empty($errors['date']) and strtotime($task['date']) < strtotime(date('d-m-Y'))) {
        $errors['date'] = 'Дата не может быть раньше текущей';
    }
    else {
        $date = $task['date'];
        $deadline = date('Y-m-d', strtotime($date));
    }
// ----- Загрузка файла -----
    if (is_uploaded_file($_FILES['preview']['tmp_name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = uniqid();
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $file = $path;
    }
    else {
        $file = null;
    }
    if (empty($errors)) {
        add_task_form($link, $task_name, $file, $deadline, $user_id, $project_name);
        header("Location: /index.php");
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
    'title' => $title_add_task
]);
print($layout_content);
?>