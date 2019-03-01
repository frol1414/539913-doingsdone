<?php
require_once ('functions.php');
require_once ('init.php');
require_once ('mysql_helper.php');


if (!$user){
    header("Location: /539913-doingsdone/");
    exit();
}

$data = [];
$errors = [];
// ----- Валидация -----
if (!empty($_POST)) {
    $required = ['name'];
// ----- Обязательные поля -----
    foreach ($required as $key) {
        if (!empty($_POST[$key])){
            $data[$key] = trim(mysqli_real_escape_string($link, $_POST[$key]));
        }
        if (empty($data[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
        if (empty($errors[$key]) AND strlen($data[$key]) > 64) {
            $errors[$key] = 'Название не может быть длиннее 64 символов';
        }
    }
    if (empty($errors)) {
        $sql = 'INSERT INTO projects (projects_name, user_id)
        VALUES ("' . $data['name'] .'", ' . $user_id . ')';
        $result_project = mysqli_query($link, $sql);
        if ($result_project) {
            header("Location: /539913-doingsdone/");
        }
    }
}
$page_content = include_template('add_project.php', [
    'data' => $data,
    'errors' => $errors,
    'project_list' => $project_list
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'task_list' => $task_list,
    'project_list' => $project_list,
    'title' => $title_add_project,
    'user' => $user
]);
print($layout_content);