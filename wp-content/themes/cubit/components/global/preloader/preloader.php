<?php if (!(defined('HIDE_PRELOADER') && constant('HIDE_PRELOADER'))) : ?>
<div id="preloader">
  <div x-data="Preloader()">
    <div class="fixed z-[1000] bg-skin-light top-0 left-0 w-screen h-screen overflow-hidden" x-ref="panel">
      <!-- <p
        class="absolute top-[calc(50%-72px)] sm:top-[calc(50%-28px)] right-[calc(50%+46px)] lg:right-[calc(50%+3.4vw)] -translate-y-1/2 overflow-hidden text-4xl 2xl:text-5xl 4k:text-6xl leading-10 font-heading">
        <span class="invisible pr-6">
          Confidence
        </span>
        <span class="absolute inset-0 translate-x-full" x-ref="textL">
          Confidence
        </span>
      </p> -->
      <!-- <p
        class="absolute top-[calc(50%-72px)] sm:top-[calc(50%-28px)] left-[calc(50%-25px)] lg:left-[calc(50%-1.8vw)] -translate-y-1/2 overflow-hidden text-4xl 2xl:text-5xl 4k:text-6xl leading-10 font-heading">
        <span class="invisible pr-6">
          looks good.
        </span>
        <span class="absolute inset-0 -translate-x-full" x-ref="textR">
          looks good.
        </span>
      </p> -->
      <div x-ref="c"
        class="absolute top-[calc(50%-44px)] sm:top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 lg:w-[7vw] text-skin-dark">
        <?= get_svg('c-underline') ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>