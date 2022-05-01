<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <?php $classname = is_null(get_parametr('type_id')) ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--ellipse filters__button--all <?= $classname ?>" href="/">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach($types as $type): ?>
                        <?php $classnames = (int)get_parametr('type_id') === $type['id'] ? 'filters__button--active' : ''; ?>
                        <li class="popular__filters-item filters__item">
                            <a
                                class="filters__button filters__button--<?= $type['class'] ?> button <?= $classnames; ?>"
                                href="/?type_id=<?= $type['id']; ?>"
                            >
                                <span class="visually-hidden"><?= $type['name']; ?></span>
                                <svg class="filters__icon" width="<?= $type['icon_width'] ?>" height="<?= $type['icon_height']; ?>">
                                    <use xlink:href="#icon-filter-<?= $type['class']; ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <? endforeach ?>
                </ul>
            </div>
        </div>

        <div class="popular__posts">
            <?php foreach($popular_posts as $post): ?>
                <?= include_template('post-card.php', [
                        'post' => $post,
                    ]);
                ?>
            <? endforeach; ?>
        </div>
    </div>
</section>
