<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
            <ul class="messages__contacts-list tabs__list">
                <?php if ($start_user) : ?>
                    <li class="messages__contacts-item">
                        <a
                            class="messages__contacts-tab tabs__item messages__contacts-tab--active tabs__item--active; ?>"
                            href="messages.php?user_id=<?= $start_user['id'] ?>"
                        >
                            <div class="messages__avatar-wrapper">
                                <img class="messages__avatar" src="<?= $start_user['avatar'] ?>" alt="Аватар пользователя">
                            </div>
                            <div class="messages__info">
                                <span class="messages__contact-name">
                                    <?= $start_user['login'] ?>
                                </span>
                                <div class="messages__preview">
                                </div>
                            </div>
                        </a>
                    </li>
                <? endif; ?>

                <?php if ($users) : ?>
                    <?php foreach ($users as $user) : ?>
                        <li class="messages__contacts-item">
                            <a
                                class="messages__contacts-tab tabs__item <?= $user['id'] === $user_id ? 'messages__contacts-tab--active tabs__item--active' : ''; ?>"
                                href="messages.php?user_id=<?= $user['id'] ?>"
                            >
                                <div class="messages__avatar-wrapper">
                                    <img class="messages__avatar" src="<?= $user['avatar'] ?>" alt="Аватар пользователя">
                                </div>
                                <div class="messages__info">
                                    <span class="messages__contact-name">
                                        <?= $user['login'] ?>
                                    </span>
                                    <div class="messages__preview">
                                        <p class="messages__preview-text">
                                            <?= $user['last_message']['id'] === $current_user['id'] ? 'Вы' : $user['last_message']['login'] ?>
                                            <?= ': ' . $user['last_message']['text'] ?>
                                        </p>
                                        <time class="messages__preview-time"
                                            datetime="<?= $user['last_message']['created_at'] ?>"
                                            title=""
                                        ><?= get_relative_date($user['last_message']['created_at']) ?></time>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <? endforeach; ?>
                <? endif; ?>
            </ul>
        </div>

        <?php if ($messages || $user_id) : ?>
            <div class="messages__chat">
                <?php if ($messages) : ?>
                    <div class="messages__chat-wrapper">
                        <ul class="messages__list tabs__content tabs__content--active">
                            <?php foreach ($messages as $message) : ?>
                                <li class="messages__item <?= $message['user_id'] === $current_user['id'] ? 'messages__item--my' : '' ?>">
                                    <div class="messages__info-wrapper">
                                        <div class="messages__item-avatar">
                                            <a class="messages__author-link" href="profile.php?user_id=<?= $message['user_id'] ?>">
                                                <img class="messages__avatar" src="<?= $message['avatar']; ?>" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="messages__item-info">
                                            <a class="messages__author" href="profile.php?user_id=<?= $message['user_id'] ?>">
                                                <?= $message['login'] ?>
                                            </a>
                                            <time class="messages__time"
                                                datetime="<?= $message['created_at'] ?>"
                                                title="<?= get_date_for_title($message['created_at']) ?>"
                                            ><?= get_relative_date($message['created_at']) ?></time>
                                        </div>
                                    </div>
                                    <p class="messages__text">
                                        <?= $message['text'] ?>
                                    </p>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                <? endif; ?>
                <div class="comments">
                    <form class="comments__form form" action="messages.php?user_id=<?= $user_id; ?>" method="post">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="<?= $current_user['avatar']; ?>" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section <?= $error ? 'form__input-section--error' : '' ?>">
                            <textarea
                                class="comments__textarea form__textarea form__input"
                                placeholder="Ваше сообщение"
                                name="comment"
                                value=""
                            ><?= $comment ?? ''; ?></textarea>
                            <label class="visually-hidden">Ваше сообщение</label>
                            <?php if ($error) : ?>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc"><?= $error ?></p>
                                </div>
                            <? endif ?>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                </div>
            </div>
        <? endif; ?>
    </section>

    <?php if (!$user_id && !$start_user) : ?>
        <p class="note">Выберите пользователя!</p>
    <? endif; ?>
</main>
