<section class="adding-post__video tabs__content tabs__content--active">
    <h2 class="visually-hidden">Форма добавления видео</h2>
    <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data">
        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">
                <!-- скрытое поле для типа контента -->
                <input type="hidden" type="text" name="type_id" value="4">

                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                        <input
                            class="adding-post__input form__input"
                            id="video-heading"
                            type="text"
                            name="title"
                            placeholder="Введите заголовок"
                            value="<?= post_parametr('title') ?>"
                        >
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                        </div>
                    </div>
                </div>
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                        <input
                            class="adding-post__input form__input"
                            id="video-url"
                            type="text"
                            name="youtube_url"
                            placeholder="Введите ссылку"
                            value="<?= post_parametr('youtube_url') ?>"
                        >
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                        </div>
                    </div>
                </div>
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="video-tags">Теги</label>
                    <div class="form__input-section">
                        <input
                            class="adding-post__input form__input"
                            id="video-tags"
                            type="text"
                            name="tags"
                            placeholder="Введите ссылку"
                            value="<?= post_parametr('tags') ?>"
                        >
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php foreach($errors as $error): ?>
                        <li class="form__invalid-item"><?= $error ?></li>
                    <? endforeach ?>
                </ul>
            </div>
        </div>

        <div class="adding-post__buttons">
            <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
            <a class="adding-post__close" href="#">Закрыть</a>
        </div>
    </form>
</section>
