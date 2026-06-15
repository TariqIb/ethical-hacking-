<?php
/**
 * MyEduConnect - Logout Page
 * Learning Management System
 */

require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Logout user
logout();

// Redirect to home page with message
redirect(APP_URL . '/login.php', 'You have been logged out successfully.', 'success');
