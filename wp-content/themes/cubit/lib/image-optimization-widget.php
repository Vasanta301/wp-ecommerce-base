<?php

add_action('wp_dashboard_setup', function () {
  wp_add_dashboard_widget('image-optimization-explanation', 'Image Optimization Explanation', 'image_optimization_explanation_widget');
});

function image_optimization_explanation_widget() {
  ob_start(); ?>
  <ol>
    <li>
      <p>If images are blurry (e.g. our difference component on homepage) it's because the uploaded image is the wrong
        aspect ratio for the slot that it fills in the component.</p>
    </li>
    <li>Images are now served using the .webp next-gen image format instead of .png or .jpg for reduced file size</li>
    <li>
      <p>Image widths generated:</p>
      <ol style="list-style-type: lower-roman;">
        <li>150</li>
        <!-- <li>300</li> -->
        <li>480</li>
        <!-- <li>640</li> -->
        <li>750</li>
        <!-- <li>828</li> -->
        <li>1080</li>
        <!-- <li>1200</li> -->
        <li>1920</li>
        <!-- <li>2048</li> -->
        <li>3840</li>
      </ol>
    </li>
  </ol>
<?php
  echo ob_get_clean();
}