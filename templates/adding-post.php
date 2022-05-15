<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php foreach($types as $type) : ?>
                            <?php $classnames = $type_id === (int)$type['id'] ? 'filters__button--active tabs__item tabs__item--active' : ''; ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a
                                    class="adding-post__tabs-link filters__button filters__button--<?= $type['class']; ?> <?= $classnames; ?> button"
                                    href="add.php?type_id=<?= $type['id']; ?>"
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
                    <?php foreach($types as $type) : ?>
                        <?php if ((int)$type['id'] === $type_id) : ?>
                            <?= include_template("adding-post-{$type['class']}.php", [
                                    'type' => $type,
                                    'errors' => $errors,
                                    'post' => $post,
                                    'tags' => $tags
                                ]);
                            ?>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
