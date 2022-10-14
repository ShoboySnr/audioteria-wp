<?php

use AudioteriaWP\Core\Pagewalker;

global $post;


$id = get_the_ID();

$args = array(
	'child_of' => get_top_page_id(),
	'title_li' => '',
	'walker' => new Pagewalker()

);



?>

<main class="about-main">
    <ul class="about-nav">
        <li>
            <a href="<?= home_url('/') ?>"><?= __('Home', 'audioteria-wp') ?> </a>
        </li>
        <li class="separator separator-home"> | </li>
        <li>
            <a href="<?= get_the_permalink(get_top_page_id()) ?>"><?= __('About Audioteria', 'audioteria-wp') ?></a>
        </li>
        <li class="separator separator-home"> | </li>
        <li class="active">
            <a href="<?= get_the_permalink(get_the_ID()) ?>"><?= get_the_title(); ?></a>
        </li>
    </ul>
    <section class="about-tab">
        <div class="about-tab-1">
            <h3 class="about-tab-title"><?= __('About Audioteria', 'audioteria-wp') ?></h3>
            <div class="about-tab-buttons">
                <button class="<?= $post->post_parent == 0 ? 'active' : '' ?>">
                    <a href="<?= get_the_permalink(get_top_page_id()) ?>">
                        <?php echo __('About', 'audioteria_wp') ?>
                    </a>
                </button>
                <?php
				    wp_list_pages($args);
				?>
            </div>
        </div>
        <div>
            <div class="about-tab-content">
                <h5 class="about-tab-content-title"><?= get_field('page_title', $id); ?></h5>
                <p>
                    <?php the_content(); ?>
                </p>
            </div>

        </div>

    </section>
</main>