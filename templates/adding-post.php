<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php foreach($types as $type): ?>
                            <?php $classnames = (is_null(get_parametr('type_id')) && (int)$type['id'] === 1) || (int)get_parametr('type_id') === (int)$type['id'] ? 'filters__button--active tabs__item tabs__item--active' : ''; ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a
                                    class="adding-post__tabs-link filters__button filters__button--<?= $type['class']; ?> <?= $classnames; ?> button"
                                    href="/add.php?type_id=<?= $type['id']; ?>"
                                >
                                    <svg class="filters__icon" width="<?= $type['icon_width'] ?>" height="<?= $type['icon_height']; ?>">
                                        <use xlink:href="#icon-filter-<?= $type['class']; ?>"></use>
                                    </svg>
                                    <span><?= $type['name']; ?></span>
                                </a>
                            </li>
                        <? endforeach ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <?php if(is_null(get_parametr('type_id')) || (int)get_parametr('type_id') === 1): ?>
                        <?= include_template('adding-post-text.php', [
                                'errors' => $errors,
                            ]);
                        ?>
                    <?php elseif ((int)get_parametr('type_id') === 2): ?>
                        <?= include_template('adding-post-quote.php', [
                                'errors' => $errors,
                            ]);
                        ?>
                    <?php elseif ((int)get_parametr('type_id') === 3): ?>
                        <?= include_template('adding-post-photo.php', [
                                'errors' => $errors,
                            ]);
                        ?>
                    <?php elseif ((int)get_parametr('type_id') === 4): ?>
                        <?= include_template('adding-post-video.php', [
                                'errors' => $errors,
                            ]);
                        ?>
                    <?php elseif ((int)get_parametr('type_id') === 5): ?>
                        <?= include_template('adding-post-link.php', [
                                'errors' => $errors,
                            ]);
                        ?>
                    <? endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
