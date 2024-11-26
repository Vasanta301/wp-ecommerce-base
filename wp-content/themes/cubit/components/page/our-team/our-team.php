<section id="our-team">
    <div class="relative">
        <div class="container py-8 overflow-hidden lg:py-16">
            <!-- Heading Section -->
            <div class="flex items-center gap-4 pb-7">
                <hr class="w-4 border border-black">
                <h2 class="text-xl font-bold text-primary"><?= $fields['subtitle']; ?></h2>
            </div>
            <div class="flex flex-col lg:flex-row ">
                <div class="lg:w-1/2">
                    <h2 class="lg:text-[2.75rem] text-4xl font-semibold text-black pb-7"><?= $fields['title']; ?></h2>
                </div>
                <div class="lg:w-1/2">
                    <p class="text-neutral-500">
                        <?= $fields['description']; ?>
                    </p>
                </div>
            </div>
            <?php
            $categories = get_terms(['taxonomy' => 'team_category', 'hide_empty' => true]);
            $count = is_array($categories) ? count($categories) : 0;
            ?>
            <?php if ($count > 0): ?>
                <div class="w-full">
                    <div class="flex justify-center gap-4 mx-auto mt-6" x-data="{ activeTab: 'all' }">
                        <button @click="activeTab = 'all'; $dispatch('tab-changed', { tab: activeTab })"
                            :class="{ 'font-bold': activeTab === 'all' }">
                            All
                        </button>
                        <?php foreach ($categories as $category): ?>
                            <button
                                @click="activeTab = '<?= esc_js($category->slug); ?>'; $dispatch('tab-changed', { tab: activeTab })"
                                :class="{ 'font-bold': activeTab === '<?= esc_js($category->slug); ?>' }">
                                <?= esc_html($category->name); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="py-12">
                <div x-data="ourTeam()" x-init="activeTab = 'all'" @tab-changed.window="activeTab = $event.detail.tab"
                    x-show="activeTab === 'all'" x-transition:enter="transition ease-in duration-0"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    class="swiper-container swiper" x-ref="swiperContainer" x-cloak>
                    <div class="flex swiper-wrapper">
                        <?php
                        $args = [
                            'post_type' => 'our_team',
                            'posts_per_page' => -1,
                            'orderby' => 'date',
                            'order' => 'DESC',
                        ];
                        $all_team_query = new WP_Query($args);
                        while ($all_team_query->have_posts()):
                            $all_team_query->the_post();
                            include get_component_path('page/our-team/team-card');
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <?php if ($count > 0): ?>
                    <?php foreach ($categories as $category): ?>
                        <?php
                        $args = [
                            'post_type' => 'our_team',
                            'posts_per_page' => -1,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'tax_query' => [[
                                'taxonomy' => 'team_category',
                                'field' => 'slug',
                                'terms' => $category->slug,
                            ]],
                        ];
                        $team_query = new WP_Query($args);
                        ?>
                        <div x-data="ourTeam()" x-init="activeTab = 'all'" @tab-changed.window="activeTab = $event.detail.tab"
                            x-show="activeTab === '<?= esc_js($category->slug); ?>'"
                            x-transition:enter="transition ease-in duration-300" x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100" class="swiper-container swiper"
                            x-ref="swiperContainer" x-cloak>
                            <div class="flex swiper-wrapper">
                                <?php while ($team_query->have_posts()):
                                    $team_query->the_post(); ?>
                                    <?php include get_component_path('page/our-team/team-card'); ?>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div id="scrolltop" class="scrolltop">
            <div class="pb-5 text-sm font-medium leading-4 tracking-wide text-center uppercase lg:hidden sm:block">
                Swipe to browse
            </div>
        </div>
    </div>
    <?php include_once get_component_path('global/modal-member/modal-member'); ?>
</section>