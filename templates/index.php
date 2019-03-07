
<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
    <input class="search-form__input" type="text" name="search" value="<?=$search?>" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>
<?php if(!empty($search)): ?>
    <p>По вашему запросу ничего не найдено</p>
<?php endif; ?>
<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/index.php?filter=all"
           class="tasks-switch__item <?= !isset($filter) || $filter === "all" ? "tasks-switch__item--active" : "" ?>">Все
            задачи</a>
        <a href="/index.php?filter=agenda"
           class="tasks-switch__item <?= $filter === "agenda" ? "tasks-switch__item--active" : "" ?>">Повестка дня</a>
        <a href="/index.php?filter=tomorrow"
           class="tasks-switch__item <?= $filter === "tomorrow" ? "tasks-switch__item--active" : "" ?>">Завтра</a>
        <a href="/index.php?filter=overdue"
           class="tasks-switch__item <?= $filter === "overdue" ? "tasks-switch__item--active" : "" ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed"
               type="checkbox" <?= $show_complete_tasks ? "checked" : "" ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>
<table class="tasks">
    <?php foreach ($task_list as $key): ?>
        <?php if (!$key['status'] || $show_complete_tasks): ?>
            <tr class="tasks__item task <?= $key['status'] ? "task--completed" : "" ?> <?= deadline($key['deadline']) ? "task--important" : "" ?>">

                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"
                               value="<?= $key['task_id'] ?>"<?= ($key['status']) ? "checked" : "" ?>>
                        <span class="checkbox__text"><?= filter_info($key['name']) ?></span>
                    </label>

                </td>

                <td class="task__file">
                    <?php if (isset($key['file'])) :?>
                    <a class="download-link" href="<?= 'uploads/' . $key['file']; ?>"></a>
                <?php endif;?>
                </td>

                <td class="task__date"><?= empty($key['deadline']) ? '' : change_format_deadline($key['deadline']);?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
</table>
