# MyEduConnect - Learning Management System

A modern, professional Learning Management System (LMS) built with PHP 8+, MySQL, HTML5, CSS3, JavaScript, and Bootstrap 5. MyEduConnect provides a comprehensive educational platform for students, teachers, and administrators.

## Features

### For Students
- **Account Management**: Create accounts, update profiles, change passwords
- **Course Browsing**: Search and browse available courses
- **Enrollment**: Enroll in courses with mock payment system
- **Learning Materials**: Download course materials uploaded by teachers
- **Progress Tracking**: View enrollment progress and course completion status
- **Payment History**: Track all payment transactions
- **Announcements**: View platform and course announcements

### For Teachers
- **Dashboard**: Comprehensive dashboard with statistics
- **Course Management**: Create, edit, and delete courses
- **Material Upload**: Upload PDF notes, assignments, and resources
- **Student Management**: View enrolled students and their progress
- **Enrollment Statistics**: Track course enrollment numbers
- **Announcements**: Create course-specific announcements

### For Administrators
- **Platform Dashboard**: Overview of all platform statistics
- **User Management**: Create and manage students, teachers, and admins
- **Course Oversight**: View and manage all courses
- **Payment Management**: Track all platform payments and revenue
- **Audit Logs**: Monitor all system activities
- **Announcements**: Create platform-wide announcements

## Technology Stack

### Backend
- **PHP 8+**: Server-side scripting language
- **MySQL 8+**: Database management system
- **PDO**: PHP Data Objects for secure database connections
- **Apache Web Server**: Web server

### Frontend
- **HTML5**: Markup language
- **CSS3**: Styling
- **JavaScript**: Client-side scripting
- **Bootstrap 5**: UI framework for responsive design
- **Bootstrap Icons**: Icon library

### Security Features
- **Password Hashing**: Using PHP's `password_hash()` function
- **CSRF Protection**: Cross-Site Request Forgery tokens
- **Input Validation**: Sanitization and validation of all user inputs
- **Prepared Statements**: PDO prepared statements to prevent SQL injection
- **Session Security**: Secure session management with timeout
- **Role-Based Access Control**: Three user roles (Student, Teacher, Admin)
- **File Upload Validation**: Secure file upload with MIME type and size validation

## Project Structure

```
MyEduConnect/
├── config/                 # Configuration files
│   ├── config.php         # Application configuration
│   └── database.php       # Database connection class
├── controllers/           # Controller files (MVC pattern)
├── models/               # Model files (MVC pattern)
├── views/                # View files (MVC pattern)
├── assets/               # Static assets
│   ├── css/             # Custom CSS files
│   ├── js/              # JavaScript files
│   └── images/          # Image files
├── includes/             # Common includes
│   ├── functions.php    # Utility functions
│   └── auth.php         # Authentication functions
├── database/             # Database files
│   └── schema.sql       # Database schema
├── admin/                # Admin panel pages
│   ├── dashboard.php
│   ├── users.php
│   ├── create-user.php
│   ├── courses.php
│   ├── payments.php
│   ├── announcements.php
│   └── audit-logs.php
├── teacher/              # Teacher panel pages
│   ├── dashboard.php
│   ├── profile.php
│   ├── courses.php
│   ├── create-course.php
│   ├── edit-course.php
│   ├── course-materials.php
│   └── students.php
├── student/              # Student panel pages
│   ├── dashboard.php
│   ├── profile.php
│   ├── enrollments.php
│   ├── enroll.php
│   ├── course-materials.php
│   └── payments.php
├── uploads/              # Uploaded files directory
├── index.php            # Home page
├── login.php            # Login page
├── register.php         # Registration page
├── logout.php           # Logout page
├── courses.php          # Course catalog
├── about.php            # About us page
├── contact.php          # Contact us page
├── faq.php              # FAQ page
└── README.md            # This file
```

## Database Schema

The database consists of the following tables:

- **users**: Base user table with authentication information
- **students**: Student-specific information
- **teachers**: Teacher-specific information
- **admins**: Administrator-specific information
- **courses**: Course information and details
- **enrollments**: Student-course enrollment records
- **payments**: Payment transaction records
- **announcements**: Platform and course announcements
- **course_materials**: Learning materials uploaded by teachers
- **audit_logs**: System activity logs for security monitoring

See `database/schema.sql` for the complete database schema with relationships and sample data.

## Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache Web Server with mod_rewrite enabled
- Composer (optional, for dependency management)

### Step 1: Clone or Download the Project

```bash
cd /path/to/your/web/directory
# Clone or extract the MyEduConnect folder
```

### Step 2: Configure Database

1. Create a new MySQL database:
```sql
CREATE DATABASE myeduconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the database schema:
```bash
mysql -u root -p myeduconnect < database/schema.sql
```

3. Update database credentials in `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'myeduconnect');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Step 3: Configure Application

1. Update the application URL in `config/config.php`:
```php
define('APP_URL', 'http://localhost/MyEduConnect');
```

2. Ensure the `uploads` directory is writable:
```bash
chmod 755 uploads
```

### Step 4: Configure Apache

Ensure Apache is configured to allow `.htaccess` files if you're using URL rewriting.

### Step 5: Access the Application

Open your browser and navigate to:
```
http://localhost/MyEduConnect
```

## Default Login Credentials

The database schema includes sample users for testing:

### Administrator
- Email: `admin@myeduconnect.com`
- Password: `password`

### Teacher
- Email: `teacher1@myeduconnect.com`
- Password: `password`

### Student
- Email: `student1@myeduconnect.com`
- Password: `password`

**Note**: Change these passwords immediately after first login for security.

## Usage Guide

### For Students

1. **Registration**: Click "Register" on the homepage and fill in the registration form
2. **Login**: Use your email and password to log in
3. **Browse Courses**: Visit the Course Catalog to explore available courses
4. **Enroll**: Click "Enroll" on any course to complete the mock payment process
5. **Access Materials**: Go to "My Courses" to view enrolled courses and download materials
6. **Track Progress**: View your enrollment progress on the dashboard

### For Teachers

1. **Login**: Use your teacher credentials to log in
2. **Create Course**: Click "Create Course" to add a new course
3. **Upload Materials**: Go to a course and click "Manage Materials" to upload files
4. **View Students**: Check the "Students" page to see enrolled students
5. **Manage Courses**: Edit or delete courses from the "My Courses" page

### For Administrators

1. **Login**: Use your admin credentials to log in
2. **Manage Users**: Create, edit, or delete users from the Users page
3. **Monitor Platform**: View platform statistics on the dashboard
4. **Review Payments**: Check all payment transactions
5. **Audit Logs**: Monitor system activities in the Audit Logs section
6. **Create Announcements**: Post platform-wide announcements

## Security Considerations

This project implements several security features:

1. **Password Security**: All passwords are hashed using PHP's `password_hash()` function
2. **SQL Injection Prevention**: All database queries use PDO prepared statements
3. **CSRF Protection**: Forms include CSRF tokens to prevent cross-site request forgery
4. **Input Validation**: All user inputs are sanitized and validated
5. **Session Security**: Sessions have timeout and are properly managed
6. **File Upload Security**: File uploads are validated for type, size, and MIME type
7. **Role-Based Access**: Users can only access pages appropriate to their role
8. **Audit Logging**: All important actions are logged for security monitoring

**Important**: This is a demonstration/educational project. For production use, additional security measures should be implemented:
- HTTPS/SSL certificate
- Additional input validation and output encoding
- Rate limiting for login attempts
- Email verification for registration
- Two-factor authentication
- Regular security audits
- Backup and recovery procedures

## Browser Compatibility

- Chrome (latest version)
- Firefox (latest version)
- Safari (latest version)
- Edge (latest version)

## Support

For issues, questions, or contributions, please refer to the project documentation or contact the development team.

## License

This project is created for educational purposes as part of a cybersecurity assignment.

## Credits

Developed as a comprehensive Learning Management System demonstration.

---

**MyEduConnect** - Your Gateway to Quality Education
