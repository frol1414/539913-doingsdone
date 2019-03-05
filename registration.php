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
// ----- Обязательные поля -----
    foreach ($required as $key) {
// -----Удаление пробелов в начале и конце -----
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
        if (empty($errors['email']) and strlen($data['email']) > 64) {
            $errors['email'] = 'E-mail не может быть длиннее 64 символов';
        }

        if (empty($errors['email']) and (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))) {
            $errors['email'] = 'E-mail введён некорректно';
        }

        $res = get_user_by_email($link, $data['email']);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }
// ----- Хеширование пароля -----
    if (!empty($data['password'])) {
        if (empty($errors['password']) and strlen($data['password']) > 64) {
            $errors['password'] = 'Пароль не может быть длиннее 64 символов';
        }
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    if (empty($errors)) {
        registration_user($link, $data['email'], $data['name'], $password);
        header("Location: /auth.php");
    }
}
// ----- Подключение контента -----
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