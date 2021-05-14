<section class="adding-post__text tabs__content tabs__content--active">
    <h2 class="visually-hidden">Форма добавления текста</h2>
    <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="active-tab" value="<?= $active_tab ?>" />
    <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="post-heading">Заголовок <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['post-heading']) ? 'form__input-section--error' : '' ?>">
                    <input
                        class="adding-post__input form__input"
                        id="post-heading"
                        type="text"
                        name="post-heading"
                        value="<?= get_post_val('post-heading') ?>"
                        placeholder="Введите заголовок">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <?php if(isset($errors) && isset($errors['post-heading'])): ?>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= $errors['post-heading']['message'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['post-text']) ? 'form__input-section--error' : '' ?>">
                    <textarea
                        class="adding-post__textarea form__textarea form__input"
                        id="post-text"
                        name="post-text"
                        placeholder="Введите текст публикации"><?= get_post_val('post-text') ?></textarea>
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <?php if(isset($errors) && isset($errors['post-text'])): ?>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= $errors['post-text']['message'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="post-tags">Теги</label>
                <div class="form__input-section <?= isset($errors['post-tags']) ? 'form__input-section--error' : '' ?>">
                    <input
                        class="adding-post__input form__input"
                        id="post-tags"
                        type="text"
                        name="post-tags"
                        value="<?= get_post_val('post-tags') ?>"
                        placeholder="Введите теги">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                    <?php if(isset($errors) && isset($errors['post-tags'])): ?>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= $errors['post-tags']['message'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if(!empty($errors)): ?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php foreach ($errors as $error): ?>
                        <li class="form__invalid-item"><?= $error['title'] ?>. <?= $error['message'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
</form>
</section>
