          <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="registration.php" method="post">
            <div class="form__row">
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input <?= !empty($errors['email']) ? "form__input--error" : ""?>" type="text" name="email" id="email" value="<?= !empty($data['email']) ? filter_info($data['email']) : ""?>" placeholder="Введите e-mail">
              <p class="form__message"><?= !empty($errors['email']) ? $errors['email'] : ""?></p>
            </div>

            <div class="form__row">
              <label class="form__label" for="password">Пароль <sup>*</sup></label>

              <input class="form__input <?= !empty($errors['password']) ? "form__input--error" : ""?>" type="password" name="password" id="password" value="<?= !empty($data['password']) ? filter_info($data['password']) : ""?>" placeholder="Введите пароль">
            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>

              <input class="form__input <?= !empty($errors['name']) ? "form__input--error" : ""?>" type="text" name="name" id="name" value="<?= !empty($data['name']) ? filter_info($data['name']) : ""?>" placeholder="Введите имя">
              <p class="form__message"><?= !empty($errors['name']) ? $errors['name'] : ""?></p>
            </div>

            <div class="form__row form__row--controls">
              <?php if (!empty($errors)) : ?>
              <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
              <?php endif; ?>
              <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
          </form>