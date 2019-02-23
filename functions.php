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

// ----- Показывать или нет выполненные задачи -----
$show_complete_tasks = rand(0, 1);

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
    $text = htmlspecialchars($str);
    return $text;
}

// ----- Функция подсчета дедлайна задачи -----
function deadline($value) {
	$date_st = strtotime($value);
    $end_time = $date_st - time();
    $value_date = ($end_time <= 86400)? true : false;
    if($value === ''){
        $value_date = false;
    }
    return $value_date;
}

// ----- Функция вызова задач для одного автора -----
function get_projects($link, $user) {
    $sql = "SELECT * FROM projects WHERE user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $project_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $project_list;
}

// ----- Функция вызова имен категорий для одного автора -----
function get_tasks_for_author_id ($link, $user) {
    $sql = "SELECT * FROM tasks WHERE user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $task_list;
}

// ----- Функция вызова задач для одного проекта -----
function get_tasks_for_author_id_and_project($link, int $user, int $projects_id=null) {
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

function none_id($projects_id, $project_list) {
    foreach($project_list as $list_info) {
        if ($projects_id == $list_info['projects_id']) {
            return true;
        }
    }
    return false;
}
// ----- Добавление задач в БД -----
function add_task_form($link, $task_name, $file, $deadline, $user, $project_name) {
    $sql = 'INSERT INTO tasks (creation_date, execution_date, status, name, file, deadline, user_id, projects_id)
        VALUES (NOW(), NULL, 0, ?, ?, ' . $deadline . ', ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql,  [$task_name, $file, $user, $project_name]);
        mysqli_stmt_execute($stmt);
}


?>