<article class="popular__post post <?= $post['class'] ?>">
    <header class="post__header">
        <h2>
            <a href="/post.php?post_id=<?= $post['id'] ?>"><?= esc($post['title']) ?></a>
        </h2>
    </header>
    <div class="post__main">
        <!--содержимое для поста-текста-->
        <?php if ($post['type_id'] === '1'): ?>
            <?= crop_text(esc($post['text']), POST_LIMIT) ?>

        <!--содержимое для поста-цитаты-->
        <?php elseif ($post['type_id'] === '2'): ?>
            <blockquote>
                <p><?= esc($post['text']) ?></p>
                <cite><?= esc($post['caption']) ?></cite>
            </blockquote>

        <!--содержимое для поста-фото-->
        <?php elseif ($post['type_id'] === '3'): ?>
            <div class="post-photo__image-wrapper">
                <img src="img/<?= $post['img_url'] ?>" alt="Фото от пользователя" width="360" height="240">
            </div>

        <!--содержимое для поста-видео-->
        <?php elseif ($post['type_id'] === '4'): ?>
            <div class="post-video__block">
                <div class="post-video__preview">
                    <?=embed_youtube_cover($post['youtube_url']); ?>
                    <!-- <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188"> -->
                </div>
                <a href="post-details.html" class="post-video__play-big button">
                    <svg class="post-video__play-big-icon" width="14" height="14">
                        <use xlink:href="#icon-video-play-big"></use>
                    </svg>
                    <span class="visually-hidden">Запустить проигрыватель</span>
                </a>
            </div>

        <!--содержимое для поста-ссылки-->
        <?php elseif ($post['type_id'] === '5'): ?>
            <div class="post-link__wrapper">
                <a class="post-link__external" href="http://<?= $post['link'] ?>" title="Перейти по ссылке">
                    <div class="post-link__info-wrapper">
                        <div class="post-link__icon-wrapper">
                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                        </div>
                        <div class="post-link__info">
                            <h3><?= esc($post['title']) ?></h3>
                        </div>
                    </div>
                    <span><?= esc($post['link']) ?></span>
                </a>
            </div>
        <? endif; ?>
    </div>
    <footer class="post__footer">
        <div class="post__author">
            <a class="post__author-link" href="#" title="<?= $post['login'] ?>">
                <div class="post__avatar-wrapper">
                    <img class="post__author-avatar" src="img/<?= $post['avatar'] ?>" alt="Аватар пользователя">
                </div>
                <div class="post__info">
                    <b class="post__author-name"><?= $post['login'] ?></b>
                    <time class="post__time"
                        datetime="<?= $post['created_at'] ?>"
                        title="<?= get_date_for_title($post['created_at']) ?>"
                    ><?= get_relative_date($post['created_at']) ?></time>
                </div>
            </a>
        </div>
        <div class="post__indicators">
            <div class="post__buttons">
                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                    <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                    </svg>
                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                    </svg>
                    <span><?= get_count_favs($link, $post['id']) ?></span>
                    <span class="visually-hidden">количество лайков</span>
                </a>
                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span><?= get_count_comments($link, $post['id']) ?></span>
                    <span class="visually-hidden">количество комментариев</span>
                </a>
            </div>
        </div>
    </footer>
</article>
