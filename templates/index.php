
<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/539913-doingsdone/index.php?filter=all"
           class="tasks-switch__item <?= !isset($filter) || $filter === "all" ? "tasks-switch__item--active" : "" ?>">Все
            задачи</a>
        <a href="/539913-doingsdone/index.php?filter=agenda"
           class="tasks-switch__item <?= $filter === "agenda" ? "tasks-switch__item--active" : "" ?>">Повестка дня</a>
        <a href="/539913-doingsdone/index.php?filter=tomorrow"
           class="tasks-switch__item <?= $filter === "tomorrow" ? "tasks-switch__item--active" : "" ?>">Завтра</a>
        <a href="/539913-doingsdone/index.php?filter=overdue"
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
            <tr class="tasks__item task <?= $key['done'] ? "task--completed" : "" ?> <?= deadline($key['deadline']) && !$key['status'] ? "task--important" : "" ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"
                               value="<?= $key['task_id'] ?>"<?= ($key['status']) ? "checked" : "" ?>>
                        <span class="checkbox__text"><?= filter_info($key['name']) ?></span>
                    </label>

                </td>

                <td class="task__file">
                    <a class="download-link" href="<?= 'uploads/' . $key['file']; ?>"></a>
                </td>

                <td class="task__date"><?= empty($key['deadline']) ? '' : date('d.m.Y', strtotime($key['deadline'])) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
</table>