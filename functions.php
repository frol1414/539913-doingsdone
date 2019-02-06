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
		if ($value["category"] === $name) {
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
?>


