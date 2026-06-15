# MyEduConnect - Installation Guide

This guide will walk you through the installation process for MyEduConnect Learning Management System.

## System Requirements

### Minimum Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 8.0 or higher
- **Apache**: 2.4 or higher with mod_rewrite enabled
- **RAM**: 512MB minimum (1GB recommended)
- **Disk Space**: 100MB minimum

### Recommended Requirements
- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher
- **Apache**: 2.4 or higher
- **RAM**: 2GB or higher
- **Disk Space**: 500MB or higher

### Required PHP Extensions
- PDO
- PDO_MySQL
- mbstring
- json
- session
- fileinfo
- gd (for image processing, optional)

## Installation Steps

### Step 1: Download the Project

1. Download the MyEduConnect project files
2. Extract the files to your web server directory
3. Ensure the directory structure is preserved

Example for XAMPP:
```
C:\xampp\htdocs\MyEduConnect\
```

Example for Linux:
```
/var/www/html/MyEduConnect/
```

### Step 2: Configure MySQL Database

#### Option A: Using phpMyAdmin

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Click on "New" to create a new database
3. Enter database name: `myeduconnect`
4. Select collation: `utf8mb4_unicode_ci`
5. Click "Create"
6. Select the newly created database
7. Click on "Import" tab
8. Choose the `database/schema.sql` file from the project
9. Click "Go" to import the schema

#### Option B: Using MySQL Command Line

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE myeduconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Select database
USE myeduconnect;

# Import schema
SOURCE /path/to/MyEduConnect/database/schema.sql;

# Exit
EXIT;
```

#### Option C: Using MySQL from Command Line (Single Command)

```bash
mysql -u root -p myeduconnect < /path/to/MyEduConnect/database/schema.sql
```

### Step 3: Configure Database Connection

1. Open `config/config.php` in a text editor
2. Locate the database configuration section
3. Update the following values:

```php
// Database Configuration
define('DB_HOST', 'localhost');        // Your database host
define('DB_NAME', 'myeduconnect');     // Your database name
define('DB_USER', 'root');             // Your database username
define('DB_PASS', '');                 // Your database password
define('DB_CHARSET', 'utf8mb4');       // Database charset
```

**Note**: If you're using XAMPP, the default MySQL password is usually empty. If you're using a different setup, use your MySQL credentials.

### Step 4: Configure Application Settings

1. Still in `config/config.php`, update the application settings:

```php
// Application Configuration
define('APP_NAME', 'MyEduConnect');
define('APP_URL', 'http://localhost/MyEduConnect');  // Update this to your actual URL
define('APP_VERSION', '1.0.0');
```

**Important**: Make sure `APP_URL` matches your actual web server URL.

### Step 5: Set File Permissions

#### Linux/macOS

```bash
# Make uploads directory writable
chmod 755 uploads

# Make uploads subdirectories writable
chmod 755 uploads/course*

# Alternatively, for development:
chmod 777 uploads
```

#### Windows

1. Right-click on the `uploads` folder
2. Select "Properties"
3. Go to "Security" tab
4. Click "Edit"
5. Add "IIS_IUSRS" or your web server user
6. Grant "Write" permissions

### Step 6: Configure Apache (Optional)

If you want clean URLs, ensure mod_rewrite is enabled:

1. Open Apache configuration file (`httpd.conf`)
2. Uncomment the following line:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```
3. Restart Apache

### Step 7: Test the Installation

1. Open your web browser
2. Navigate to: `http://localhost/MyEduConnect`
3. You should see the MyEduConnect homepage

### Step 8: Test Login

Use the default credentials to test login:

**Administrator:**
- Email: `admin@myeduconnect.com`
- Password: `password`

**Teacher:**
- Email: `teacher1@myeduconnect.com`
- Password: `password`

**Student:**
- Email: `student1@myeduconnect.com`
- Password: `password`

**Security Note**: Change these passwords immediately after first login!

## Troubleshooting

### Issue: Database Connection Failed

**Solution:**
1. Verify MySQL is running
2. Check database credentials in `config/config.php`
3. Ensure the database exists
4. Check MySQL user permissions

### Issue: Session Not Working

**Solution:**
1. Ensure `session_save_path` is writable
2. Check PHP session configuration in `php.ini`
3. Verify cookies are enabled in your browser

### Issue: File Upload Not Working

**Solution:**
1. Check `uploads` directory permissions
2. Verify `upload_max_filesize` and `post_max_size` in `php.ini`
3. Ensure disk space is available

### Issue: CSS/JavaScript Not Loading

**Solution:**
1. Verify `APP_URL` in `config/config.php` is correct
2. Check file paths in HTML
3. Clear browser cache

### Issue: 404 Errors

**Solution:**
1. Verify Apache mod_rewrite is enabled
2. Check `.htaccess` file exists (if using)
3. Verify file permissions

## Development Setup

### For Local Development (XAMPP)

1. Download and install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services from XAMPP Control Panel
3. Place MyEduConnect in `htdocs` folder
4. Follow the installation steps above

### For Local Development (WAMP)

1. Download and install WAMP from https://www.wampserver.com/
2. Start WAMP server
3. Place MyEduConnect in `www` folder
4. Follow the installation steps above

### For Local Development (Linux - LAMP)

```bash
# Install Apache, MySQL, PHP
sudo apt-get update
sudo apt-get install apache2 mysql-server php php-mysql php-pdo php-mbstring

# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Place MyEduConnect in /var/www/html/
sudo cp -r MyEduConnect /var/www/html/

# Set permissions
sudo chown -R www-data:www-data /var/www/html/MyEduConnect
sudo chmod -R 755 /var/www/html/MyEduConnect
```

### For Local Development (macOS - MAMP)

1. Download and install MAMP from https://www.mamp.info/
2. Start MAMP server
3. Place MyEduConnect in `htdocs` folder
4. Follow the installation steps above

## Production Deployment

### Pre-Deployment Checklist

- [ ] Change all default passwords
- [ ] Update `APP_URL` to production domain
- [ ] Enable HTTPS/SSL
- [ ] Set proper file permissions
- [ ] Configure error reporting (set to 0 in production)
- [ ] Enable database backups
- [ ] Configure email settings
- [ ] Test all functionality
- [ ] Review security settings

### Deployment Steps

1. **Upload Files**: Upload all files to your production server
2. **Configure Database**: Create production database and import schema
3. **Update Configuration**: Update `config/config.php` with production settings
4. **Set Permissions**: Configure proper file permissions
5. **Test**: Thoroughly test all functionality
6. **Monitor**: Set up monitoring and logging

### Security Considerations for Production

1. **HTTPS**: Enable SSL certificate
2. **Firewall**: Configure firewall rules
3. **Regular Backups**: Set up automated database backups
4. **Security Headers**: Implement security headers
5. **Rate Limiting**: Implement rate limiting for login attempts
6. **Input Validation**: Ensure all inputs are properly validated
7. **Error Handling**: Disable detailed error messages in production

## Post-Installation

### First Steps After Installation

1. **Change Default Passwords**: Log in as admin and change all default passwords
2. **Create Users**: Create necessary teacher and student accounts
3. **Configure Email**: Set up email configuration for notifications
4. **Test Features**: Test all major features
5. **Customize**: Customize the platform appearance and settings

### Recommended Customizations

1. **Branding**: Update logo, colors, and company name
2. **Email Templates**: Customize email notification templates
3. **Default Content**: Add default courses and announcements
4. **User Roles**: Configure user permissions as needed
5. **Settings**: Adjust platform settings to match your requirements

## Support

If you encounter issues during installation:

1. Check the troubleshooting section above
2. Review error logs in Apache error log
3. Check PHP error log
4. Verify all file permissions
5. Ensure all PHP extensions are enabled

## Additional Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Apache Documentation](https://httpd.apache.org/docs/)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)

---

**MyEduConnect Installation Guide**
