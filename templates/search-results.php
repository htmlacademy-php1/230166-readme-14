<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= $search ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">
                    <?php foreach($posts as $post) : ?>
                    <article class="feed__post post post-photo">
                        <header class="post__header post__author">
                            <a class="post__author-link" href="#" title="Автор">
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

                            <?php if ((int)$post['type_id'] === 1) : ?>
                                <?= include_template('post-text.php', [
                                        'text' => esc($post['text'])
                                    ]);
                                ?>
                            <?php elseif((int)$post['type_id'] === 2) : ?>
                                <?= include_template('post-quote.php', [
                                        'quote' => $post['quote'],
                                        'author' => $post['caption']
                                    ]);
                                ?>
                            <?php elseif ((int)$post['type_id'] === 3) : ?>
                                <?= include_template('post-photo.php', [
                                        'photo_url' => $post['photo_url'],
                                    ]);
                                ?>
                            <?php elseif ((int)$post['type_id'] === 4) : ?>
                                <?= include_template('post-video.php', [
                                        'video_url' => $post['video_url'],
                                    ]);
                                ?>
                            <?php elseif ((int)$post['type_id'] === 5) : ?>
                                <?= include_template('post-link.php', [
                                        'link_url' => $post['link_url'],
                                        'title' => $post['title'],
                                    ]);
                                ?>
                            <?php endif; ?>
                        </div>
                        <footer class="post__footer post__indicators">
                            <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
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
                            <a
                                class="post__indicator post__indicator--repost button"
                                href="add-repost.php?post_id=<?= $post['id'] ?>"
                                 title="Репост"
                            >
                                <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= $post['repost_count'] ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                            </div>
                        </footer>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
