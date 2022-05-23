<article class="popular__post post post-<?= $post['class'] ?>">
    <header class="post__header">
        <h2>
            <a href="post.php?post_id=<?= $post['id'] ?>"><?= esc($post['title']) ?></a>
        </h2>
    </header>
    <div class="post__main">
        <!--содержимое для поста-текста-->
        <?php if ($post['type_id'] === '1') : ?>
            <?= crop_text(esc($post['text']), $post['id'], 300) ?>

        <!--содержимое для поста-цитаты-->
        <?php elseif ($post['type_id'] === '2') : ?>
            <blockquote>
                <p><?= esc($post['quote']) ?></p>
                <cite><?= esc($post['caption']) ?></cite>
            </blockquote>

        <!--содержимое для поста-фото-->
        <?php elseif ($post['type_id'] === '3') : ?>
            <div class="post-photo__image-wrapper">
                <img src="<?= esc($post['photo_url']) ?>" alt="Фото от пользователя" width="360" height="240">
            </div>

        <!--содержимое для поста-видео-->
        <?php elseif ($post['type_id'] === '4') : ?>
            <div class="post-video__block">
                <div class="post-video__preview">
                    <?=embed_youtube_cover(esc($post['video_url'])); ?>
                    <!-- <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188"> -->
                </div>
                <a href="post.php?post_id=<?= $post['id'] ?>" class="post-video__play-big button">
                    <svg class="post-video__play-big-icon" width="14" height="14">
                        <use xlink:href="#icon-video-play-big"></use>
                    </svg>
                    <span class="visually-hidden">Запустить проигрыватель</span>
                </a>
            </div>

        <!--содержимое для поста-ссылки-->
        <?php elseif ($post['type_id'] === '5') : ?>
        <div class="post-link__wrapper">
            <a class="post-link__external" href="<?= esc($post['link_url']) ?>" title="Перейти по ссылке">
                <div class="post-link__info-wrapper">
                    <div class="post-link__icon-wrapper">
                        <img src="https://www.google.com/s2/favicons?domain=<?= esc($post['link_url']) ?>" alt="Иконка">
                    </div>
                    <div class="post-link__info">
                        <h3><?= esc($post['title']) ?></h3>
                    </div>
                </div>
                <span><?= esc($post['link_url']) ?></span>
            </a>
        </div>
        <? endif; ?>
    </div>
    <footer class="post__footer">
        <div class="post__author">
            <a class="post__author-link" href="profile.php?user_id=<?= $post['user_id'] ?>" title="<?= $post['login'] ?>">
                <div class="post__avatar-wrapper">
                    <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя">
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
                    <span>
                        <?= $post['count_favs']; ?>
                    </span>
                    <span class="visually-hidden">количество лайков</span>
                </a>
                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span><?= $post['count_comments'] ?></span>
                    <span class="visually-hidden">количество комментариев</span>
                </a>
            </div>
        </div>
    </footer>
</article>
