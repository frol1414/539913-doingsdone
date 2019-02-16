<?php
require_once ('functions.php');
require_once ('init.php');
$title = 'Дела в порядке';
//$project_list = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
/*$task_list = [
	[
		'task' => 'Собеседование в IT компании',
		'datePerformed' => '01.12.2019',
		'category' => 'Работа',
		'isDone' => false
	],
	[
		'task' => 'Выполнить тестовое задание',
		'datePerformed' => '25.12.2019',
		'category' => 'Работа',
		'isDone' => false
	],
	[
		'task' => 'Сделать задание первого раздела',
		'datePerformed' => '21.12.2019',
		'category' => 'Учеба',
		'isDone' => true
	],
	[
		'task' => 'Встреча с другом',
		'datePerformed' => '05.01.2019',
		'category' => 'Входящие',
		'isDone' => false
	],
	[
		'task' => 'Купить корм для кота',
		'datePerformed' => 'Нет',
		'category' => 'Домашние дела',
		'isDone' => false
	],
	[
		'task' => 'Заказать пиццу',
		'datePerformed' => 'Нет',
		'category' => 'Домашние дела',
		'isDone' => false
	]
]; */


if (!$link) {
	$error = mysqli_connect_error();
}
else {
	$sql = 'SELECT * FROM projects WHERE user_id = 1';
	$result = mysqli_query($link, $sql);
	if ($result) {
    	$project_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	else {
		$error = mysqli_error($link);
	}

	$sql = 'SELECT * FROM tasks WHERE user_id = 1';
	$result = mysqli_query($link, $sql);
	if ($result) {
    	$task_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	else {
    	$error = mysqli_error($link);
	}
}




$page_content = include_template('index.php', [
    'task_list' => $task_list, 
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'task_list' => $task_list, 
    'project_list' => $project_list
]);
print($layout_content);
?>


