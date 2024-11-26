<?php

add_action('admin_footer-post.php',     'convert_category_checkboxes_to_radio');
add_action('admin_footer-post-new.php', 'convert_category_checkboxes_to_radio');
function convert_category_checkboxes_to_radio() {
?>
  <script type="text/javascript">
    const convertToRadio = (list) => {
      list.querySelectorAll('input').forEach(input => {
        input.type = 'radio'
      })
    }
    const lists = document.querySelectorAll('#taxonomy-category .categorychecklist')
    lists.forEach(list => {
      convertToRadio(list)
      list.addEventListener('DOMNodeInserted', function(event) {
        convertToRadio(list)
      })
    })
  </script>
  <style type="text/css">
    #taxonomy-category #category-tabs .hide-if-no-js {
      display: none;
    }
  </style>
<?php
}
