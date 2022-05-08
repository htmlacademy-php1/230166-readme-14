<main class="content">
    <div class="content__main-col">
        <header class="content__header">
            <h2 class="content__header-text">Ошибка <?= http_response_code() ?></h2>
        </header>
        <article class="gif-list">
            <p class="error"><?= $error; ?></p>
        </article>
    </div>
</main>
