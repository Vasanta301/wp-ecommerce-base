<?php
$fields = get_field('frequently_asked_questions', 'option');
if ($fields): ?>
    <section id="frequently-asked-question">
        <div class="container flex flex-col py-16 lg:py-32 lg:flex-row">
            <div class="flex flex-col items-center content-between w-full max-w-6xl gap-8 lg:flex-row "
                x-data="{ visible: false }" x-intersect="visible = true">
                <div class="relative w-full h-auto lg:w-full">
                    <?= get_img($fields['image'], '(max-width: 1920px) 100vw, 1920px', ['class' => 'object-cover object-center lg:w-5/6 h-[550px] inset-0']); ?>
                    <?php
                    /***
                    <section class="lg:absolute right-4 bg-primary  border-8 border-white  -bottom-[10%] lg:w-1/2 h-24  w-full "
                       x-data="{ open: false }">
                       <div class="z-10 flex gap-4 p-4 ">
                           <span @click="open = true"
                               class="flex items-center justify-center w-20 transition-colors bg-white rounded-full cursor-pointer h-14 hover:bg-transparent hover:border hover:border-black group">
                               <span class="flex items-center justify-center w-10 h-10 text-black group-hover:text-white">
                                   <?= get_svg('play'); ?>
                               </span>
                           </span>
                           <h3 class="flex items-center text-lg font-bold text-white">
                               <?= esc_html($fields['video_text']); ?>
                           </h3>
                       </div>
                       <div x-show="open" x-cloak
                           class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 ease-out bg-black bg-opacity-75 lg:w-full">

                           <!-- Close Button -->
                           <button @click="open = false" aria-label="Close modal"
                               class="absolute px-4 py-2 text-white transition ease-in-out delay-150 top-5 lg:right-5 right-1">
                               &#10005;
                               <div
                                   class="absolute inset-0 bg-gray-400 border border-gray-100 rounded-full opacity-50 hover:bg-black hover:text-white">
                               </div>
                           </button>
                           <!-- Modal Container -->
                           <div x-data="ModalVideo()"
                               class="relative w-full max-w-md mx-4 overflow-hidden bg-white rounded-lg shadow-lg sm:max-w-lg lg:max-w-4xl h-1/2">
                               <!-- Video Container with Aspect Ratio -->
                               <div class="relative w-full h-full" data-url="<?= $fields['video']; ?>" x-ref="video"
                                   data-type="youtube">
                               </div>
                           </div>
                       </div>
                    </div>
                   **/
                    ?>
                </div>
            </div>
            <div>
                <div class="z-10 mt-10 mb-9 lg:mt-0">
                    <div class="flex items-center gap-4 pb-7">
                        <hr class="w-4 border border-black">
                        <h2 class="text-xl font-bold text-primary"><?= esc_html($fields['subtitle']); ?></h2>
                    </div>
                    <div class="lg:max-w-sm ">
                        <h2 class="text-3xl font-semibold text-black lg:text-5xl pb-7"><?= esc_html($fields['title']); ?>
                        </h2>
                    </div>
                    <p class="max-w-5xl text-black">
                        <?= esc_html($fields['description']); ?>
                    </p>
                </div>
                <?php if (!empty($fields['more_faqs']['more_faqs'])): ?>
                    <div class="w-full max-w-6xl pt-10 mx-auto ">

                        <div x-data="Accordion({})">
                            <div x-data="{ index: null }">
                                <?php foreach ($fields['more_faqs']['more_faqs'] as $index => $qna): ?>
                                    <?php
                                    $fields = [
                                        'index' => $index,
                                        'title' => $qna['question'],
                                        'content' => $qna['answer'],
                                    ];
                                    include get_component_path('global/accordion/accordion');
                                    ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>