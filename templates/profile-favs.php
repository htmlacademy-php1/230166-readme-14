<ul class="profile__likes-list">
    <?php foreach($favs as $fav) : ?>
        <li class="post-mini post-mini--photo post user">
            <div class="post-mini__user-info user__info">
                <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="profile.php?user_id=<?= $fav['user_id'] ?>">
                        <img class="post-mini__picture user__picture" src="<?= $fav['avatar'] ?>" alt="Аватар пользователя">
                    </a>
                </div>
                <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="profile.php?user_id=<?= $fav['user_id'] ?>">
                        <span><?= $fav['login'] ?></span>
                    </a>
                    <div class="post-mini__action">
                        <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                        <time class="post-mini__time user__additional"
                            datetime="<?= $fav['created_at']; ?>"
                            title="<?= get_date_for_title($fav['created_at']); ?>"
                        ><?= get_relative_date($fav['created_at']); ?></time>
                    </div>
                </div>
            </div>
            <div class="post-mini__preview">
                <?php if ((int)$fav['type_id'] === 1) : ?>
                    <a class="post-mini__link" href="post.php?post_id=<?= $fav['post_id'] ?>" title="Перейти на публикацию">
                        <span class="visually-hidden">Текст</span>
                        <svg class="post-mini__preview-icon" width="20" height="21">
                            <use xlink:href="#icon-filter-text"></use>
                        </svg>
                    </a>
                <? elseif ((int)$fav['type_id'] === 2) : ?>
                    <a class="post-mini__link" href="post.php?post_id=<?= $fav['post_id'] ?>" title="Перейти на публикацию">
                        <span class="visually-hidden">Цитата</span>
                        <svg class="post-mini__preview-icon" width="21" height="20">
                            <use xlink:href="#icon-filter-quote"></use>
                        </svg>
                    </a>
                <? elseif ((int)$fav['type_id'] === 3) : ?>
                    <a class="post-mini__link" href="post.php?post_id=<?= $fav['post_id'] ?>" title="Перейти на публикацию">
                        <div class="post-mini__image-wrapper">
                            <img class="post-mini__image" src="<?= $fav['photo_url']; ?>" width="109" height="109" alt="Превью публикации">
                        </div>
                        <span class="visually-hidden">Фото</span>
                    </a>
                <? elseif ((int)$fav['type_id'] === 4) : ?>
                    <a class="post-mini__link" href="post.php?post_id=<?= $fav['post_id'] ?>" title="Перейти на публикацию">
                        <div class="post-mini__image-wrapper">
                            <?php embed_youtube_cover(esc($fav['photo_url'])); ?>
                            <span class="post-mini__play-big">
                                <svg class="post-mini__play-big-icon" width="12" height="13">
                                    <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                            </span>
                        </div>
                        <span class="visually-hidden">Видео</span>
                    </a>
                <? elseif ((int)$fav['type_id'] === 5) : ?>
                    <a class="post-mini__link" href="post.php?post_id=<?= $fav['post_id'] ?>" title="Перейти на публикацию">
                        <span class="visually-hidden">Ссылка</span>
                        <svg class="post-mini__preview-icon" width="21" height="18">
                            <use xlink:href="#icon-filter-link"></use>
                        </svg>
                    </a>
                <? endif; ?>
            </div>
        </li>
    <? endforeach; ?>
</ul>
