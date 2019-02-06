<?php
// показывать или нет выполненные задачи
require_once('functions.php');
$title = 'Дела в порядке';
$project_list = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$task_list = [
	[
		'task' => 'Собеседование в IT компании',
		'datePerformed' => '01.12.2018',
		'category' => 'Работа',
		'isDone' => false
	],
	[
		'task' => 'Выполнить тестовое задание',
		'datePerformed' => '25.12.2018',
		'category' => 'Работа',
		'isDone' => false
	],
	[
		'task' => 'Сделать задание первого раздела',
		'datePerformed' => '21.12.2018',
		'category' => 'Учеба',
		'isDone' => true
	],
	[
		'task' => 'Встреча с другом',
		'datePerformed' => '22.12.2018',
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
]; 

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


