# MyEduConnect - Database Documentation

This document provides detailed information about the MyEduConnect database schema, relationships, and data structures.

## Database Overview

**Database Name**: `myeduconnect`
**Character Set**: `utf8mb4`
**Collation**: `utf8mb4_unicode_ci`
**Engine**: InnoDB

## Table Descriptions

### 1. users

**Purpose**: Base user table containing authentication and basic user information.

**Columns**:
- `user_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique user identifier
- `email` (VARCHAR(255), UNIQUE, NOT NULL): User email address
- `password` (VARCHAR(255), NOT NULL): Hashed password
- `first_name` (VARCHAR(100), NOT NULL): User's first name
- `last_name` (VARCHAR(100), NOT NULL): User's last name
- `phone` (VARCHAR(20)): User's phone number
- `address` (TEXT): User's address
- `profile_picture` (VARCHAR(255)): Path to profile picture
- `role` (ENUM('student', 'teacher', 'admin'), NOT NULL): User role
- `status` (ENUM('active', 'inactive', 'suspended'), DEFAULT 'active'): Account status
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Account creation timestamp
- `updated_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE): Last update timestamp
- `last_login` (TIMESTAMP): Last login timestamp

**Indexes**:
- Primary key on `user_id`
- Unique index on `email`
- Index on `role`
- Index on `status`

**Relationships**:
- One-to-one with `students`, `teachers`, or `admins` based on role

---

### 2. students

**Purpose**: Student-specific information extending the users table.

**Columns**:
- `student_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique student identifier
- `user_id` (INT, UNIQUE, NOT NULL, FOREIGN KEY): Reference to users table
- `student_id_number` (VARCHAR(50), UNIQUE, NOT NULL): Student ID number
- `date_of_birth` (DATE): Student's date of birth
- `grade_level` (VARCHAR(50)): Student's grade level
- `parent_name` (VARCHAR(100)): Parent/guardian name
- `parent_email` (VARCHAR(255)): Parent/guardian email
- `parent_phone` (VARCHAR(20)): Parent/guardian phone
- `enrollment_date` (DATE, DEFAULT CURRENT_DATE): Date of enrollment

**Indexes**:
- Primary key on `student_id`
- Unique index on `student_id_number`
- Foreign key on `user_id` (CASCADE DELETE)

**Relationships**:
- Belongs to `users` table
- Has many `enrollments`
- Has many `payments`

---

### 3. teachers

**Purpose**: Teacher-specific information extending the users table.

**Columns**:
- `teacher_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique teacher identifier
- `user_id` (INT, UNIQUE, NOT NULL, FOREIGN KEY): Reference to users table
- `teacher_id_number` (VARCHAR(50), UNIQUE, NOT NULL): Teacher ID number
- `department` (VARCHAR(100)): Teacher's department
- `specialization` (VARCHAR(255)): Teacher's area of specialization
- `qualification` (VARCHAR(255)): Teacher's academic qualification
- `hire_date` (DATE): Date of hire
- `salary` (DECIMAL(10, 2)): Teacher's salary
- `bio` (TEXT): Teacher biography

**Indexes**:
- Primary key on `teacher_id`
- Unique index on `teacher_id_number`
- Index on `department`
- Foreign key on `user_id` (CASCADE DELETE)

**Relationships**:
- Belongs to `users` table
- Has many `courses`
- Has many `course_materials`

---

### 4. admins

**Purpose**: Administrator-specific information extending the users table.

**Columns**:
- `admin_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique admin identifier
- `user_id` (INT, UNIQUE, NOT NULL, FOREIGN KEY): Reference to users table
- `admin_id_number` (VARCHAR(50), UNIQUE, NOT NULL): Admin ID number
- `department` (VARCHAR(100)): Admin's department
- `permissions` (TEXT): JSON string of permissions
- `hire_date` (DATE): Date of hire

**Indexes**:
- Primary key on `admin_id`
- Unique index on `admin_id_number`
- Foreign key on `user_id` (CASCADE DELETE)

**Relationships**:
- Belongs to `users` table
- Has many `announcements`

---

### 5. courses

**Purpose**: Course information and details.

**Columns**:
- `course_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique course identifier
- `title` (VARCHAR(255), NOT NULL): Course title
- `description` (TEXT): Course description
- `instructor_id` (INT, NOT NULL, FOREIGN KEY): Reference to teachers table
- `category` (VARCHAR(100)): Course category
- `thumbnail` (VARCHAR(255)): Path to course thumbnail image
- `price` (DECIMAL(10, 2), DEFAULT 0.00): Course price
- `duration_weeks` (INT, DEFAULT 8): Course duration in weeks
- `max_students` (INT, DEFAULT 50): Maximum number of students
- `enrollment_count` (INT, DEFAULT 0): Current enrollment count
- `status` (ENUM('draft', 'published', 'archived'), DEFAULT 'draft'): Course status
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Course creation timestamp
- `updated_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE): Last update timestamp

**Indexes**:
- Primary key on `course_id`
- Foreign key on `instructor_id` (CASCADE DELETE)
- Index on `category`
- Index on `status`
- Index on `title`

**Relationships**:
- Belongs to `teachers` table
- Has many `enrollments`
- Has many `course_materials`
- Has many `payments`

---

### 6. enrollments

**Purpose**: Student-course enrollment records.

**Columns**:
- `enrollment_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique enrollment identifier
- `student_id` (INT, NOT NULL, FOREIGN KEY): Reference to students table
- `course_id` (INT, NOT NULL, FOREIGN KEY): Reference to courses table
- `enrollment_date` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Enrollment timestamp
- `status` (ENUM('active', 'completed', 'dropped'), DEFAULT 'active'): Enrollment status
- `progress` (INT, DEFAULT 0): Course progress percentage (0-100)

**Indexes**:
- Primary key on `enrollment_id`
- Foreign key on `student_id` (CASCADE DELETE)
- Foreign key on `course_id` (CASCADE DELETE)
- Unique constraint on (student_id, course_id)
- Index on `student_id`
- Index on `course_id`
- Index on `status`

**Relationships**:
- Belongs to `students` table
- Belongs to `courses` table

---

### 7. payments

**Purpose**: Payment transaction records.

**Columns**:
- `payment_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique payment identifier
- `student_id` (INT, NOT NULL, FOREIGN KEY): Reference to students table
- `course_id` (INT, FOREIGN KEY): Reference to courses table
- `amount` (DECIMAL(10, 2), NOT NULL): Payment amount
- `payment_method` (VARCHAR(50)): Payment method (Credit Card, PayPal, etc.)
- `transaction_id` (VARCHAR(255), UNIQUE): Transaction ID
- `status` (ENUM('pending', 'completed', 'failed', 'refunded'), DEFAULT 'pending'): Payment status
- `payment_date` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Payment timestamp

**Indexes**:
- Primary key on `payment_id`
- Foreign key on `student_id` (CASCADE DELETE)
- Foreign key on `course_id` (SET NULL)
- Unique index on `transaction_id`
- Index on `student_id`
- Index on `course_id`
- Index on `status`
- Index on `transaction_id`

**Relationships**:
- Belongs to `students` table
- Belongs to `courses` table

---

### 8. announcements

**Purpose**: Platform and course announcements.

**Columns**:
- `announcement_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique announcement identifier
- `title` (VARCHAR(255), NOT NULL): Announcement title
- `content` (TEXT, NOT NULL): Announcement content
- `author_id` (INT, NOT NULL): Author ID (admin or teacher)
- `author_type` (ENUM('admin', 'teacher'), NOT NULL): Author type
- `target_audience` (ENUM('all', 'students', 'teachers', 'specific_course'), DEFAULT 'all'): Target audience
- `course_id` (INT, FOREIGN KEY): Reference to courses table (for course-specific announcements)
- `priority` (ENUM('low', 'medium', 'high'), DEFAULT 'medium'): Announcement priority
- `status` (ENUM('draft', 'published', 'archived'), DEFAULT 'draft'): Announcement status
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Creation timestamp
- `updated_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE): Last update timestamp

**Indexes**:
- Primary key on `announcement_id`
- Foreign key on `course_id` (CASCADE DELETE)
- Index on `author_id`
- Index on `status`
- Index on `priority`
- Index on `created_at`

**Relationships**:
- Belongs to `courses` table (optional)

---

### 9. course_materials

**Purpose**: Learning materials uploaded by teachers.

**Columns**:
- `material_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique material identifier
- `course_id` (INT, NOT NULL, FOREIGN KEY): Reference to courses table
- `title` (VARCHAR(255), NOT NULL): Material title
- `description` (TEXT): Material description
- `file_name` (VARCHAR(255), NOT NULL): Original file name
- `file_path` (VARCHAR(512), NOT NULL): Server file path
- `file_type` (VARCHAR(50)): File MIME type
- `file_size` (BIGINT): File size in bytes
- `upload_date` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Upload timestamp
- `uploaded_by` (INT, NOT NULL, FOREIGN KEY): Reference to teachers table
- `material_type` (ENUM('note', 'assignment', 'video', 'resource', 'other'), DEFAULT 'resource'): Material type
- `status` (ENUM('active', 'inactive'), DEFAULT 'active'): Material status

**Indexes**:
- Primary key on `material_id`
- Foreign key on `course_id` (CASCADE DELETE)
- Foreign key on `uploaded_by` (CASCADE DELETE)
- Index on `course_id`
- Index on `material_type`
- Index on `status`

**Relationships**:
- Belongs to `courses` table
- Belongs to `teachers` table

---

### 10. audit_logs

**Purpose**: System activity logging for security and compliance.

**Columns**:
- `log_id` (INT, AUTO_INCREMENT, PRIMARY KEY): Unique log identifier
- `user_id` (INT): User ID (nullable for system actions)
- `user_type` (ENUM('student', 'teacher', 'admin', 'system')): User type
- `action` (VARCHAR(100), NOT NULL): Action performed
- `table_name` (VARCHAR(50)): Table affected
- `record_id` (INT): Record ID affected
- `old_values` (TEXT): JSON string of old values
- `new_values` (TEXT): JSON string of new values
- `ip_address` (VARCHAR(45)): IP address of the user
- `user_agent` (TEXT): User agent string
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP): Log timestamp

**Indexes**:
- Primary key on `log_id`
- Index on `user_id`
- Index on `action`
- Index on `table_name`
- Index on `created_at`

**Relationships**:
- No direct relationships (logging table)

---

## Entity Relationships

### Primary Relationships

1. **users → students/teachers/admins**: One-to-one (polymorphic)
2. **teachers → courses**: One-to-many
3. **courses → enrollments**: One-to-many
4. **students → enrollments**: One-to-many
5. **courses → course_materials**: One-to-many
6. **students → payments**: One-to-many
7. **courses → payments**: One-to-many
8. **teachers → course_materials**: One-to-many

### Relationship Diagram

```
users (1) ──── (1) students
users (1) ──── (1) teachers
users (1) ──── (1) admins

teachers (1) ──── (N) courses
courses (1) ──── (N) enrollments
students (1) ──── (N) enrollments

courses (1) ──── (N) course_materials
teachers (1) ──── (N) course_materials

students (1) ──── (N) payments
courses (1) ──── (N) payments

courses (1) ──── (N) announcements (optional)
```

## Database Views

### user_statistics
Aggregates user counts by role and status.

### course_statistics
Aggregates course enrollment statistics.

### payment_statistics
Aggregates payment totals by status.

## Stored Procedures

### AddAuditLog
Adds an entry to the audit_logs table.

### UpdateCourseEnrollment
Updates the enrollment count for a course.

## Triggers

### after_user_update
Logs user profile changes to audit_logs.

### after_enrollment_insert
Updates course enrollment count when a student enrolls.

### after_enrollment_delete
Updates course enrollment count when a student unenrolls.

## Data Integrity

### Constraints

1. **Primary Keys**: All tables have auto-increment primary keys
2. **Foreign Keys**: Referential integrity with CASCADE DELETE
3. **Unique Constraints**: Email, ID numbers, transaction IDs
4. **NOT NULL**: Required fields
5. **DEFAULT Values**: Sensible defaults for optional fields
6. **ENUM**: Restricted values for status and type fields

### Transactions

Critical operations use database transactions:
- User registration
- Course enrollment with payment
- Course creation with materials

## Sample Data

The database schema includes sample data for testing:

- 1 Administrator account
- 2 Teacher accounts
- 3 Student accounts
- 4 Sample courses
- 5 Sample enrollments
- 5 Sample payments
- 3 Sample announcements
- 5 Sample course materials
- 4 Sample audit logs

## Backup and Recovery

### Backup Strategy

```bash
# Full database backup
mysqldump -u root -p myeduconnect > backup_$(date +%Y%m%d).sql

# Compressed backup
mysqldump -u root -p myeduconnect | gzip > backup_$(date +%Y%m%d).sql.gz
```

### Recovery Strategy

```bash
# Restore from backup
mysql -u root -p myeduconnect < backup_20240101.sql

# Restore from compressed backup
gunzip < backup_20240101.sql.gz | mysql -u root -p myeduconnect
```

## Performance Optimization

### Indexes

Strategic indexes on:
- Foreign keys
- Frequently queried columns
- Search fields
- Date fields

### Query Optimization

- Use EXPLAIN to analyze queries
- Optimize JOIN operations
- Use LIMIT for pagination
- Avoid SELECT *

---

**MyEduConnect Database Documentation**
