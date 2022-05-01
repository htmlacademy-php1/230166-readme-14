<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= esc($post['title']); ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper <?= $post['type_class']; ?>">
                <div class="post-details__main-block post post--details">
                    <?php if ($post['type_id'] === '1'): ?>
                        <?= include_template('post-text.php', [
                                'text' => $post['text']
                            ]);
                        ?>
                    <?php elseif($post['type_id'] === '2'): ?>
                        <?= include_template('post-quote.php', [
                                'quote' => $post['quote'],
                                'author' => $post['caption']
                            ]);
                        ?>
                    <?php elseif ($post['type_id'] === '3'): ?>
                        <?= include_template('post-photo.php', [
                                'img_url' => $post['img_url'],
                            ]);
                        ?>
                    <?php elseif ($post['type_id'] === '4'): ?>
                        <?= include_template('post-video.php', [
                                'youtube_url' => $post['youtube_url'],
                            ]);
                        ?>
                    <?php elseif ($post['type_id'] === '5'): ?>
                        <?= include_template('post-link.php', [
                                'link' => $post['link'],
                                'title' => $post['title'],
                            ]);
                        ?>
                    <? endif; ?>
                <div class="post__indicators">
                    <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                        <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                        </svg>
                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                            <use xlink:href="#icon-heart-active"></use>
                        </svg>
                        <span><?= $count_favs; ?></span>
                        <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                        <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-comment"></use>
                        </svg>
                        <span><?= $count_comments; ?></span>
                        <span class="visually-hidden">количество комментариев</span>
                    </a>
                    <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                        <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-repost"></use>
                        </svg>
                        <span>5</span>
                        <span class="visually-hidden">количество репостов</span>
                    </a>
                    </div>
                    <span class="post__view">
                        <?= $post['views'] . " " . get_noun_plural_form($post['views'], 'просмотр', 'просмотра', 'просмотров'); ?>
                    </span>
                </div>
                <ul class="post__tags">
                    <?php foreach ($tags as $tag): ?>
                        <li><a href="#"><?= $tag['text'] ?></a></li>
                    <? endforeach ?>
                </ul>
                <div class="comments">
                    <form class="comments__form form" action="#" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section form__input-section--error">
                            <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий"></textarea>
                            <label class="visually-hidden">Ваш комментарий</label>
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка валидации</h3>
                                <p class="form__error-desc">Это поле обязательно к заполнению</p>
                            </div>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>

                    <div class="comments__list-wrapper">
                        <?php if ($comments): ?>
                            <ul class="comments__list">
                                <?php foreach($comments_start as $comment): ?>
                                    <li class="comments__item user">
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="comments__picture" src="img/<?= $comment['avatar']; ?>" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="#">
                                                    <span><?= $comment['author']; ?></span>
                                                </a>
                                                <time class="comments__time"
                                                    datetime="<?= $comment['created_at']; ?>"
                                                    title="<?= get_date_for_title($comment['created_at']); ?>"
                                                ><?= get_relative_date($comment['created_at']); ?></time>
                                            </div>
                                            <p class="comments__text">
                                                <?= $comment['text']; ?>
                                            </p>
                                        </div>
                                    </li>
                                <? endforeach; ?>
                                <?php if($is_show_comments): ?>
                                    <?php foreach($comments_more as $comment): ?>
                                        <li class="comments__item user">
                                            <div class="comments__avatar">
                                                <a class="user__avatar-link" href="#">
                                                    <img class="comments__picture" src="img/<?= $comment['avatar']; ?>" alt="Аватар пользователя">
                                                </a>
                                            </div>
                                            <div class="comments__info">
                                                <div class="comments__name-wrapper">
                                                    <a class="comments__user-name" href="#">
                                                        <span><?= $comment['author']; ?></span>
                                                    </a>
                                                    <time class="comments__time"
                                                        datetime="<?= $comment['created_at']; ?>"
                                                        title="<?= get_date_for_title($comment['created_at']); ?>"
                                                    ><?= get_relative_date($comment['created_at']); ?></time>
                                                </div>
                                                <p class="comments__text">
                                                    <?= $comment['text'] ?>
                                                </p>
                                            </div>
                                        </li>
                                    <? endforeach; ?>
                                <? endif; ?>
                            </ul>
                            <?php if ($count_comments > 2 && !$is_show_comments): ?>
                                <a class="comments__more-link" href="/post.php?post_id=<?= $post['post_id']; ?>&is_show_comments=true">
                                    <span>Показать все комментарии</span>
                                    <sup class="comments__amount"><?= $count_comments; ?></sup>
                                </a>
                            <? endif; ?>
                        <? endif; ?>
                    </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="#">
                                <img class="post-details__picture user__picture" src="img/<?= $post['avatar']; ?>" alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="#">
                                <span><?= esc($post['login']); ?></span>
                            </a>
                            <time class="post-details__time user__time"
                                datetime="<?= $post['user_created_at'] ?>"
                                title="<?= get_date_for_title($post['user_created_at']); ?>"
                            ><?= get_relative_date($post['user_created_at']); ?></time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?= $count_subscribes; ?></span>
                            <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($count_subscribes, 'подписчик', 'подписчика', 'подписчиков'); ?></span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount"><?= $count_posts; ?></span>
                            <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($count_posts, 'публикация', 'публикации', 'публикаций'); ?></span>
                        </p>
                    </div>
                    <div class="post-details__user-buttons user__buttons">
                        <button class="user__button user__button--subscription button button--main" type="button">Подписаться</button>
                        <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
