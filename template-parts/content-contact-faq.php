<?php

$faqs = \AudioteriaWP\Pages\Faq::get_instance()->get_faq();

$contact_form = get_field('contact_field');
?>
<main class="contact-main">
    <ul class="contact-nav">
        <li>
            <a title="Home" href="<?= home_url('/') ?>"><?= __('Home', 'audioteria-wp') ?> </a>
        </li>
        <li class="active">
            <a href="<?= get_permalink(get_the_ID()); ?>"><?= __('Contact Us', 'audioteria-wp') ?></a>
        </li>
    </ul>

    <?php if (!empty($faqs)) : ?>
    <div class="faq" id="faq">
        <h2 class=" contact-heading"><?= __('FAQ’s', 'audioteria-wp') ?></h2>
        <p class="faq-text"><?= get_field('page_title') ?></p>


        <?php

            foreach ($faqs as $faq) {
            ?>
        <div class="faq-wrapper">
            <button class="faq-button">
                <?= $faq['title']; ?>
                <?php include AUDIOTERIA_ASSETS_ICONS_DIR . '/faq-arrow-black.svg' ?>
            </button>
            <div class="faq-answer"><?= $faq['content'] ?></div>
        </div>

        <?php } ?>

        <button class="faq-view-more">
            <?= __('View more', 'audioteria-wp') ?>
            <?php include AUDIOTERIA_ASSETS_ICONS_DIR . '/faq-arrow-orange.svg' ?>
        </button>
    </div>
    <?php endif; ?>

    <?php if (!empty($contact_form)) : ?>
    <div id="contact-us" class="contact-us">
        <h2 class="contact-heading"><?= __('Contact us', 'audioteria-wp') ?></h2>

        <?= do_shortcode($contact_form) ?>


    </div>
    <?php endif; ?>
</main>