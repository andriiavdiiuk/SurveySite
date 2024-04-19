<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/Landing.css">
    <title>Back Bone</title>
</head>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/CategoryMapper.php';
$categoryMapper = new CategoryMapper();
$category = null;

if (isset($_GET['category']) && is_numeric($_GET['category'])) {

    $category = $categoryMapper->get(intval($_GET['category']));
}
?>
<body>
    <div class="site">
        <header>
            <section class="header__section">
                <div class="header__wrapper">
                    <h1 class="header__title">Description For Clients</h1>
                    <div class="header__link-block">
                        <?php if($category != null) : ?>
                            <a href="/quiz-selection.php?category=<?php echo $category->getCategoryId() ?>" class="header__link">TAKE A QUIZ</a>
                        <?php  else : ?>
                        <a href="/quiz-selection.php" class="header__link">TAKE A QUIZ</a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </header>
        <main class="main">
            <section class="main__section-1">
                <div class="section-1__wrapper grid__1 ">
                    <div class="section1__block-text grid__block-text-1">
                        <p class="sectio1__title">FOR WHOM THIS QUIZE?</p>
                        <p class="section1__text">
                            <?php if($category != null) :
                                echo  $category->getCategoryWhom();
                            else :
                            ?>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="section1__block-photo grid__block-photo-1 ">
                        <img src="/assets/emptyPhoto-1.png" alt="" class="section1__block-photo">
                    </div>
                </div>
                <div class="section-1__wrapper grid__2">
                    <div class="section1__block-photo ">
                        <img src="/assets/emptyPhoto-1.png" alt="" class="section1__block-photo">
                    </div>
                    <div class="section1__block-text grid__block-text-2">
                        <p class="sectio1__title">what you get?</p>
                        <p class="section1__text">
                            <?php if($category != null) :
                                echo  $category->getCategoryWhatYouGet();
                            else :
                            ?>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </section>
            <section class="main__section-2">
                <div class="section-2__wrapper">
                    <div class="section-2__title_block">
                        <p class="section-2__title">FAQ</p>
                    </div>
                    <div class="section-2__block_info">
                        <div class="section-2__block_flex">
                            <p class="section-2__text">Who is this quiz for?</p>
                            <div class="section-2__plus_block">
                                <p class="plus__border-1"></p>
                                <p class="plus__border-2"></p>
                            </div>
                        </div>
                        <div class="section-2__active_text">
                            <p class="active__info">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam magnam doloribus cupiditate qui minus esse similique rerum quo saepe quasi!</p>
                        </div>
                    </div>
                    <div class="section-2__block_info">
                        <div class="section-2__block_flex">
                            <p class="section-2__text">Who is this quiz for?</p>
                            <div class="section-2__plus_block">
                                <p class="plus__border-1"></p>
                                <p class="plus__border-2"></p>
                            </div>
                        </div>
                        <div class="section-2__active_text">
                            <p class="active__info">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam magnam doloribus cupiditate qui minus esse similique rerum quo saepe quasi!</p>
                        </div>
                    </div>
                    <div class="section-2__block_info">
                        <div class="section-2__block_flex">
                            <p class="section-2__text">Who is this quiz for?</p>
                            <div class="section-2__plus_block">
                                <p class="plus__border-1"></p>
                                <p class="plus__border-2"></p>
                            </div>
                        </div>
                        <div class="section-2__active_text">
                            <p class="active__info">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam magnam doloribus cupiditate qui minus esse similique rerum quo saepe quasi!</p>
                        </div>
                    </div>
                    <div class="section-2__block_info">
                        <div class="section-2__block_flex">
                            <p class="section-2__text">Who is this quiz for?</p>
                            <div class="section-2__plus_block">
                                <p class="plus__border-1"></p>
                                <p class="plus__border-2"></p>
                            </div>
                        </div>
                        <div class="section-2__active_text">
                            <p class="active__info">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam magnam doloribus cupiditate qui minus esse similique rerum quo saepe quasi!</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer class="footer">
            <section class="footer__section">
                <div class="footer__block">
                    <div class="footer__logo_block">
                            <img src="/assets/logo_footer.png" alt="">
                    </div>
                    <div class="footer__text_block">
                        <p class="footer__text-1">This brand is dedicated to all women, who show their strength every day.</p>
                        <p class="footer__text-2">Copyright © 2024, <a href="https://backbonesociety.com/" class="footer__link">Back Bone Society.</a> All rights reserved. See our <a href="https://backbonesociety.com/policies/terms-of-service" class="footer__link">terms of use</a> and <a href="https://backbonesociety.com/policies/terms-of-service" class="footer__link">privacy notice.</a></p>
                    </div>
                </div>
            </section>
        </footer>
    </div>
    <script src="/js/landing.js"></script>
</body>
</html>