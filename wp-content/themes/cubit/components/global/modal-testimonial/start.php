<div @keydown.window.escape="open = false" x-show="open"
  class="fixed z-20 inset-0 px-4 sm:px-20 flex items-center media-video-container" role="dialog" aria-modal="true"
  x-cloak x-transition:enter="ease-out duration-300"
  x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
  x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
  x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
  x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
  <div class="absolute inset-0 bg-black bg-opacity-50" @click="open = false"></div>

  <div class="w-full select-none relative">
    <div class="absolute inset-0 w-full h-full overlay-content-div ">
    </div>