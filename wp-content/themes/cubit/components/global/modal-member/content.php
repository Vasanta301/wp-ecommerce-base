<div class="flex items-center justify-center">
    <div class="relative w-full max-w-6xl bg-white" @click.away="open = false">

        <template x-if="member">
            <div class="p-4 lg;p-6 bg-background w-full relative">
                <div class="relative flex flex-col gap-6 lg:flex-row lg:gap-14">
                    <button
                        class="box-content absolute top-0 right-0 hidden w-6 h-6 p-4 text-black transition-transform hover:scale-125 lg:block"
                        @click="open = false">
                        <?= get_icon('x') ?>
                    </button>
                    <div class="w-full flex-0">
                        <div class="aspect-[0.78] h-full" x-html="member?.image">
                        </div>
                    </div>
                    <div class="flex flex-col justify-center flex-grow space-y-4 lg:py-10">
                        <h5 x-text="member?.position" class="text-sm text-shade-dark-gray"></h5>
                        <h2 x-text="member?.name" class="text-2xl font-semibold lg:text-4xl text-shade-dark-gray"></h2>
                        <div class="h-20 overflow-auto overflow-y-auto prose lg:h-auto max-h-80 scroll-dark"
                            x-html="member?.description"></div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>