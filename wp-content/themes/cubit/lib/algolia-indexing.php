<?php
function index_record_array($index, $array) {
  $keys = explode('.', $index);
  $value = $array;
  foreach ($keys as $key) {
    if (isset($value[$key])) {
      $value = $value[$key];
    } else {
      return null;
    }
  }
  return $value;
}

add_filter('index_page', function ($records, $postId) {
  $post = get_post($postId);
  if ($post->page_template == '') {
    $fields = get_fields($post);
    $content = [];
    foreach ($fields['page_components'] as $key => $layout) {
      try {
        switch ($layout['acf_fc_layout']) {
          case 'glossary':
            $content_data = $layout['glossaries_table'];
            foreach ($content_data['glossaries'] as $glossary) {
              array_push($content, $glossary['as_seen_as']);
              array_push($content, $glossary['meaning']);
            }
            break;
          case 'banner_images':
          case 'banner_generic':
            array_push($content, $layout['title']);
            array_push($content, $layout['description']);
            break;
          case 'banner_homepage':
            array_push($content, $layout['title']);
            break;
          case 'text_100':
            array_push($content, $layout['text']);
            break;
          case 'training':
            array_push($content, $layout['title']);
            array_push($content, $layout['content']);
            break;
          case 'generic_text_image_5050':
            array_push($content, $layout['title']);
            array_push($content, $layout['description']);
            break;
          case 'image_&_content_5050':
            array_push($content, $layout['title']);
            array_push($content, $layout['content']);
            break;
          case 'special_offer':
            array_push($content, $layout['title']);
            array_push($content, $layout['subtitle']);
            array_push($content, $layout['description']);
            break;
          case 'content_generic':
            array_push($content, $layout['content']);
            break;
          case 'reviews':
            $content_data = $layout['reviews'];
            array_push($content, $layout['text']);
            foreach ($content_data as $review) {
              array_push($content, $review['review']);
            }
            break;
          case 'pricing_table':
            $content_data = $layout['consultation_fees'];
            array_push($content, $layout['description']);
            if (is_array($content)) {
              foreach ($content_data as $pricing_row) {
                array_push($content, $pricing_row['consultation_type']);
                array_push($content, $pricing_row['price']);
              }
            }
            break;
          case 'faq':
            $content_data = $layout['topics'];
            array_push($content, $layout['title']);
            array_push($content, $layout['description']);

            if (is_array($content_data)) {
              foreach ($content_data as $topic) {
                if (is_array($topic['qnas'])) {
                  foreach ($topic['qnas'] as $qna) {
                    array_push($content, $qna['question']);
                    array_push($content, $qna['answer']);
                  }
                }
              }
            }
            break;
          case 'gift_cards':
            array_push($content, $layout['title']);
            array_push($content, $layout['description']);
            break;
          case 'contact_us':
            array_push($content, $layout['title']);
            array_push($content, $layout['content']);
            break;
          case 'call_to_action':
            array_push($content, $layout['title']);
            array_push($content, $layout['description']);
            break;
          default:
            break;
        }
      } catch (\Exception $ex) {
        throw $ex;
      }
    }

    $records['content'] = $content;


    $records['content'] = strip_tags(join(' ', $records['content']));

  } else {
    $fields = get_fields($post);
    $records['content'] = array(
      $fields['content'],
    );
    $records['content'] = strip_tags(join(' ', $records['content']));
  }

  return $records;
}, 10, 2);

add_filter('index_post', function ($records, $postId) {
  $post = get_post($postId);
  $fields = get_fields($post);
  $records['content'] = array($post->post_content);
  $records['content'] = strip_tags(join(' ', $records['content']));

  return $records;
}, 10, 2);

add_filter('index_treatment_type', function ($records, $postId) {

  $post = get_post($postId);
  $post->page_template = $post->page_template ?? 'default';
  $fields = get_fields($post);
  $content = [];


  array_push($content, $fields['hero']['description']);
  array_push($content, $fields['hero']['summary']['treatment_time']);
  array_push($content, $fields['hero']['summary']['discomfort_level']);
  array_push($content, $fields['hero']['summary']['time_to_result']);
  array_push($content, $fields['hero']['summary']['maintenance_required']);
  array_push($content, $fields['hero']['summary']['collagen_alternative']);
  array_push($content, $fields['about']['description']);
  array_push($content, $fields['benefits']['description']);
  if (is_array($fields['appointment']['items'])) {
    foreach ($fields['appointment']['items'] ?? [] as $appointment) {
      array_push($content, $appointment['name']);
      array_push($content, $appointment['description']);
    }
  }

  array_push($content, $fields['before_&_aftercare']['description']);
  array_push($content, $fields['results']['content']);
  if (is_array($fields['before_&_after']['before_&_after']['testimonials'])) {
    foreach ($fields['before_&_after']['before_&_after']['testimonials'] as $appointment) {
      array_push($content, $appointment['testimonial']);
    }
  }

  if (is_array($fields['pricing']['additional_items'])) {
    foreach ($fields['pricing']['additional_items'] ?? [] as $item) {
      array_push($content, $item['label']);
      array_push($content, $item['description']);
    }
  }


  if (is_array($fields['information_hub']['information_hub']))
    foreach ($fields['information_hub']['information_hub']['tabs'] ?? [] as $tab) {
      array_push($content, $tab['tab_name']);
      array_push($content, $tab['content']);
      if (is_array($tab['accordion'])) {
        foreach ($tab['accordion'] as $accordion) {
          array_push($content, $accordion['name']);
          array_push($content, $accordion['description']);
        }
      }
    }

  if (is_array($fields['faq'])) {
    foreach ($fields['faq'] as $faqSection) {
      foreach ($faqSection as $faq) {
        array_push($content, $faq['question']);
        array_push($content, $faq['answer']);
      }
    }
  }


  if (array_key_exists('show_feature_component', $fields) && $fields['show_feature_component'] == 1) {
    array_push($content, $fields['feature_component']['feature_component']['sub_title']);
    array_push($content, $fields['feature_component']['feature_component']['title']);
    array_push($content, $fields['feature_component']['feature_component']['description']);
  }
  (array_key_exists('suitability', $fields)) && array_push($content, $fields['suitability']);
  (array_key_exists('aftercare', $fields)) && array_push($content, $fields['aftercare']);

  $records['content'] = strip_tags(join(' ', $content));

  return $records;
}, 10, 2);

add_filter('index_treatment_app', function ($records, $postId) {

  $post = get_post($postId);
  $post->page_template = $post->page_template ?? 'default';
  $fields = get_fields($post);
  $content = [];

  array_push($content, $fields['hero']['description']);
  array_push($content, $fields['hero']['summary']['treatment_time']);
  array_push($content, $fields['hero']['summary']['discomfort_level']);
  array_push($content, $fields['hero']['summary']['time_to_result']);
  array_push($content, $fields['hero']['summary']['maintenance_required']);
  array_push($content, $fields['hero']['summary']['collagen_alternative']);
  array_push($content, $fields['about']['description']);
  array_push($content, $fields['benefits']['description']);

  array_push($content, $fields['before_&_aftercare']['description']);
  if (is_array($fields['before_&_after']['before_&_after']['testimonials'])) {
    foreach ($fields['before_&_after']['before_&_after']['testimonials'] as $appointment) {
      array_push($content, $appointment['testimonial']);
    }
  }

  foreach ($fields['information_hub']['information_hub']['information_hub']['tab'] ?? [] as $tab) {
    foreach ($tab as $accordion) {
      array_push($content, $accordion['name']);
      array_push($content, $accordion['description']);
    }
  }

  foreach ($fields['faq'] as $faqSection) {
    foreach ($faqSection as $faq) {
      array_push($content, $faq['question']);
      array_push($content, $faq['answer']);
    }
  }

  if (array_key_exists('show_feature_component', $fields) && $fields['show_feature_component'] == 1) {
    array_push($content, $fields['feature_component']['feature_component']['sub_title']);
    array_push($content, $fields['feature_component']['feature_component']['title']);
    array_push($content, $fields['feature_component']['feature_component']['description']);
  }
  (array_key_exists('suitability', $fields)) && array_push($content, $fields['suitability']);
  (array_key_exists('aftercare', $fields)) && array_push($content, $fields['aftercare']);

  $records['content'] = strip_tags(join(' ', $content));

  return $records;
}, 10, 2);

add_filter('index_treatment', function ($records, $postId) {

  $post = get_post($postId);
  $post->page_template = $post->page_template ?? 'default';
  $fields = get_fields($post);
  $content = [];

  array_push($content, $fields['hero']['description']);
  array_push($content, $fields['hero']['summary']['treatment_time']);
  array_push($content, $fields['hero']['summary']['discomfort_level']);
  array_push($content, $fields['hero']['summary']['time_to_result']);
  array_push($content, $fields['hero']['summary']['maintenance_required']);
  array_push($content, $fields['hero']['summary']['collagen_alternative']);
  array_push($content, $fields['about']['description']);
  array_push($content, $fields['benefits']['description']);

  array_push($content, $fields['before_&_aftercare']['description']);


  if (is_array($fields['results'])) {
    array_push($content, $fields['results']['content']);
  }


  if (is_array($fields['before_&_after']['before_&_after']['testimonials'])) {
    foreach ($fields['before_&_after']['before_&_after']['testimonials'] ?? [] as $testimonial) {
      array_push($content, $testimonial['testimonial']);
    }
  }

  if (array_key_exists('information_hub', $fields) && is_array($fields['information_hub']['information_hub']['tabs'])) {
    foreach ($fields['information_hub']['information_hub']['information_hub']['tabs'] ?? [] as $tab) {
      array_push($content, $tab['tab_name']);
      array_push($content, $tab['content']);
    }
  }

  if (is_array($fields['faq'])) {
    foreach ($fields['faq'] as $faqSection) {
      foreach ($faqSection as $faq) {
        array_push($content, $faq['question']);
        array_push($content, $faq['answer']);
      }
    }
  }

  if (array_key_exists('show_feature_component', $fields) && $fields['show_feature_component'] == 1) {
    array_push($content, $fields['feature_component']['feature_component']['sub_title']);
    array_push($content, $fields['feature_component']['feature_component']['title']);
    array_push($content, $fields['feature_component']['feature_component']['description']);
  }
  (array_key_exists('suitability', $fields)) && array_push($content, $fields['suitability']);
  (array_key_exists('aftercare', $fields)) && array_push($content, $fields['aftercare']);

  $records['content'] = strip_tags(join(' ', $content));

  return $records;
}, 10, 2);

add_filter('index_team_member', function ($records, $postId) {

  $post = get_post($postId);
  $post->page_template = $post->page_template ?? 'default';
  $fields = get_fields($post);
  $content = [];
  array_push($content, $fields['role']);
  array_push($content, $fields['short_bio']);
  array_push($content, $fields['bio']);
  if (isset($fields['qualifications']) && is_array(($fields['qualifications']))) {
    foreach ($fields['qualifications'] as $qualification) {
      array_push($content, $qualification['qualification']);
    }
  }
  $records['content'] = strip_tags(join(' ', $content));
  return $records;
}, 10, 2);

add_filter('index_treatment_guide', function ($records, $postId) {

  $post = get_post($postId);
  $post->page_template = $post->page_template ?? 'default';
  $fields = get_fields($post);
  $content = [];
  array_push($content, $fields['description']);
  $records['content'] = strip_tags(join(' ', $content));
  return $records;
}, 10, 2);