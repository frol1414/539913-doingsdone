<?php
require_once ('functions.php');
require_once('init.php');
$data = [];
$errors = [];

if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $data[$key] = mysqli_real_escape_string($link, $_POST[$key]);
    }
    $required = ['email', 'password', 'name'];
    // Обязательные поля
    foreach ($required as $key) {
        // Удаляет пробелы из начала и конца строки
        if (!empty($data[$key])) {
            $data[$key] = trim($data[$key]);
        }
        if (empty($data[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }
// ----- Валидация полей -----
    if (empty($errors['name']) and strlen($data['name']) > 64) {
        $errors['name'] = 'Имя должно быть не длиннее 64 символов';
    }

// ----- Валидация e-mail -----
    if (!empty($data['email'])) {
        if (empty($errors['email']) and strlen($data['email']) > 128) {
            $errors['email'] = 'E-mail не может быть длиннее 128 символов';
        }
        elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        }
        $sql = 'SELECT user_id FROM user WHERE email = "' . $data['email'] . '"';
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

// ----- Хеширование пароля -----
    if (!empty($data['password'])) {
        if (empty($errors['password']) and strlen($data['password']) > 128) {
            $errors['password'] = 'Пароль не может быть длиннее 64 символов';
        }
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    if (empty($errors)) {
        $sql_add_user = 'INSERT INTO user (registration_date, email, name, password) VALUES (NOW(), "' . $data['email'] . '", "' . $data['name'] . '", "' . $password . '")';
        $add_user = mysqli_query($link, $sql_add_user);
        if ($add_user) {
            header("Location: /539913-doingsdone/index.php");
        }
    }
}
$page_content = include_template('reg.php', [
    'data' => $data,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'task_list' => '',
    'project_list' => '',
    'title' => $title_registration
]);
print($layout_content);
?>