<article class="popular__post post <?= $post['type'] ?>">
    <header class="post__header">
        <h2><?= esc($post['title']) ?></h2>
    </header>
    <div class="post__main">
        <!--содержимое для поста-цитаты-->
        <?php if($post['type'] === 'post-quote'): ?>
        <blockquote>
            <p><?= esc($post['content']) ?></p>
            <cite>Неизвестный Автор</cite>
        </blockquote>

        <!--содержимое для поста-ссылки-->
        <?php elseif ($post['type'] === 'post-link'): ?>
        <div class="post-link__wrapper">
            <a class="post-link__external" href="http://<?= $post['content'] ?>" title="Перейти по ссылке">
                <div class="post-link__info-wrapper">
                    <div class="post-link__icon-wrapper">
                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                    </div>
                    <div class="post-link__info">
                        <h3><?= esc($post['title']) ?></h3>
                    </div>
                </div>
                <span><?= esc($post['content']) ?></span>
            </a>
        </div>

        <!--содержимое для поста-фото-->
        <?php elseif ($post['type'] === 'post-photo'): ?>
        <div class="post-photo__image-wrapper">
            <img src="img/<?= $post['content'] ?>" alt="Фото от пользователя" width="360" height="240">
        </div>

        <!--содержимое для поста-видео-->
        <?php elseif ($post['type'] === 'post-video'): ?>
        <div class="post-video__block">
            <div class="post-video__preview">
                <?=embed_youtube_cover($post['content']); ?>
                <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188">
            </div>
            <a href="post-details.html" class="post-video__play-big button">
                <svg class="post-video__play-big-icon" width="14" height="14">
                    <use xlink:href="#icon-video-play-big"></use>
                </svg>
                <span class="visually-hidden">Запустить проигрыватель</span>
            </a>
        </div>

        <!--содержимое для поста-текста-->
        <?php elseif ($post['type'] === 'post-text'): ?>
            <?= crop_text(esc($post['content']), POST_LIMIT) ?>
        <? endif; ?>
    </div>
    <footer class="post__footer">
        <div class="post__author">
            <a class="post__author-link" href="#" title="<?= $post['user_name'] ?>">
                <div class="post__avatar-wrapper">
                    <img class="post__author-avatar" src="img/<?= $post['avatar'] ?>" alt="Аватар пользователя">
                </div>
                <div class="post__info">
                    <b class="post__author-name"><?= $post['user_name'] ?></b>
                    <time class="post__time" datetime="<?= $post['date'] ?>" title="<?= get_date_for_title($post['date']) ?>"><?= get_relative_date($post['date']) ?></time>
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
                    <span>0</span>
                    <span class="visually-hidden">количество лайков</span>
                </a>
                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span>0</span>
                    <span class="visually-hidden">количество комментариев</span>
                </a>
            </div>
        </div>
    </footer>
</article>
