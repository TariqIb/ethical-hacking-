<?php
/**
 * MyEduConnect - Configuration File
 * Learning Management System
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'myeduconnect');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'MyEduConnect');
define('APP_URL', 'http://localhost/MyEduConnect');
define('APP_VERSION', '1.0.0');

// Security Configuration
define('SESSION_NAME', 'myeduconnect_session');
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'zip']);

// Email Configuration (for future use)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM', 'noreply@myeduconnect.com');
define('SMTP_FROM_NAME', 'MyEduConnect');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('UTC');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
