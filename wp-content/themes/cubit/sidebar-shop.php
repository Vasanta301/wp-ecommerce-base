<div class="container p-6 mx-auto overflow-y-auto">
    <!-- Filters Section -->
    <div class="flex flex-col gap-6">
        <!-- Price Range -->
        <div class="flex gap-2">
            <input type="number" name="price_min" x-model="filters.price_min" value="outofstock" placeholder="Min Price"
                class="w-1/2 p-2 border rounded" />
            <input type="number" name="price_max" x-model="filters.price_max" value="outofstock" placeholder="Max Price"
                class="w-1/2 p-2 border rounded" />
        </div>

        <!-- Stock Status -->
        <div>
            <h3 class="mb-2 font-bold">Stock Status</h3>
            <label class="inline-flex items-center gap-2">
                <input type="radio" :selected="filters.stockStatus === 'instock'" x-model="filters.stockStatus"
                    value="instock" class="mr-2" />
                In Stock
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="radio" :selected="filters.stockStatus === 'outofstock'" x-model="filters.stockStatus"
                    value="outofstock" class="mr-2" />
                Out of Stock
            </label>
        </div>
        <!-- Categories -->
        <div>
            <h3 class="mb-2 font-bold">Categories</h3>
            <div class="flex flex-wrap gap-2">
                <?php
                $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
                foreach ($categories as $category) {
                    ?>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox"
                            :checked="filters.categories.includes('<?php echo esc_attr($category->slug); ?>')"
                            @change="toggleFilter('categories', '<?php echo esc_attr($category->slug); ?>')" class="mr-2" />
                        <?php echo esc_html($category->name); ?>
                    </label>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Tags -->
        <div>
            <h3 class="mb-2 font-bold">Tags</h3>
            <div class="flex flex-wrap gap-2">
                <?php
                $tags = get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]);
                foreach ($tags as $tag) {
                    ?>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" :checked="filters.tags.includes('<?php echo esc_attr($tag->slug); ?>')"
                            @change="toggleFilter('tags', '<?php echo esc_attr($tag->slug); ?>')" class="mr-2" />
                        <?php echo esc_html($tag->name); ?>
                    </label>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Attributes -->
        <div>
            <h3 class="mb-2 font-bold">Attributes</h3>
            <div class="flex flex-wrap gap-2">
                <?php
                $attributes = wc_get_attribute_taxonomies();
                foreach ($attributes as $attribute) {
                    $attribute_terms = get_terms(['taxonomy' => 'pa_' . $attribute->attribute_name, 'hide_empty' => true]);
                    foreach ($attribute_terms as $term) {
                        ?>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox"
                                :checked="filters.attributes.includes('<?php echo esc_attr($term->slug); ?>')"
                                @change="toggleFilter('attributes', '<?php echo esc_attr($term->slug); ?>')" class="mr-2" />
                            <?php echo esc_html($term->name); ?>
                        </label>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <!-- Filter Button -->
        <button @click="filterProducts" class="p-2 mt-4 text-white bg-black rounded hover:bg-black">
            Apply Filters
        </button>
    </div>
</div>