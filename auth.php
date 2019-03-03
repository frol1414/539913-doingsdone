<?php
require_once ('functions.php');
require_once('init.php');
require_once ('mysql_helper.php');

if ($user){
    header("Location: /539913-doingsdone/index.php");
    exit();
}
$data = [];
$errors = [];

if (!empty($_POST)) {
    $required = ['email', 'password'];
// ----- Обязательные поля -----
    foreach ($required as $key) {
        if (!empty($_POST[$key])){
// ----- Экранируем спецсимволы -----
            $data[$key] = mysqli_real_escape_string($link, $_POST[$key]);
        }

        if (empty($data[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

// ----- Валидация полей -----
    if (empty($errors['email']) and (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) or strlen($data['email']) > 128)) {
        $errors['email'] = 'E-mail введён некорректно';
    }
    if (empty($errors)) {
        $email = $data['email'];
        $sql = 'SELECT * FROM user WHERE email = "' . $email . '"';
        $res = mysqli_query($link, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if ($user === null) {
            $errors['email'] = 'Такой пользователь не найден';
        }
// ----- Валидация пароля -----
        elseif (password_verify($data['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /539913-doingsdone/index.php");
               exit();
        }
        else {
            $errors['password'] = 'Неверный пароль';
         }
    }
}
$page_content = include_template('auth.php', [
    'data' => $data,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Вход на сайт',
    'task_list' => '',
    'project_list' => '',
]);

print($layout_content);
?>