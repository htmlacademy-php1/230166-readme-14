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
                        <?php $classname = !filter_input(INPUT_GET, 'type_id') ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--ellipse filters__button--all <?= $classname ?>" href="/">
                            <span>Все</span>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <?php $classname = filter_input(INPUT_GET, 'type_id') === '3' ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--photo button <?= $classname ?>" href="/?type_id=3">
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <?php $classname = filter_input(INPUT_GET, 'type_id') === '4' ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--video button <?= $classname ?>" href="/?type_id=4">
                            <span class="visually-hidden">Видео</span>
                            <svg class="filters__icon" width="24" height="16">
                                <use xlink:href="#icon-filter-video"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <?php $classname = filter_input(INPUT_GET, 'type_id') === '1' ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--text button <?= $classname ?>" href="/?type_id=1">
                            <span class="visually-hidden">Текст</span>
                            <svg class="filters__icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <?php $classname = filter_input(INPUT_GET, 'type_id') === '2' ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--quote button <?= $classname ?>" href="/?type_id=2">
                            <span class="visually-hidden">Цитата</span>
                            <svg class="filters__icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <?php $classname = filter_input(INPUT_GET, 'type_id') === '5' ? 'filters__button--active' : ''; ?>
                        <a class="filters__button filters__button--link button <?= $classname ?>" href="/?type_id=5">
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="filters__icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="popular__posts">
            <?php foreach($popular_posts as $post): ?>
                <?= include_template('post_card.php', [
                        'post' => $post,
                        'link' => $link
                    ]);
                ?>
            <? endforeach; ?>
        </div>
    </div>
</section>
