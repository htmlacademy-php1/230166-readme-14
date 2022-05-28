<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="<?= esc($user['avatar']) ?>" alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= esc($user['login']) ?></span>
                        <time
                            class="profile__user-time user__time"
                            datetime="<?= esc($user['created_at']) ?>"
                            title="<?= get_date_for_title(esc($user['created_at'])) ?>"
                        ><?= get_relative_date(esc($user['created_at'])) ?></time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount">
                            <?= esc($user['count_posts']); ?>
                        </span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(esc($user['count_posts']), 'публикация', 'публикации', 'публикаций'); ?>
                        </span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount">
                            <?= esc($user['count_subscribes']); ?>
                        </span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(esc($user['count_subscribes']), 'подписчик', 'подписчика', 'подписчиков'); ?>
                        </span>
                    </p>
                </div>
                <?php if ((int)$user['id'] !== (int)$current_user['id']) : ?>
                    <div class="profile__user-buttons user__buttons">
                        <a
                            class="profile__user-button user__button user__button--subscription button button--main"
                            href="add-subscribe.php?user_id=<?= esc($user['id']); ?>"
                        ><?= ($user['is_subscribe']) ? 'Отписаться' : 'Подписаться'; ?></a>
                        <a
                            class="profile__user-button user__button user__button--writing button button--green"
                            href="messages.php?user_id=<?= esc($user['id']); ?>"
                        >Сообщение</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a
                                class="profile__tabs-link filters__button tabs__item button <?= ($tab === 'posts') ? 'filters__button--active tabs__item--active' : ''; ?>"
                                href="profile.php?tab=posts&user_id=<?= esc($user['id']) ?>"
                            >Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a
                                class="profile__tabs-link filters__button tabs__item button <?= ($tab === 'favs') ? 'filters__button--active tabs__item--active' : ''; ?>"
                                href="profile.php?tab=favs&user_id=<?= esc($user['id'])?>"
                            >Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a
                                class="profile__tabs-link filters__button tabs__item button <?= ($tab === 'subscribers') ? 'filters__button--active tabs__item--active' : ''; ?>"
                                href="profile.php?tab=subscribers&user_id=<?= esc($user['id']) ?>"
                            >Подписки</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <section class="profile__posts tabs__content <?= $tab === 'posts' ? 'tabs__content--active' : ''; ?>">
                        <h2 class="visually-hidden">Публикации</h2>
                        <?php if ($posts) : ?>
                            <?php foreach($posts as $post) : ?>
                                <?= include_template("profile-post.php", [
                                        'post' => $post,
                                        'user_id' => $user_id,
                                        'comment_error_id' => $comment_error_id,
                                        'comment_error_text' => $comment_error_text,
                                        'current_user' => $current_user
                                    ]);
                                ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            Пусто!
                        <?php endif; ?>
                    </section>

                    <section class="profile__likes tabs__content <?= $tab === 'favs' ? 'tabs__content--active' : ''; ?>">
                        <h2 class="visually-hidden">Лайки</h2>
                        <?php if ($favs) : ?>
                            <?= include_template("profile-favs.php", [
                                    'favs' => $favs,
                                    'user_id' => $user_id
                                ]);
                            ?>
                        <?php else : ?>
                            Пусто!
                        <?php endif; ?>
                    </section>

                    <section class="profile__subscriptions tabs__content <?= $tab === 'subscribers' ? 'tabs__content--active' : ''; ?>">
                        <h2 class="visually-hidden">Подписки</h2>
                        <?php if ($subscribers) : ?>
                            <?= include_template("profile-subscribers.php", [
                                    'subscribers' => $subscribers
                                ]);
                            ?>
                        <?php else : ?>
                            Пусто!
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
