<?php
/**
 * Home Page Template for Rosenfield Collection
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_front_page_genesis_meta' );
function rc_front_page_genesis_meta() {



}

//* Run the Genesis loop
genesis();
