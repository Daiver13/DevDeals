<?php
/**
 * Template Name: Template Contact
 */
// send contact
if (isset($_POST['contact'])) {
    $error = flo_send_contact($_POST['contact']);
}
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php flo_part('pagehead');?>

    <section class="contact cf">
        <div class="page-title">
            <h1><?php the_title(); ?></h1>
        </div>
        <div class="form-text">
            <?php the_content(); ?>
        </div>
        <?php echo do_shortcode('[contact-form-7 id="68" title="Контактная форма"]'); ?>
    </section>

    <?php flo_part('pagefooter');?>
<?php endwhile; else: ?>
    <?php flo_part('notfound')?>
<?php endif; ?>
<?php get_footer(); ?>