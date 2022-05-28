<ul class="profile__subscriptions-list">
    <?php foreach($subscribers as $subscriber) : ?>
        <li class="post-mini post-mini--photo post user">
            <div class="post-mini__user-info user__info">
                <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="profile.php?user_id=<?= $subscriber['id']; ?>">
                        <img
                            class="post-mini__picture user__picture"
                            src="<?= $subscriber['avatar']; ?>"
                            alt="Аватар пользователя"
                        >
                    </a>
                </div>
                <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="profile.php?user_id=<?= $subscriber['id'] ?>">
                        <span><?= $subscriber['login']; ?></span>
                    </a>
                    <time class="post-mini__time user__additional"
                        datetime="<?= $subscriber['created_at']; ?>"
                        title="<?= get_date_for_title($subscriber['created_at']); ?>"
                    ><?= get_relative_date($subscriber['created_at']); ?></time>
                </div>
            </div>
            <div class="post-mini__rating user__rating">
                <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                    <span class="post-mini__rating-amount user__rating-amount">
                        <?= $subscriber['count_posts']; ?>
                    </span>
                    <span class="post-mini__rating-text user__rating-text">
                        <?= get_noun_plural_form($subscriber['count_posts'], 'публикация', 'публикации', 'публикаций'); ?>
                    </span>
                </p>
                <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                    <span class="post-mini__rating-amount user__rating-amount">
                        <?= $subscriber['count_subscribers']; ?>
                    </span>
                    <span class="post-mini__rating-text user__rating-text">
                        <?= get_noun_plural_form($subscriber['count_subscribers'], 'подписчик', 'подписчика', 'подписчиков'); ?>
                    </span>
                </p>
            </div>
            <div class="post-mini__user-buttons user__buttons">
                <a
                    class="post-mini__user-button user__button user__button--subscription button button--main"
                    href="add-subscribe.php?user_id=<?= $subscriber['id']; ?>"
                >
                    <?= $subscriber['is_subscribe'] ? 'Отписаться' : 'Подписаться'; ?>
                </a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
