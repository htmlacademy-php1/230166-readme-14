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
                        <a
                            class="sorting__link <?= $sorting === 'views' ? 'sorting__link--active' : '' ?>"
                            href="?type_id=<?= $type_id; ?>&sorting=views"
                        >
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a
                            class="sorting__link <?= $sorting === 'count_favs' ? 'sorting__link--active' : '' ?>"
                            href="?type_id=<?= $type_id; ?>&sorting=count_favs"
                        >
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a
                            class="sorting__link <?= $sorting === 'created_at' ? 'sorting__link--active' : '' ?>"
                            href="?type_id=<?= $type_id; ?>&sorting=created_at"
                        >
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
                        <?php $classname = $type_id ? '' : 'filters__button--active'; ?>
                        <a class="filters__button filters__button--ellipse filters__button--all <?= $classname ?>" href="popular.php?sorting=<?= $sorting ?>">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach($types as $type) : ?>
                    <?php $classnames = $type_id === $type['id'] ? 'filters__button--active' : ''; ?>
                    <li class="popular__filters-item filters__item">
                        <a
                            class="filters__button filters__button--<?= $type['class'] ?> button <?= $classnames; ?>"
                            href="?type_id=<?= $type['id']; ?>&sorting=<?= $sorting ?>"
                        >
                            <span class="visually-hidden"><?= $type['name']; ?></span>
                            <svg class="filters__icon" width="<?= $type['icon_width'] ?>" height="<?= $type['icon_height']; ?>">
                                <use xlink:href="#icon-filter-<?= $type['class']; ?>"></use>
                            </svg>
                        </a>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>

        <div class="popular__posts">
            <?php foreach($posts as $post) : ?>
                <?= include_template("popular-post.php", [
                        'post' => $post,
                    ]);
                ?>
            <?php endforeach; ?>
        </div>
        <div class="popular__page-links">
            <a
                class="popular__page-link popular__page-link--prev button button--gray"
                href="?type_id=<?= $type_id; ?>&page=<?= $current_page > 1 ? $current_page - 1 : 1; ?>&sorting=<?= $sorting ?>"
            >Предыдущая страница</a>
            <a
                class="popular__page-link popular__page-link--next button button--gray"
                href="?type_id=<?= $type_id; ?>&page=<?= $current_page < $pages_count ? $current_page + 1 : $pages_count; ?>&sorting=<?= $sorting ?>"
            >Следующая страница</a>
        </div>
    </div>
</section>
