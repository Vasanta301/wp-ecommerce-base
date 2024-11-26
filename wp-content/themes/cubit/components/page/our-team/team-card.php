<?php
$id = $info['post_id'];
$image_id = get_field('image', $id);
$position = get_field('position', $id);
$description = get_field('description', $id);
$image = get_img($image_id, '(max-width: 750px) 750w, 464px', ['class' => 'object-cover w-full h-full group-hover transition-transform duration-300']);

$member = [
  'image' => $image,
  'position' => $position,
  'name' => get_the_title($id),
  'description' => $description,
]
  ?>
<div class="cursor-pointer swiper-slide group max-w-[480px] w-full md:block" x-data="{hover:false}">
  <div class="relative" @mouseover="hover = true" @mouseover.away="hover = false"
    @click="$dispatch('open-member-modal', <?= json_encode_alpine($member) ?>)">
    <div class="relative flex flex-col items-center justify-center">
      <div class="relative w-32 h-auto mx-auto overflow-hidden rounded-full">
        <?= get_img($image_id, '(max-width: 500px) 100vw,(max-width:1024px)50vw,(max-width:1556px) 40vw,40vw', ['class' => 'object-cover w-full h-full group-hover transition-transform group-hover:scale-110 duration-300 ']) ?>
      </div>
      <div class="pt-8 font-medium leading-4 text-center ">
        <div class="pb-1 text-xl ">
          <?= get_the_title($id); ?>
        </div>
        <div class="text-xs uppercase text-primary">
          <?= $position ?>
        </div>
      </div>
    </div>
  </div>
</div>