
<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" <?php if($show_complete_tasks == 1): ?> checked <?php endif;?> type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
	<?php foreach ($task_list as $key_task_list => $value_task_list): ?>
	<?php if (!$value_task_list['isDone'] || ($value_task_list['isDone'] && $show_complete_tasks)): ?>
    <tr class="tasks__item task <?php if($value_task_list["isDone"]): ?> task--completed <?php endif;?>
    <?php if(deadline($value_task_list["datePerformed"])): ?> task--important <?php endif;?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                <span class="checkbox__text"><?=filter_info($value_task_list["task"]);?> </span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#">Home.psd</a>
        </td>

        <td class="task__date"><?=$value_task_list["datePerformed"];?></td>
    </tr>
    <?php endif; ?>
	<?php endforeach; ?>
    <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
    <?php if($show_complete_tasks == 1): ?>
    <tr class="tasks__item task task--completed">
		<td class="task__select">
			<label class="checkbox task__checkbox">
				<input class="checkbox__input visually-hidden" type="checkbox" checked>
				<span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>
			</label>
		</td>
		<td class="task__date">10.10.2019</td>

		<td class="task__controls">
		</td>
	</tr>
	<?php endif;?>
</table>
