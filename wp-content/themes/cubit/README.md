# Design Principles
1. Minimal use of plugins
2. Try to make everything into a self-contained component



# Component Rules

## 1. Component Folder Structure
Keep css and js close to the associated html by using this folder structure:

```
components
├── page-template
│   ├── component-one
│   │   ├── _component-one.scss
│   │   ├── component-one.js
│   │   └── component-one.php
│   └── component-two
│       ├── _component-two.scss
│       ├── component-two.js
│       └── component-two.php
├── some-other-page-template
│   ├── another-component
│   └── yet-another-component
└── global
    └── contact-us
        ├── _contact-us.scss
        └── contact-us.php
```
Note: For components that are used in multiple places across the website, put them in the global subdirectory:

Import scss files in `theme-folder/src/scss/style.scss` like this:

```
@import '../../components/page-template/component-one/component-one';
@import '../../components/page-template/component-two/component-two';
@import '../../components/some-other-page-template/another-component/another-component';
@import '../../components/some-other-page-template/yet-another-component/yet-another-component';
@import '../../components/global/ccontact-us/contact-us';
```

Import js files in `theme-folder/src/js/index.js` like this:

```
// ...import other modules
import ContactUs from '../../components/global/contact-us/contact-us'


document.addEventListener("DOMContentLoaded", function() {
  // ...execute other modules
  ContactUs()
})
```

All js files should look like this:
```
export default () => {
  // ...put your code here
}
```

Note: Most components won't need a js file.

## 2. Wordpress Template Files

Wordpress template files (e.g. `single-program.php`, `archive-product.php` or `page-about.php`) should only be made up of components where possible. For example, `single-program.php` would look like:

```
<?php
get_header();

get_template_part('components/program/banner');
get_template_part('components/program/register-interest');
get_template_part('components/program/variations');
get_template_part('components/program/overview');
get_template_part('components/program/reviews');

get_footer();
```

## 3. Component Data

All the data needed for a component should be retrieved in the component file. For example, in `components/home/community.php`:

```
<?php
  $community = get_field('community_and_sponsorships');
  $section_heading = $community['section_heading'];
  $description = $community['description'];
  $companies = $community['companies'];
?>

<section id="community">
  ...more html here
</section>
```

## 4. Components requiring IDs

In component files that require IDs, use the Wordpress functions `set_query_var` and `get_query_var` to pass IDs from parent file to child components. For example, take a global `location.php` component:

```
<?php
$location_id = get_query_var('location_id');
$location_data['title'] = get_the_title($location_id);
$location_data['address'] = get_geocode_address($location_id);
$location_data['driving'] = get_field('driving', $location_id);
?>

<section class="location">
  ...more html here
</section>
```

And the parent component `locations.php`:

```
<?php
$location_ids = get_field('location_ids');
echo '<section class="locations">';
foreach ($location_ids as $location_id):
  set_query_var('location_id', $location_id);
  get_template_part('components/global/location/location');
endforeach;
echo '</section>';
```


## 5. Component HTML structure

Try and make every components top level html element a section with an id which is exactly the same as the component’s php file name. E.g. the `components/global/reviews/reviews.php` file should look like:

```
<?php
  // Retrieve data here
?>

<section id="reviews">
  ...more html here
</section>
```

If you know the component will appear more than once on any given page, use `class="reviews"` instead.
