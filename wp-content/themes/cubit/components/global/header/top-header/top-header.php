<?php $socials = get_field('socials', 'option'); ?>
<div id="header-top" class="hidden py-4 text-sm font-semibold bg-white text-neutral-500 lg:flex">
    <div class="container flex justify-between w-full">
        <div class="flex justify-between w-2/3">
            <div class="flex flex-row items-center gap-2">
                <span class="block w-4 h-4 text-primary"><?= get_icon('location-marker', 'solid'); ?></span>
                <span><?= get_field('company_address', 'option'); ?></span>
            </div>
            <div class="flex flex-row items-center gap-2">
                <span class="block w-4 h-4 text-primary"><?= get_icon('mail', 'solid'); ?></span>
                <a
                    href="mailto:<?= get_field('company_email', 'option'); ?>"><?= get_field('company_email', 'option'); ?></a>
            </div>
            <div class="flex flex-row items-center gap-2">
                <span class="block w-4 h-4 text-primary"><?= get_icon('phone', 'solid'); ?></span>
                <a
                    href="tel:<?= get_field('company_phone', 'option'); ?>"><?= get_field('company_phone', 'option'); ?></a>
            </div>
        </div>
        <div class="flex items-center justify-end w-1/3 gap-4">
            <?php if (!empty($socials)):
                foreach ($socials as $key => $social): //vdump($social['type']); 
                    ?>
                    <a href="<?= $social['url'] ?>" target="_blank"
                        class="flex justify-center p-1 bg-black rounded-sm hover:bg-primary">
                        <span
                            class="inline-block w-4 h-4 text-white transition-colors rounded-md cursor-pointer "><?= get_social_icon($social['type']) ?></span>
                    </a>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</div>