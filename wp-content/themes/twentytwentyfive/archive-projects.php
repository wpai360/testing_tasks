<?php get_header(); ?>

<div class="project-archive-wrapper">
    <?php
    $paged = max(1, get_query_var('paged'));
    $args = [
        'post_type' => 'projects',
        'posts_per_page' => 6,
        'paged' => $paged,
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="project-card">
                <h2><?php the_title(); ?></h2>
                <div><?php the_excerpt(); ?></div>
            </div>
        <?php endwhile; ?>

        <div class="project-pagination">
            <?php
            $prev = get_previous_posts_link('« Previous');
            $next = get_next_posts_link('Next »', $query->max_num_pages);
            echo $prev ?: '';
            echo $next ?: '';
            ?>
        </div>

    <?php else : ?>
        <p>No projects found.</p>
    <?php endif;

    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>

