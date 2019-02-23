<?php
require_once ('functions.php');
require_once('init.php');
$data = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST)) {
        $data = $_POST;
    }
// ----- Валидация на обязательные поля -----
    $required = ['email', 'password', 'name'];
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }
// ----- Валидация полей -----
    if (empty($errors['name']) and strlen($data['name']) > 64) {
        $errors['name'] = 'Имя должно быть не длиннее 64 символов';
    }
    if (empty($errors['email']) and strlen($data['email']) > 64) {
        $errors['email'] = 'E-mail должно быть не длиннее 64 символов';
    }
    if (empty($errors['password']) and strlen($data['password']) > 64) {
        $errors['password'] = 'Пароль должно быть не длиннее 64 символов';
    }
// ----- Валидация e-mail -----
    if (!empty($data['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        }
        $sql = 'SELECT user_id FROM user WHERE email = ' . $data['email'];
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }
// ----- Хеширование пароля -----
    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    if (empty($errors)) {
        $sql_add_user = 'INSERT INTO user (registration_date, email, name, password) VALUES (NOW(),' . $data['email'] . ', ' . $data['name'] . ', ' . $password . ')';
        $add_user = mysqli_query($link, $sql_add_user);
        if ($add_user) {
            header("Location: /539913-doingsdone/index.php");
        }
    }
}
$layout_content = include_template('reg.php', [
    'data' => $data,
    'errors' => $errors,
    'title' => $title,
]);
print($layout_content);
?>