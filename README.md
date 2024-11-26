## Getting Started

Follow these instructions to set up and use Cubit WP Base Theme within Cubit Incorporated.

### Prerequisites

- Confirm that your system meets the following requirements:
  - [List any system requirements, dependencies, or software prerequisites]

### Installation

1. **Pull the repo**

   - Clone the repository to your local machine using Git:

HTTPS

```
https://github.com/webtecsolutions/WP-base.git
```

SSH

```
git clone git@github.com:webtecsolutions/WP-base.git
```

2. **Copy or configure wp-config.php**

- By default, repo will have all the other files/folders except wp-config.php file. So you will have to copy or configure wp-config.php.

3. **Rename Directory**

- Rename the cloned directory to public instead of WP-base. You can either rename older public to public-old or remove it :

4. **Install Dependencies**

- Go to theme directory where you will find theme titled "cubit":

```
  cd wp-content/themes/cubit
```

- Install any required dependencies using [Package Manager/Command] inside the theme folder directory:

```
npm install
```

5. **Kick start the project**

- Start the project in dev mode to dynamically reload and regenrate necessary resources to preview changes in site :

```
npm run dev
```

## Configuring Tailwind properties

### Colors

Define your custom colors in the `colors` section of the `tailwind.config.js` file. This allows you to standardize and reuse colors throughout your project.

```
module.exports = {
  theme: {
    extend: {
      colors: {
        'primary': '#1a202c',
        'secondary': '#2d3748',
        // Add more custom colors here
      },
    },
  },
  // other configurations...
}
```

### Font Family

Define your custom fonts in the fontFamily section of the tailwind.config.js file. This enables you to apply custom fonts consistently across your project.

```
module.exports = {
  theme: {
    extend: {
      fontFamily: {
        'heading': ['Roboto', 'sans-serif'],
        'body': ['Open Sans', 'sans-serif'],
        // Add more custom fonts here
      },
    },
  },
  // other configurations...
}
```

## Extending Existing Tailwind properties

Note: Only extend the properties and donot overwrite as much as possible.

### Screen

- Customize responsive breakpoints for different screen sizes.

```
module.exports = {
  theme: {
    extend: {
      screens: {
        '5k': '3840px',
      },
    },
  },
}
```

### Z-Index

- Define custom z-index values for layering elements.

```
module.exports = {
  theme: {
    extend: {
      zIndex: {
        '60': '60',
      },
    },
  },
}
```

....
more as required

## Extending/Modifying WooCommerce Hooks

Utilize WooCommerce hooks to add custom content or modify existing content without using third-party library as much as possible .

### Add gallery suppport in product single page

- Support for WooCommerce's product gallery features (zoom, lightbox, slider) can be enabled in your theme:

```
add_action('after_setup_theme', 'your_theme_woocommerce_support');
function your_theme_woocommerce_support() {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
```

### Adding/Editing woocommerce object through WP Cruds

- For. e.g. Donot use wp_insert_post() to create new product post. Similarly, donot use update_post_meta() to update `price` for product. Instead

### Products

- To add or modify a product, you can use the WC_Product_Simple class:

```
// Add a new simple product
$product = new WC_Product_Simple();
$product->set_name('Custom Product');
$product->set_regular_price(9.99);
$product->save();

// Modify an existing product
$product_id = 123; // Replace with the actual product ID
$product = new WC_Product_Simple($product_id);
$product->set_price(5.99);
$product->save();
```

- Similarly for Order within woocommerce :

#### Orders

- To create or modify orders, use the WC_Order class:

```
// Create a new order
$order = wc_create_order();
$order->add_product(get_product(123), 2); // Add 2 units of product with ID 123
$order->set_address(array(
    'first_name' => 'John',
    'last_name'  => 'Doe',
    'address_1'  => '123 Main St',
    'city'       => 'Anytown',
    'postcode'   => '12345',
    'country'    => 'US'
), 'billing');
$order->calculate_totals();
$order->update_status('completed');

// Modify an existing order
$order_id = 1234; // Replace with the actual order ID
$order = wc_get_order($order_id);
$order->update_status('completed');
$order->save();
```

- Same for other properties as available in woocommerce

#### Cart

- To interact with the WooCommerce cart, use the WC_Cart class:

```
// Add a product to the cart
$product_id = 123; // Replace with the actual product ID
WC()->cart->add_to_cart($product_id);

// Remove a product from the cart
WC()->cart->remove_cart_item($cart_item_key);

// Empty the cart
WC()->cart->empty_cart();
```

## Managing woocommerce related codes in a single place

### Template files

If you want to modify template by overwriting, please copy related file from plugin to woocommerce folder in theme's root folder.

Example 1 : to customize the product page, copy file from plugin `wp-content/plugins/woocommerce/templates/` to

```
wp-content/themes/cubit/woocommerce/single-product.php
```

Example 2 : To override the admin order notification, copy: `wp-content/plugins/woocommerce/templates/emails/admin-new-order.php` to

```
wp-content/themes/cubit/woocommerce/emails/admin-new-order.php
```

### Woocommerce custom codes within theme

- To customize WooCommerce default code sections using functions, create file similar to how it is done in functions.php.

E.g. Currently, for all taxonomy related code, taxonomy related file is added in `lib/taxonomy.php` and included in `functions.php`.

```
require_once 'lib/taxonomy.php';
```

Similarly, you can write related code and include it inside `lib/woocommerce.php`.

For e.g. Codes related to `product and product tab` can be added inside `lib/woocommerce/product.php` and include that file in `woocommerce.php` in following way

```
require_once 'woocommerce/product.php';
```

....
more as required

### Developer Guideleines

## WordPress Themes

- Template Hierarchy:
  Familiarize yourself with WordPress’s template hierarchy to efficiently locate and override template files. Understanding this can help you customize theme structure and improve the maintainability of your theme.

- Security Practices:
  Sanitize and validate user inputs using WordPress functions like sanitize_text_field() and esc_html(). Protect against SQL injection and cross-site scripting (XSS) by using WordPress's built-in functions.

- Custom Configurations:
  Extend Tailwind’s default configurations (colors, fonts, spacing) in tailwind.config.js to maintain design consistency. Utilize @apply directive to apply utility classes within CSS files if necessary.

  E.g. To add the custom font within theme as needed, custom code with @apply property is added in  `\wp-content\themes\cubit\src\css\components\fonts.css` and included it inside 
  ```
  src\css\index.css
  ```

- Documentation:
  Document your code, configuration, and customizations thoroughly. This includes inline code comments defining what current functions do when was it added.

  ```
  /**
  * Dumps a variable in a human-readable format.
  *
  * This function takes a variable and outputs its contents using `var_dump` wrapped in `<pre>` tags
  * to format the output for better readability in HTML.
  *
  * @param mixed $var The variable to be dumped. Can be of any type.
  * @return void
  */
  function vdump($var)
  {
    echo '<pre>'; // Formats the output for readability
    var_dump($var); // Variable that is being dumped
    echo '</pre>';
  }
  ```

## Advanced Custom Fields (ACF)

- ACF JSON:
  Always enable ACF JSON to automatically save and load field group configurations from a JSON file. This feature helps in version controlling field configurations and keeping them in sync across environments.

- ACF CPT/Taxonomies
  Always create "custom post type" and "custom taxonomies" via lib/post-types.php instead of using ACF.

## Woocommerce

### Overriding Templates vs. Using Hooks

- Donot simply copy the template file in `woocommerce` for small feature, if it can be overwritten by a simple function.

Example: Adding Custom Text to the Product Page

Instead of copying the `single-product.php` template to add a custom message, use a hook:

-- Not Recommended: Copying the Template File

```
cubit/woocommerce/single-product.php
```

-- Recommended: Using a Hook

```
add_action('woocommerce_single_product_summary', 'add_custom_text_to_product', 20);
function add_custom_text_to_product() {
    echo '<p class="custom-message">This is a custom message for single product pages.</p>';
}
```

## Mobile-Friendly & Optimized design

- Prioritize UX with a Mobile-Friendly Design. A WooCommerce best practice is to ensure your online store has a responsive design.

## Version Control and Branch Management

- Version Control:
- **Main Branch (`main`)**:
  - This is the production branch.
  - Only merge into `main` after code reviews and approvals.
- **Feature Branches**:
  - Each developer must create a separate branch for their work as much as possible.
  - Use the naming convention: `<developer-name>`.
  - Example: `albij`.

### Branch Strategy

- Clear commit history
  Maintain a clear commit history and use meaningful commit messages.

```
git commit -m "Changes => Integrated banner component in home page."
```

### Usage

- Access the website via your web browser at `http://localhost:3000` (or the appropriate URL for your setup).
- Follow any additional instructions specific to your project to use the application.

### Troubleshooting

- If you encounter issues, refer to the troubleshooting guide in the documentation or contact Team Lead within Cubit Incorporated.

### Contributing

- Contributions are not accepted for this repository only after confirmation and review within team. Please donot commit into this at random.
