<?php
require_once ('functions.php');
require_once ('init.php');
require_once ('mysql_helper.php');

// ----- При отсутствии пользователя - переход на начальную страницу -----
if (!$user) {
    header("Location: /539913-doingsdone/");
    exit();
}
$data = [];
$errors = [];
// ----- Валидация -----
if (!empty($_POST)) {
    $required = ['name'];
// ----- Обязаьтельные поля -----
    foreach ($required as $key) {
        if (!empty($_POST[$key])) {
// ----- Экранирование спецсимволов -----
            $data[$key] = trim(mysqli_real_escape_string($link, $_POST[$key]));
        }
// ----- Проверка полей -----
        if (empty($data[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
        if (empty($errors[$key]) and strlen($data[$key]) > 64) {
            $errors[$key] = 'Название не может быть длиннее 64 символов';
        }
    }
// ----- Проверка на существование такого же проекта -----
    if (empty($errors)) {
        $sql_project = 'SELECT * FROM projects WHERE user_id = "' . $user_id . '" AND projects_name = "' . $data['name'] . '"';
        $res = mysqli_query($link, $sql_project);
        if (mysqli_num_rows($res) > 0) {
            $errors['name'] = 'Такой проект уже существует';
        }
    }
// ----- Добавляем проект, если нет ошибок -----
    if (empty($errors)) {
        add_project_form($link, $data['name'], $user_id);
        header("Location: /539913-doingsdone/index.php");
    }
}
// ----- Подключение контента -----
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
?>