<?php
ob_start();
?>
<div id="testimonial-modal">
    <div x-data="modalTestimonial()" @open-testimonial-modal.window="open = true; testimonial=$event.detail;">
        <?php include get_component_path('global/modal-testimonial/start'); ?>
        <div class="max-w-[calc(177.77vh-160px)] mx-auto aspect-w-16 aspect-h-9 relative">
            <?php include get_component_path('global/modal-testimonial/content') ?>
        </div>
        <?php include get_component_path('global/modal-testimonial/end'); ?>
    </div>
</div>
<?php
$modal = ob_get_clean();
add_action('wp_footer', function () use ($modal) {
    echo $modal;
});
?>