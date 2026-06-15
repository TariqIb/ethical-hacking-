<?php
/**
 * MyEduConnect - Common Functions
 * Utility functions for the application
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Sanitize input data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return getCurrentUserRole() === 'admin';
}

/**
 * Check if user is teacher
 */
function isTeacher() {
    return getCurrentUserRole() === 'teacher';
}

/**
 * Check if user is student
 */
function isStudent() {
    return getCurrentUserRole() === 'student';
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/login.php');
        exit();
    }
}

/**
 * Require specific role
 */
function requireRole($role) {
    requireLogin();
    if (getCurrentUserRole() !== $role) {
        header('Location: ' . APP_URL . '/unauthorized.php');
        exit();
    }
}

/**
 * Redirect with message
 */
function redirect($url, $message = '', $type = 'success') {
    if (!empty($message)) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header('Location: ' . $url);
    exit();
}

/**
 * Get flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

/**
 * Format date
 */
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Generate random string
 */
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Upload file
 */
function uploadFile($file, $destination, $allowedTypes = null) {
    if ($allowedTypes === null) {
        $allowedTypes = ALLOWED_FILE_TYPES;
    }
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size exceeds maximum limit'];
    }
    
    // Check file type
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    // Generate unique filename
    $fileName = generateRandomString(16) . '.' . $fileExt;
    $filePath = $destination . $fileName;
    
    // Create directory if it doesn't exist
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    // Move file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'file_path' => $filePath, 'file_name' => $fileName];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file'];
}

/**
 * Log audit trail
 */
function logAudit($action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null) {
    $userId = getCurrentUserId();
    $userRole = getCurrentUserRole();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $query = "INSERT INTO audit_logs (user_id, user_type, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    dbExecute($query, [
        $userId,
        $userRole,
        $action,
        $tableName,
        $recordId,
        $oldValues ? json_encode($oldValues) : null,
        $newValues ? json_encode($newValues) : null,
        $ipAddress,
        $userAgent
    ]);
}

/**
 * Get user by ID
 */
function getUserById($userId) {
    $query = "SELECT * FROM users WHERE user_id = ?";
    return dbSelectOne($query, [$userId]);
}

/**
 * Get student by user ID
 */
function getStudentByUserId($userId) {
    $query = "SELECT s.*, u.* FROM students s JOIN users u ON s.user_id = u.user_id WHERE s.user_id = ?";
    return dbSelectOne($query, [$userId]);
}

/**
 * Get teacher by user ID
 */
function getTeacherByUserId($userId) {
    $query = "SELECT t.*, u.* FROM teachers t JOIN users u ON t.user_id = u.user_id WHERE t.user_id = ?";
    return dbSelectOne($query, [$userId]);
}

/**
 * Get admin by user ID
 */
function getAdminByUserId($userId) {
    $query = "SELECT a.*, u.* FROM admins a JOIN users u ON a.user_id = u.user_id WHERE a.user_id = ?";
    return dbSelectOne($query, [$userId]);
}

/**
 * Get all courses
 */
function getAllCourses($status = 'published') {
    $query = "SELECT c.*, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as instructor_name 
              FROM courses c 
              JOIN teachers t ON c.instructor_id = t.teacher_id 
              JOIN users u ON t.user_id = u.user_id
              WHERE c.status = ? 
              ORDER BY c.created_at DESC";
    return dbSelect($query, [$status]);
}

/**
 * Get course by ID
 */
function getCourseById($courseId) {
    $query = "SELECT c.*, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as instructor_name 
              FROM courses c 
              JOIN teachers t ON c.instructor_id = t.teacher_id 
              JOIN users u ON t.user_id = u.user_id 
              WHERE c.course_id = ?";
    return dbSelectOne($query, [$courseId]);
}

/**
 * Get enrolled courses for student
 */
function getEnrolledCourses($studentId) {
    $query = "SELECT e.*, c.*, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as instructor_name 
              FROM enrollments e 
              JOIN courses c ON e.course_id = c.course_id 
              JOIN teachers t ON c.instructor_id = t.teacher_id
              JOIN users u ON t.user_id = u.user_id  
              WHERE e.student_id = ? AND e.status = 'active'
              ORDER BY e.enrollment_date DESC";
    return dbSelect($query, [$studentId]);
}

/**
 * Get courses by teacher
 */
function getCoursesByTeacher($teacherId) {
    $query = "SELECT c.*, COUNT(e.enrollment_id) as enrollment_count 
              FROM courses c 
              LEFT JOIN enrollments e ON c.course_id = e.course_id 
              WHERE c.instructor_id = ? 
              GROUP BY c.course_id 
              ORDER BY c.created_at DESC";
    return dbSelect($query, [$teacherId]);
}

/**
 * Get course materials
 */
function getCourseMaterials($courseId) {
    $query = "SELECT * FROM course_materials WHERE course_id = ? AND status = 'active' ORDER BY upload_date DESC";
    return dbSelect($query, [$courseId]);
}

/**
 * Get announcements
 */
function getAnnouncements($limit = 10) {
    $query = "SELECT * FROM announcements WHERE status = 'published' ORDER BY created_at DESC LIMIT ?";
    return dbSelect($query, [$limit]);
}

/**
 * Get payments by student
 */
function getPaymentsByStudent($studentId) {
    $query = "SELECT p.*, c.title as course_title 
              FROM payments p 
              LEFT JOIN courses c ON p.course_id = c.course_id 
              WHERE p.student_id = ? 
              ORDER BY p.payment_date DESC";
    return dbSelect($query, [$studentId]);
}

/**
 * Get platform statistics
 */
function getPlatformStatistics() {
    $stats = [];
    
    // Total users by role
    $stats['total_users'] = dbSelectOne("SELECT COUNT(*) as count FROM users")['count'];
    $stats['total_students'] = dbSelectOne("SELECT COUNT(*) as count FROM users WHERE role = 'student'")['count'];
    $stats['total_teachers'] = dbSelectOne("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'")['count'];
    $stats['total_admins'] = dbSelectOne("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")['count'];
    
    // Courses
    $stats['total_courses'] = dbSelectOne("SELECT COUNT(*) as count FROM courses WHERE status = 'published'")['count'];
    $stats['total_enrollments'] = dbSelectOne("SELECT COUNT(*) as count FROM enrollments WHERE status = 'active'")['count'];
    
    // Payments
    $stats['total_payments'] = dbSelectOne("SELECT COUNT(*) as count FROM payments WHERE status = 'completed'")['count'];
    $stats['total_revenue'] = dbSelectOne("SELECT SUM(amount) as total FROM payments WHERE status = 'completed'")['total'] ?? 0;
    
    return $stats;
}

/**
 * Search courses
 */
function searchCourses($keyword)
{
    // SQL Injection Protection ON = Secure
    if (isProtectionEnabled('sqli_enabled'))
    {
        $query = "SELECT c.*, 
                         u.first_name,
                         u.last_name,
                         CONCAT(u.first_name, ' ', u.last_name) AS instructor_name
                  FROM courses c
                  JOIN teachers t ON c.instructor_id = t.teacher_id
                  JOIN users u ON t.user_id = u.user_id
                  WHERE c.status = 'published'
                  AND (
                        c.title LIKE ?
                        OR c.description LIKE ?
                        OR c.category LIKE ?
                  )
                  ORDER BY c.created_at DESC";

        $param = "%$keyword%";

        return dbSelect(
            $query,
            [$param, $param, $param]
        );
    }

    // SQL Injection Protection OFF = Vulnerable
    else
    {
        $query = "SELECT c.*, 
                         u.first_name,
                         u.last_name,
                         CONCAT(u.first_name, ' ', u.last_name) AS instructor_name
                  FROM courses c
                  JOIN teachers t ON c.instructor_id = t.teacher_id
                  JOIN users u ON t.user_id = u.user_id
                  WHERE c.status = 'published'
                  AND c.title = '$keyword'
                  ORDER BY c.created_at DESC";

        return dbSelect($query);
    }
}

/**
 * Pagination helper
 */
function paginate($query, $params, $page = 1, $perPage = ITEMS_PER_PAGE) {
    $offset = ($page - 1) * $perPage;
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM ($query) as temp";
    $total = dbSelectOne($countQuery, $params)['total'];
    
    // Get paginated results
    $paginatedQuery = $query . " LIMIT $perPage OFFSET $offset";
    $results = dbSelect($paginatedQuery, $params);
    
    return [
        'data' => $results,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage)
    ];
}

function isProtectionEnabled($name)
{
    $setting = dbSelectOne(
        "SELECT * FROM security_settings LIMIT 1"
    );

    if (!$setting) {
        return false;
    }

    return $setting[$name] == 1;
}