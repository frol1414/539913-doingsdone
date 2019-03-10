<?php
// ----- Шаблонизатор -----
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;
    $result = ob_get_clean();
    return $result;
}

// ----- Счетчик задач -----
function count_tasks($array, $name)
{
	$result = 0;
	foreach ($array as $key => $value) {
		if ($value["projects_id"] === $name) {
			$result++;
		}
	}
	return $result;
}

// ----- Фильтр от XSS -----
function filter_info($str) {
    $text = htmlentities($str);
    return $text;
}

// ----- Функция подсчета дедлайна задачи -----
function deadline($value) {
	$date_st = strtotime($value);
    $end_time = $date_st - time();
    $value_date = ($end_time <= 86400)? true : false;
    if($value === null){
        $value_date = false;
    }
    return $value_date;
}

// ----- Функция вызова задач для одного автора -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @return mysqli_stmt Подготовленное выражение
 */
function get_projects($link, $user) {
    $sql = "SELECT * FROM projects WHERE user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $project_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $project_list;
}

// ----- Функция вызова имен категорий для одного автора -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @return mysqli_stmt Подготовленное выражение
 */
function get_tasks_for_author_id ($link, $user) {
    $sql = "SELECT * FROM tasks WHERE user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $task_list;
}

// ----- Функция вызова задач для одного проекта -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @param $projects_id int ID проекта (по умолчанию нет)
 * @return mysqli_stmt Подготовленное выражение
 */
function get_tasks_for_author_id_and_project($link, $user, $projects_id = null) {
    if(!empty($projects_id)) {
        $sql = "
                SELECT DISTINCT tasks.*, projects.projects_name AS projects_name
                FROM tasks
                INNER JOIN projects ON tasks.projects_id = projects.projects_id
                WHERE projects.user_id = ? AND tasks.projects_id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$user, $projects_id]);
    } else {
        $sql = "
                SELECT DISTINCT tasks.*, projects.projects_name AS projects_name
                FROM tasks
                INNER JOIN projects ON tasks.projects_id = projects.projects_id
                WHERE projects.user_id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $task_list;
}

function none_id(int $projects_id, $project_list) {
    foreach($project_list as $list_info) {
        if ($projects_id == $list_info['projects_id']) {
            return true;
        }
    }
    return false;
}

// ----- Регистрация пользователя -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $email string Email пользователя
 * @param $name string Имя пользователя
 * @param $password string Пароль пользователя
 * @return mysqli_stmt boolean
 */
function registration_user($link, $email, $name, $password) {
    $sql = "
            INSERT INTO user (registration_date, email, name, password)
            VALUES (NOW(), ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$email, $name, $password]);
        mysqli_stmt_execute($stmt);
}

// ----- Добавление задач в БД -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $task_name string Имя задачи
 * @param $file Файл
 * @param $deadline Дедлайн задачи
 * @param $user int Идентификатор автора
 * @param $project_name Имя проекта
 * @return mysqli_stmt boolean
 */
function add_task_form($link, $task_name, $file, $deadline, $user, $project_name = null) {
    $sql = '
            INSERT INTO tasks (creation_date, execution_date, status, name, file, deadline, user_id, projects_id)
            VALUES (NOW(), NULL, 0, ?, ?,  ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql,  [$task_name, $file, $deadline, $user, $project_name]);
        mysqli_stmt_execute($stmt);
}

// ----- Добавление проекта в БД -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user_id int ID автора
 * @param $project_name Имя проекта
 * @return mysqli_stmt boolean
 */
function add_project_form($link, $projects_name, $user_id) {
    $sql = "
            INSERT INTO projects (projects_name, user_id) VALUES (?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql,  [$projects_name, $user_id]);
        mysqli_stmt_execute($stmt);
}

// ----- Фильтр задач на сегодня для данного пользователя -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @return mysqli_stmt Подготовленное выражение
 */
function get_tasks_for_user_by_agenda($link, int $user)
{
    $sql = "
            SELECT * FROM tasks WHERE user_id = ? AND DATE(tasks.deadline) = CURDATE()";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $task_list;
}

// ----- Фильтр задач на завтра для данного пользователя -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @return mysqli_stmt Подготовленное выражение
 */

function get_tasks_for_user_by_tomorrow($link, int $user)
{
    $sql = "
            SELECT * FROM tasks WHERE user_id = ? AND DATE(tasks.deadline) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $task_list;
}

// ----- Фильтр просроченных задач для данного пользователя -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @return mysqli_stmt Подготовленное выражение
 */
function get_tasks_for_user_by_overdue($link, int $user)
{
    $sql = "
            SELECT * FROM tasks WHERE user_id = ? AND DATE(tasks.deadline) < CURDATE()";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $task_list;
}

// ----- Поиск -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $search string Параметр поиска
 * @param $user_id int ID автора
 * @return mysqli_stmt Подготовленное выражение
 */
function searh_task($link, $search, int $user_id) {
    $sql = "SELECT * FROM tasks WHERE MATCH(name) AGAINST(?) AND user_id = ?";

    $stmt = db_get_prepare_stmt($link, $sql, [$search, $user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// ----- Фильтр всех задач для данного пользователя -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $user int Идентификатор автора
 * @param $projects_id int ID проекта (по умолчанию нет)
 * @param $filter Наличие фильтра (по умолчанию нет)
 * @param $search Наличие поиска (по умолчанию нет)
 * @return array
 */
function get_tasks_for_user_filter($link, int $user, int $projects_id = null, $filter = null, $search = null)
{
    if (!empty($projects_id)) {
        return get_tasks_for_author_id_and_project($link, $user, $projects_id);
    } elseif (!empty($search)) {
        return searh_task($link, $search, (int) $user);  
    } else {
        switch ($filter) {
            case 'agenda' :
                return get_tasks_for_user_by_agenda($link, (int)$user);
            case 'tomorrow' :
                return get_tasks_for_user_by_tomorrow($link, (int)$user);
            case 'overdue' :
                return get_tasks_for_user_by_overdue($link, (int)$user);
            default :
                return get_tasks_for_author_id($link, (int)$user);
        }
    }
}

// ----- Изменение статус задачи -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $task_id int ID задачи
 * @param $check int Новый статус задачи
 * @param $user_id int ID автора
 * @return boolean
 */
/*function change_task_status($link, int $task_id, int $check, int $user_id)
{
    $sql = "
            UPDATE tasks INNER JOIN projects ON projects.projects_id = tasks.projects_id
            SET tasks.status = ? WHERE tasks.task_id = ? AND projects.user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$check, $task_id, $user_id]);
    return mysqli_stmt_execute($stmt);
}*/

function change_task_status($link, int $task_id, int $check, int $user_id)
{
    $sql = "
            UPDATE tasks 
            SET status = ? WHERE task_id = ? AND user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$check, $task_id, $user_id]);
    return mysqli_stmt_execute($stmt);
}

// ----- Проверяет email пользователя -----
/**
 * @param $link mysqli Ресурс соединения
 * @param $email string Адрес пользователя
 * @return boolean
 */
function get_user_by_email($link, $email)
{
    $email = mysqli_real_escape_string($link, $email);
    $sql = 'SELECT * FROM user WHERE email = "' . $email . '"';
    return mysqli_query($link, $sql);
}

// ----- Возврат даты в формате Д.М.Г. -----
/**
 * @param $value NULL/string Дата выполнения задачи
 * @return $dt_format string Дата в формате Д.М.Г
 */
function change_format_deadline($value) {
    if ($value === NULL) {
        return null;
    }
    $date = date_create($value);
    $dt_format = date_format($date, 'd.m.Y');
    return $dt_format;
}

?>