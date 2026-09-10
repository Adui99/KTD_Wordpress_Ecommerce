<?php
/**
 * Template Name: KTD Contact Page Fallback
 * Description: Redirects or includes page-contact-us.php if slug is /contact/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/page-contact-us.php';
