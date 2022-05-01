<section class="adding-post__photo tabs__content tabs__content--active">
    <h2 class="visually-hidden">Форма добавления фото</h2>
    <form class="adding-post__form form" action="/add.php" method="post" enctype="multipart/form-data">
        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">
                <!-- скрытое поле для типа контента -->
                <input type="hidden" type="text" name="type_id" value="3">

                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section">
                        <input
                            class="adding-post__input form__input"
                            id="photo-heading"
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
                    <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                    <div class="form__input-section">
                        <input
                            class="adding-post__input form__input"
                            id="photo-url"
                            type="text"
                            name="img_url"
                            placeholder="Введите ссылку"
                            value="<?= post_parametr('img_url') ?>"
                        >
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                        </div>
                    </div>
                </div>

                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                    <div class="form__input-section">
                        <input
                            class="adding-post__input form__input"
                            id="photo-tags"
                            type="text"
                            name="tag"
                            placeholder="Введите теги"
                            value="<?= post_parametr('tag') ?>"
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
        <div class="adding-post__input-file-container form__input-container form__input-container--file">
            <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                    <input
                        class="adding-post__input-file form__input-file"
                        id="userpic-file-photo"
                        type="file"
                        name="img_file"
                        title=" "
                        value="<?= post_parametr('img_file') ?>"
                    >
                    <div class="form__file-zone-text">
                        <span>Перетащите фото сюда</span>
                    </div>
                </div>
                <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                    <span>Выбрать фото</span>
                    <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                        <use xlink:href="#icon-attach"></use>
                    </svg>
                </button>
            </div>
            <div class="adding-post__file adding-post__file--photo form__file dropzone-previews"></div>
        </div>
        <div class="adding-post__buttons">
            <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
            <a class="adding-post__close" href="#">Закрыть</a>
        </div>
    </form>
</section>
