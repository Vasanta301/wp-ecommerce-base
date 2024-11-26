<?php
/*
Plugin Name: Validate CF7 Emails
Plugin URI: https://www.mude.com.au
Description: Checks if there are DNS records that signal that the server accepts emails. This does not check if the specific email exists.
Version: 1.0
Author: Mude
Author URI: https://www.mude.com.au
*/


if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;

require_once __DIR__ . '/vendor/autoload.php';

class ValidateCF7Emails {
  function __construct() {
    add_filter('wpcf7_validate_email*', [$this, 'validate_cf7_email'], 10, 2);
  }

  function validate_cf7_email(WPCF7_Validation $result, WPCF7_FormTag $tag) {
    $email = (string) $_POST[$tag['name']];
    if (!$this->is_valid_email($email)) {
      $result->invalidate($tag, 'Invalid email');
    }

    return $result;
  }

  function is_valid_email(string $email) {
    $validator = new EmailValidator();
    return $validator->isValid($email, new DNSCheckValidation());
  }
}

new ValidateCF7Emails();
