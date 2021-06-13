<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= htmlspecialchars($search_query_text) ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <?php if(empty($founded_posts)): ?>
                <div class="search__no-results container">
                    <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                    <p class="search__no-results-desc">
                        Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
                    </p>
                    <div class="search__links">
                        <a class="search__popular-link button button--main" href="popular.php">Популярное</a>
                        <a class="search__back-link" href="#" onclick="history.go(-1); return false;">Вернуться назад</a>
                    </div>
                </div>
            <?php else: ?>
            <div class="container">
                <div class="search__content">
                    <?php foreach ($founded_posts as $key => $post): ?>
                        <article class="search__post post <?= $post['type'] ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="#" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="<?= htmlspecialchars($post['avatar']) ?>" alt="Аватар пользователя" width="60" height="60">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= $post['user_name'] ? htmlspecialchars($post['user_name']) : htmlspecialchars($post['login']) ?></b>
                                        <span class="post__time"><?= format_publication_date($post['date_add']) ?></span>
                                    </div>
                                </a>
                            </header>
                            <div class="post__main">
                                <?php if ($post['type'] == 'post-photo'): ?>
                                    <h2><a href="<?= get_url_post($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                                    <div class="post-photo__image-wrapper">
                                        <img src="<?= htmlspecialchars($post['content']) ?>" alt="Фото от пользователя" width="760" height="396">
                                    </div>
                                <?php elseif($post['type'] === 'post-text'): ?>
                                    <h2><a href="<?= get_url_post($post['id']) ?>"><?= $post['title'] ?></a></h2>
                                    <?= cut_text(htmlspecialchars($post['content'])) ?>
                                <?php elseif($post['type'] === 'post-video'): ?>
                                    <div class="post-video__block">
                                        <div class="post-video__preview">
                                            <?=embed_youtube_cover(htmlspecialchars($post['content']), 760, 393); ?>
                                        </div>
                                        <div class="post-video__control">
                                            <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                                            <div class="post-video__scale-wrapper">
                                                <div class="post-video__scale">
                                                    <div class="post-video__bar">
                                                        <div class="post-video__toggle"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                                        </div>
                                        <button class="post-video__play-big button" type="button">
                                            <svg class="post-video__play-big-icon" width="27" height="28">
                                                <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                            <span class="visually-hidden">Запустить проигрыватель</span>
                                        </button>
                                    </div>
                                <?php elseif($post['type'] === 'post-quote'): ?>
                                    <blockquote>
                                        <p>
                                            <?= htmlspecialchars($post['content']) ?>
                                        </p>
                                        <cite><?= htmlspecialchars($post['author']) ?></cite>
                                    </blockquote>
                                <?php elseif($post['type'] === 'post-link'): ?>
                                    <div class="post-link__wrapper">
                                        <a class="post-link__external" href="<?= htmlspecialchars($post['content']) ?>" title="Перейти по ссылке">
                                            <div class="post-link__icon-wrapper">
                                                <img src="https://www.google.com/s2/favicons?domain=<?= get_domain($post['content']) ?>" alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3><?= htmlspecialchars($post['title']) ?></h3>
                                                <span><?= htmlspecialchars($post['content']) ?></span>
                                            </div>
                                            <svg class="post-link__arrow" width="11" height="16">
                                                <use xlink:href="#icon-arrow-right-ad"></use>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= $post['likes_count'] ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= $post['comments_count'] ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>

<!--                    <article class="search__post post post-photo">-->
<!--                        <header class="post__header post__author">-->
<!--                            <a class="post__author-link" href="#" title="Автор">-->
<!--                                <div class="post__avatar-wrapper">-->
<!--                                    <img class="post__author-avatar" src="img/userpic-elvira.jpg" alt="Аватар пользователя" width="60" height="60">-->
<!--                                </div>-->
<!--                                <div class="post__info">-->
<!--                                    <b class="post__author-name">Эльвира Хайпулинова</b>-->
<!--                                    <span class="post__time">15 минут назад</span>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </header>-->
<!--                        <div class="post__main">-->
<!--                            <h2><a href="#">Наконец, обработала фотки!</a></h2>-->
<!--                            <div class="post-photo__image-wrapper">-->
<!--                                <img src="img/rock.jpg" alt="Фото от пользователя" width="760" height="396">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <footer class="post__footer post__indicators">-->
<!--                            <div class="post__buttons">-->
<!--                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">-->
<!--                                    <svg class="post__indicator-icon" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart"></use>-->
<!--                                    </svg>-->
<!--                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart-active"></use>-->
<!--                                    </svg>-->
<!--                                    <span>250</span>-->
<!--                                    <span class="visually-hidden">количество лайков</span>-->
<!--                                </a>-->
<!--                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">-->
<!--                                    <svg class="post__indicator-icon" width="19" height="17">-->
<!--                                        <use xlink:href="#icon-comment"></use>-->
<!--                                    </svg>-->
<!--                                    <span>25</span>-->
<!--                                    <span class="visually-hidden">количество комментариев</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </footer>-->
<!--                    </article>-->
<!---->
<!--                    <article class="search__post post post-text">-->
<!--                        <header class="post__header post__author">-->
<!--                            <a class="post__author-link" href="#" title="Автор">-->
<!--                                <div class="post__avatar-wrapper">-->
<!--                                    <img class="post__author-avatar" src="img/userpic-tanya.jpg" alt="Аватар пользователя">-->
<!--                                </div>-->
<!--                                <div class="post__info">-->
<!--                                    <b class="post__author-name">Таня Фирсова</b>-->
<!--                                    <span class="post__time">25 минут назад</span>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </header>-->
<!--                        <div class="post__main">-->
<!--                            <h2><a href="#">Полезный пост про Байкал</a></h2>-->
<!--                            <p>-->
<!--                                Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.-->
<!--                            </p>-->
<!--                            <a class="post-text__more-link" href="#">Читать далее</a>-->
<!--                        </div>-->
<!--                        <footer class="post__footer post__indicators">-->
<!--                            <div class="post__buttons">-->
<!--                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">-->
<!--                                    <svg class="post__indicator-icon" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart"></use>-->
<!--                                    </svg>-->
<!--                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart-active"></use>-->
<!--                                    </svg>-->
<!--                                    <span>250</span>-->
<!--                                    <span class="visually-hidden">количество лайков</span>-->
<!--                                </a>-->
<!--                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">-->
<!--                                    <svg class="post__indicator-icon" width="19" height="17">-->
<!--                                        <use xlink:href="#icon-comment"></use>-->
<!--                                    </svg>-->
<!--                                    <span>25</span>-->
<!--                                    <span class="visually-hidden">количество комментариев</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </footer>-->
<!--                    </article>-->
<!---->
<!--                    <article class="search__post post post-video">-->
<!--                        <header class="post__header post__author">-->
<!--                            <a class="post__author-link" href="#" title="Автор">-->
<!--                                <div class="post__avatar-wrapper">-->
<!--                                    <img class="post__author-avatar" src="img/userpic-petro.jpg" alt="Аватар пользователя">-->
<!--                                </div>-->
<!--                                <div class="post__info">-->
<!--                                    <b class="post__author-name">Петр Демин</b>-->
<!--                                    <span class="post__time">5 часов назад</span>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </header>-->
<!--                        <div class="post__main">-->
<!--                            <div class="post-video__block">-->
<!--                                <div class="post-video__preview">-->
<!--                                    <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">-->
<!--                                </div>-->
<!--                                <div class="post-video__control">-->
<!--                                    <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>-->
<!--                                    <div class="post-video__scale-wrapper">-->
<!--                                        <div class="post-video__scale">-->
<!--                                            <div class="post-video__bar">-->
<!--                                                <div class="post-video__toggle"></div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>-->
<!--                                </div>-->
<!--                                <button class="post-video__play-big button" type="button">-->
<!--                                    <svg class="post-video__play-big-icon" width="27" height="28">-->
<!--                                        <use xlink:href="#icon-video-play-big"></use>-->
<!--                                    </svg>-->
<!--                                    <span class="visually-hidden">Запустить проигрыватель</span>-->
<!--                                </button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <footer class="post__footer post__indicators">-->
<!--                            <div class="post__buttons">-->
<!--                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">-->
<!--                                    <svg class="post__indicator-icon" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart"></use>-->
<!--                                    </svg>-->
<!--                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart-active"></use>-->
<!--                                    </svg>-->
<!--                                    <span>250</span>-->
<!--                                    <span class="visually-hidden">количество лайков</span>-->
<!--                                </a>-->
<!--                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">-->
<!--                                    <svg class="post__indicator-icon" width="19" height="17">-->
<!--                                        <use xlink:href="#icon-comment"></use>-->
<!--                                    </svg>-->
<!--                                    <span>25</span>-->
<!--                                    <span class="visually-hidden">количество комментариев</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </footer>-->
<!--                    </article>-->
<!---->
<!--                    <article class="search__post post post-quote">-->
<!--                        <header class="post__header post__author">-->
<!--                            <a class="post__author-link" href="#" title="Автор">-->
<!--                                <div class="post__avatar-wrapper">-->
<!--                                    <img class="post__author-avatar" src="img/userpic-mark.jpg" alt="Аватар пользователя">-->
<!--                                </div>-->
<!--                                <div class="post__info">-->
<!--                                    <b class="post__author-name">Марк Смолов</b>-->
<!--                                    <span class="post__time">2 дня назад</span>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </header>-->
<!--                        <div class="post__main">-->
<!--                            <blockquote>-->
<!--                                <p>-->
<!--                                    Тысячи людей живут без любви, но никто — без воды.-->
<!--                                </p>-->
<!--                                <cite>Xью Оден</cite>-->
<!--                            </blockquote>-->
<!--                        </div>-->
<!--                        <footer class="post__footer post__indicators">-->
<!--                            <div class="post__buttons">-->
<!--                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">-->
<!--                                    <svg class="post__indicator-icon" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart"></use>-->
<!--                                    </svg>-->
<!--                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart-active"></use>-->
<!--                                    </svg>-->
<!--                                    <span>250</span>-->
<!--                                    <span class="visually-hidden">количество лайков</span>-->
<!--                                </a>-->
<!--                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">-->
<!--                                    <svg class="post__indicator-icon" width="19" height="17">-->
<!--                                        <use xlink:href="#icon-comment"></use>-->
<!--                                    </svg>-->
<!--                                    <span>25</span>-->
<!--                                    <span class="visually-hidden">количество комментариев</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </footer>-->
<!--                    </article>-->
<!---->
<!--                    <article class="search__post post post-link">-->
<!--                        <header class="post__header post__author">-->
<!--                            <a class="post__author-link" href="#" title="Автор">-->
<!--                                <div class="post__avatar-wrapper">-->
<!--                                    <img class="post__author-avatar" src="img/userpic-larisa.jpg" alt="Аватар пользователя">-->
<!--                                </div>-->
<!--                                <div class="post__info">-->
<!--                                    <b class="post__author-name">Лариса Роговая</b>-->
<!--                                    <span class="post__time">Месяц назад</span>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </header>-->
<!--                        <div class="post__main">-->
<!--                            <div class="post-link__wrapper">-->
<!--                                <a class="post-link__external" href="http://www.vitadental.ru" title="Перейти по ссылке">-->
<!--                                    <div class="post-link__icon-wrapper">-->
<!--                                        <img src="img/logo-vita.jpg" alt="Иконка">-->
<!--                                    </div>-->
<!--                                    <div class="post-link__info">-->
<!--                                        <h3>Стоматология «Вита»</h3>-->
<!--                                        <span>www.vitadental.ru</span>-->
<!--                                    </div>-->
<!--                                    <svg class="post-link__arrow" width="11" height="16">-->
<!--                                        <use xlink:href="#icon-arrow-right-ad"></use>-->
<!--                                    </svg>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <footer class="post__footer post__indicators">-->
<!--                            <div class="post__buttons">-->
<!--                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">-->
<!--                                    <svg class="post__indicator-icon" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart"></use>-->
<!--                                    </svg>-->
<!--                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">-->
<!--                                        <use xlink:href="#icon-heart-active"></use>-->
<!--                                    </svg>-->
<!--                                    <span>250</span>-->
<!--                                    <span class="visually-hidden">количество лайков</span>-->
<!--                                </a>-->
<!--                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">-->
<!--                                    <svg class="post__indicator-icon" width="19" height="17">-->
<!--                                        <use xlink:href="#icon-comment"></use>-->
<!--                                    </svg>-->
<!--                                    <span>25</span>-->
<!--                                    <span class="visually-hidden">количество комментариев</span>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </footer>-->
<!--                    </article>-->
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>
