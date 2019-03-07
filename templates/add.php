<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>

    <input class="form__input <?= !empty($errors['name']) ? "form__input--error" : ""?>" type="text" name="name" id="name" value="<?=!empty($task['name']) ? filter_info($task['name']) : ""?>" placeholder="Введите название">

    <p class="form__message"><?= !empty($errors['name']) ? $errors['name'] : ""?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="project">Проект</label>
      <?php $classname = isset($errors['project']) ? "form__input--error" : ""; $value = isset($task['project']) ? $task['project'] : ""?>
            <select class="form__input form__input--select" name="project" id="project">
              <option value="">Без проекта</option>

              <?php foreach ($project_list as $project): ?>
                <option <?= $project['projects_id'] == $value ? 'selected' : '' ?> value="<?= filter_info($project['projects_id']); ?>"><?= filter_info($project['projects_name']); ?></option>

              <?php endforeach ?>
            </select>
            <?php if (isset($errors['project'])): ?>
            <p class="form__message"><?=$errors['project']?></p>
          <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date <?= !empty($errors['date']) ? "form__input--error" : ""?>" type="date" name="date" id="date" value="<?= !empty($task['date']) ? $task['date'] : ""?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

    <p class="form__message"><?= !empty($errors['date']) ? $errors['date'] : "";?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="preview">Файл</label>

    <div class="form__input-file">
      <input class="visually-hidden" type="file" name="preview" id="preview" value="">

      <label class="button button--transparent" for="preview">
        <span>Выберите файл</span>
      </label>
    </div>

  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">

    <?php if (!empty($errors)) : ?>
        <p class="form__message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
  </div>
</form>