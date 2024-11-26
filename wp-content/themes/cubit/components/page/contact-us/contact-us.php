<?php $socials = get_field('socials', 'option');
$contact_form = get_field('contact_form', 'option');
?>
<div id="contact-us">
    <div class="w-full bg-neutral-200">
        <div class="relative px-4 py-2 mx-auto max-w-7xl">
            <div class="flex flex-col justify-center w-full mx-auto mt-10 lg:absolute lg:flex-row -top-28">
                <?php if (!empty($fields['heading'])): ?>
                    <div class="flex flex-col justify-between w-full gap-6 md:flex-row ">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <div class="flex flex-col w-full gap-4 px-4 py-2 lg:w-1/3 bg-secondary">
                                <?php if ($i === 0): ?>
                                    <span
                                        class="flex w-10 h-10 items text-primary"><?= get_icon('location-marker', 'solid'); ?></span>
                                    <div class="flex mb-2">
                                        <h2 class="text-xl font-bold text-left text-white ">
                                            <?= esc_html($fields['heading'][$i]['heading']); ?>
                                        </h2>
                                    </div>
                                    <span class="text-white"><?= get_field('company_address', 'option'); ?></span>
                                <?php elseif ($i === 1): ?>
                                    <span class="flex w-10 h-10 items text-primary"><?= get_icon('mail', 'solid'); ?></span>
                                    <div class="flex mb-2">
                                        <h2 class="text-xl font-bold text-left text-white ">
                                            <?= esc_html($fields['heading'][$i]['heading']); ?>
                                        </h2>
                                    </div>
                                    <a class="text-white"
                                        href="mailto:<?= get_field('company_email', 'option'); ?>"><?= get_field('company_email', 'option'); ?></a>
                                <?php elseif ($i === 2): ?>
                                    <span class="flex w-10 h-10 items text-primary"><?= get_icon('phone', 'solid'); ?></span>
                                    <div class="flex mb-2">
                                        <h2 class="text-xl font-bold text-left text-white ">
                                            <?= esc_html($fields['heading'][$i]['heading']); ?>
                                        </h2>
                                    </div>
                                    <a class="text-white"
                                        href="tel:<?= get_field('company_phone', 'option'); ?>"><?= get_field('company_phone', 'option'); ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="py-8 mt-10 text-sm font-semibold text-neutral-500 lg:flex lg:mt-28">
                <div class="flex flex-col gap-4 mt-5 lg:mt-10 lg:flex-row">
                    <div class="lg:w-1/2">

                        <div class="flex items-center gap-4 pb-7">
                            <hr class="flex w-10 border border-black items">
                            <h2 class="text-xl font-bold text-primary"><?= $fields['subtitle']; ?></h2>
                        </div>

                        <div class=" pb-7">
                            <div class="">
                                <h2 class="lg:text-[2.75rem]  text-4xl font-semibold text-black pb-7">
                                    <?= $fields['title']; ?>
                                </h2>
                            </div>
                            <div class="">
                                <p class="font-normal text-black">
                                    <?= $fields['description']; ?>
                                </p>
                            </div>
                        </div>
                        <div id="map">
                            <div class="mx-auto h-1/2">
                                <?= ($fields['map']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="w-full px-3 md:w-full lg:w-1/2">
                        <div class="flex flex-col">
                            <?= do_shortcode($contact_form['short_code']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>