<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php foreach($posts as $post) : ?>
                    <article class="feed__post post post-photo">
                        <header class="post__header post__author">
                            <a class="post__author-link" href="profile.php?user_id=<?= $post['user_id'] ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <img class="post__author-avatar" src="<?= $post['avatar']; ?>" alt="Аватар пользователя" width="60" height="60">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= $post['login']; ?></b>
                                    <time class="post__time"
                                        datetime="<?= $post['created_at'] ?>"
                                        title="<?= get_date_for_title($post['created_at']) ?>"
                                    ><?= get_relative_date($post['created_at']) ?></time>
                                </div>
                            </a>
                        </header>
                        <div class="post__main">
                            <h2><a href="post.php?post_id=<?= $post['id'] ?>"><?= $post['title']; ?></a></h2>

                            <?php if ($post['type_id'] === '1') : ?>
                                <?= include_template('post-text.php', [
                                        'text' => esc($post['text'])
                                    ]);
                                ?>
                            <?php elseif($post['type_id'] === '2') : ?>
                                <?= include_template('post-quote.php', [
                                        'quote' => $post['quote'],
                                        'author' => $post['caption']
                                    ]);
                                ?>
                            <?php elseif ($post['type_id'] === '3') : ?>
                                <?= include_template('post-photo.php', [
                                        'photo_url' => $post['photo_url'],
                                    ]);
                                ?>
                            <?php elseif ($post['type_id'] === '4') : ?>
                                <?= include_template('post-video.php', [
                                        'video_url' => $post['video_url'],
                                    ]);
                                ?>
                            <?php elseif ($post['type_id'] === '5') : ?>
                                <?= include_template('post-link.php', [
                                        'link_url' => $post['link_url'],
                                        'title' => $post['title'],
                                    ]);
                                ?>
                            <?php endif; ?>
                        </div>
                        <footer class="post__footer post__indicators">
                            <div class="post__buttons">
                            <a
                                class="post__indicator post__indicator--likes button <?= $post['is_fav'] ? 'post__indicator--likes-active' : ''; ?>"
                                href="add-fav.php?post_id=<?= esc($post['id']) ?>"
                                title="Лайк"
                            >
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?= $post['count_favs']; ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= $post['count_comments'] ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="add-repost.php?post_id=<?= $post['id'] ?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span>5</span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                            </div>
                        </footer>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <ul class="feed__filters filters">
                <?php $classname = !$type_id ? 'filters__button--active' : ''; ?>
                <li class="feed__filters-item filters__item">
                    <a class="filters__button <?= $classname; ?>" href="feed.php">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach($types as $type) : ?>
                <?php $classname = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_NUMBER_INT) === $type['id'] ? 'filters__button--active' : ''; ?>
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--photo button <?= $classname; ?>" href="?type_id=<?= $type['id']; ?>">
                        <span class="visually-hidden"><?= $type['name']; ?></span>
                        <svg class="filters__icon" width="<?= $type['icon_width'] ?>" height="<?= $type['icon_height']; ?>">
                            <use xlink:href="#icon-filter-<?= $type['class']; ?>"></use>
                        </svg>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
