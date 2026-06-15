# MyEduConnect - Architecture Documentation

This document provides an overview of the MyEduConnect system architecture, design patterns, and technical implementation details.

## System Architecture

### High-Level Architecture

MyEduConnect follows a **Model-View-Controller (MVC)** inspired architecture with a three-tier design:

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                        │
│  (HTML5, CSS3, JavaScript, Bootstrap 5)                    │
│  - User Interface                                           │
│  - Client-side Validation                                  │
│  - AJAX Requests                                           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Application Layer                         │
│  (PHP 8+)                                                  │
│  - Controllers (Request Handling)                           │
│  - Business Logic                                           │
│  - Session Management                                      │
│  - Authentication & Authorization                            │
│  - Input Validation & Sanitization                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Data Layer                              │
│  (MySQL 8+ with PDO)                                        │
│  - Database Operations                                      │
│  - Data Persistence                                         │
│  - Transaction Management                                   │
└─────────────────────────────────────────────────────────────┘
```

## MVC Pattern Implementation

### Models (Data Layer)

**Purpose**: Handle data access and business logic

**Location**: `models/` directory

**Key Functions**:
- Database queries
- Data validation
- Business rules
- Data transformation

**Example Model Structure**:
```php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($id) {
        // Database query
    }
    
    public function create($data) {
        // Create record
    }
    
    public function update($id, $data) {
        // Update record
    }
    
    public function delete($id) {
        // Delete record
    }
}
```

### Views (Presentation Layer)

**Purpose**: Display data to users and collect user input

**Location**: `views/` directory and root-level PHP files

**Key Components**:
- HTML templates
- CSS styling
- JavaScript functionality
- Bootstrap components

**View Structure**:
```php
<?php require_once 'config/config.php'; ?>
<?php require_once 'includes/functions.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <!-- Meta tags, CSS, JavaScript -->
</head>
<body>
    <!-- Navigation -->
    <!-- Main Content -->
    <!-- Footer -->
</body>
</html>
```

### Controllers (Application Logic)

**Purpose**: Handle HTTP requests, coordinate between models and views

**Location**: `controllers/` directory and root-level PHP files

**Key Responsibilities**:
- Request routing
- Input validation
- Session management
- Response generation

**Controller Flow**:
```php
// 1. Include dependencies
require_once 'config/config.php';
require_once 'includes/functions.php';

// 2. Check authentication
requireLogin();

// 3. Handle request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $result = processFormData($_POST);
    redirect($result['redirect'], $result['message']);
}

// 4. Load data
$data = getModelData();

// 5. Render view
require 'view_file.php';
```

## Database Architecture

### Database Design Principles

1. **Normalization**: Third Normal Form (3NF) to reduce redundancy
2. **Relationships**: Proper foreign key relationships
3. **Indexing**: Strategic indexing for performance
4. **Data Integrity**: Constraints and validation at database level
5. **Scalability**: Designed for future growth

### Entity-Relationship Diagram

```
┌─────────────┐
│    users     │
├─────────────┤
│ user_id (PK)│
│ email       │
│ password    │
│ first_name  │
│ last_name   │
│ role        │
│ status      │
└──────┬──────┘
       │
       ├──────────────┬──────────────┬──────────────┐
       ▼              ▼              ▼              ▼
┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│  students   │ │  teachers   │ │   admins    │ │             │
├─────────────┤ ├─────────────┤ ├─────────────┤ │             │
│ student_id  │ │ teacher_id  │ │ admin_id    │ │             │
│ user_id (FK)│ │ user_id (FK)│ │ user_id (FK)│ │             │
└──────┬──────┘ └──────┬──────┘ └──────┬──────┘ │             │
       │               │               │         │             │
       │               │               │         │             │
       ▼               ▼               │         │             │
┌─────────────┐ ┌─────────────┐       │         │             │
│ enrollments │ │   courses   │       │         │             │
├─────────────┤ ├─────────────┤       │         │             │
│ enrollment  │ │ course_id   │       │         │             │
│ student_id  │ │ instructor  │       │         │             │
│ course_id   │ │ category    │       │         │             │
└──────┬──────┘ └──────┬──────┘       │         │             │
       │               │               │         │             │
       │               │               │         │             │
       ▼               ▼               │         │             │
┌─────────────┐ ┌─────────────┐       │         │             │
│  payments   │ │course_matls │       │         │             │
├─────────────┤ ├─────────────┤       │         │             │
│ payment_id  │ │ material_id │       │         │             │
│ student_id  │ │ course_id   │       │         │             │
│ course_id   │ │ file_name   │       │         │             │
└─────────────┘ └─────────────┘       │         │             │
                                     │         │             │
                                     ▼         ▼             ▼
                              ┌─────────────────────────────┐
                              │      announcements        │
                              ├─────────────────────────────┤
                              │ announcement_id          │
                              │ author_id                 │
                              │ target_audience          │
                              └─────────────────────────────┘
```

### Database Schema Overview

**Core Tables**:

1. **users**: Base authentication table
   - Stores login credentials and basic user information
   - Supports three roles: student, teacher, admin

2. **students**: Student-specific data
   - Extends users table with student information
   - Contains academic details and parent information

3. **teachers**: Teacher-specific data
   - Extends users table with teacher information
   - Contains professional details and qualifications

4. **admins**: Administrator-specific data
   - Extends users table with admin information
   - Contains department and permission details

**Course Management Tables**:

5. **courses**: Course information
   - Stores course details, pricing, and enrollment limits
   - Links to teachers via instructor_id

6. **enrollments**: Student-course relationships
   - Tracks which students are enrolled in which courses
   - Records enrollment status and progress

7. **course_materials**: Learning materials
   - Stores uploaded files and resources
   - Links to courses and teachers

**Financial Tables**:

8. **payments**: Payment transactions
   - Records all payment transactions
   - Links to students and courses

**Communication Tables**:

9. **announcements**: Platform communications
   - Stores announcements for different audiences
   - Supports priority levels and targeting

**Security Tables**:

10. **audit_logs**: System activity tracking
    - Records all important system actions
    - Used for security monitoring and compliance

## Security Architecture

### Authentication Flow

```
┌──────────────┐
│   User       │
│  Input       │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Validation │
│  - Email     │
│  - Password  │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Database    │
│  Lookup      │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ Password     │
│ Verification│
│ (hash_check) │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Session     │
│  Creation    │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│   Redirect   │
│  to Dashboard│
└──────────────┘
```

### Security Layers

1. **Input Validation Layer**
   - Server-side validation
   - Input sanitization
   - Type checking

2. **Authentication Layer**
   - Password hashing (bcrypt)
   - Session management
   - CSRF protection

3. **Authorization Layer**
   - Role-based access control
   - Permission checking
   - Resource ownership verification

4. **Data Protection Layer**
   - Prepared statements (SQL injection prevention)
   - Output escaping (XSS prevention)
   - File upload validation

5. **Audit Layer**
   - Activity logging
   - Security monitoring
   - Compliance tracking

## File Upload Architecture

### Upload Flow

```
┌──────────────┐
│  User Selects│
│     File      │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Client-side │
│  Validation  │
│  (optional)  │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Server-side │
│  Validation  │
│  - Size      │
│  - Type      │
│  - MIME      │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  File Rename │
│  (unique ID) │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Move to     │
│  Upload Dir  │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Database    │
│  Record      │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Success     │
│  Response    │
└──────────────┘
```

### Upload Security Measures

1. **File Type Validation**
   - Extension checking
   - MIME type verification
   - Magic number validation

2. **File Size Limits**
   - Maximum file size: 5MB
   - Configurable in config file

3. **File Naming**
   - Unique random filenames
   - Prevents overwriting
   - Prevents path traversal

4. **Directory Structure**
   - Organized by course ID
   - Separate from web root
   - Proper permissions

## Session Management

### Session Configuration

```php
define('SESSION_NAME', 'myeduconnect_session');
define('SESSION_LIFETIME', 3600); // 1 hour
```

### Session Lifecycle

1. **Creation**: On successful login
2. **Validation**: On each page load
3. **Timeout**: After inactivity period
4. **Destruction**: On logout or timeout

### Session Data Structure

```php
$_SESSION = [
    'user_id' => int,
    'user_email' => string,
    'user_name' => string,
    'user_role' => string,
    'logged_in' => boolean,
    'login_time' => timestamp,
    'csrf_token' => string
];
```

## API Architecture (Future Enhancement)

The current implementation uses traditional PHP pages. Future versions could implement a RESTful API:

```
┌──────────────┐
│   Client     │
│  (Browser/Mobile)│
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  API Gateway │
│  (Optional)  │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  API Routes  │
│  /api/users  │
│  /api/courses│
│  /api/payments│
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ Controllers  │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│   Models     │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│  Database    │
└──────────────┘
```

## Performance Considerations

### Database Optimization

1. **Indexing**: Strategic indexes on frequently queried columns
2. **Query Optimization**: Efficient SQL queries
3. **Connection Pooling**: PDO connection reuse
4. **Caching**: Future implementation of caching layer

### Frontend Optimization

1. **Minification**: CSS and JavaScript minification
2. **Image Optimization**: Compressed images
3. **Lazy Loading**: Deferred loading of resources
4. **CDN**: Content Delivery Network for static assets

### Server Optimization

1. **PHP OPcache**: Bytecode caching
2. **Gzip Compression**: Response compression
3. **HTTP/2**: Protocol upgrade
4. **Load Balancing**: For high-traffic deployments

## Scalability Architecture

### Horizontal Scaling

- Stateless session design
- Database replication
- Load balancing
- Microservices architecture (future)

### Vertical Scaling

- Increased server resources
- Database optimization
- Caching layer
- CDN integration

## Monitoring and Logging

### Application Logging

- Error logging
- Audit logging
- Performance logging
- Security event logging

### Monitoring Metrics

- Response times
- Error rates
- User activity
- Resource utilization

---

**MyEduConnect Architecture Documentation**
