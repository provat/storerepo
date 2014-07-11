<?php

/**
 * Plugin Name: User Login & Register
 * Plugin URI: --
 * Description: Creates a simple login and register modal with an optional shortcode.
 * Version: 1.0.1
 * Author: Ankan Mitra
 * Author URI: http://capitalnumbers.com/
 * License: 
 */


/**
 * Include our abstract which is a Class of shared Methods for our Classes.
 */
require_once 'controllers/abstract.php';





/**
 * If users are allowed to register we require the registration class
 */
require_once 'controllers/register.php';


/**
 * Load the login class
 */
require_once 'controllers/login.php';


/**
 * If the admin is being displayed load the admin class and run it.
 */
if ( is_admin() ){
    require_once 'controllers/admin.php';
}




        
   
    