<?php
ob_start();
?>
<div id="member-modal">
    <div x-data="ModalMember()" @open-member-modal.window="open = true; member=$event.detail">
        <?php include get_component_path('global/modal-member/start'); ?>
        <div class="max-w-[calc(177.77vh-160px)] mx-auto aspect-w-16 aspect-h-9 relative">
            <?php include get_component_path('global/modal-member/content') ?>
        </div>
        <?php include get_component_path('global/modal-member/end'); ?>
    </div>
</div>
<?php
$modal = ob_get_clean();
add_action('wp_footer', function () use ($modal) {
    echo $modal;
});
?>