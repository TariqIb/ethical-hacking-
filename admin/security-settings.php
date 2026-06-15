<?php

require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireRole('admin');

$settings = dbSelectOne(
    "SELECT * FROM security_settings LIMIT 1"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    dbUpdate(
        "UPDATE security_settings
        SET
        sqli_enabled=?,
        xss_enabled=?,
        idor_enabled=?,
        weak_auth_enabled=?,
        weak_password_enabled=?,
        file_exposure_enabled=?,
        weak_session_enabled=?",
        [
            isset($_POST['sqli_enabled']) ? 1 : 0,
            isset($_POST['xss_enabled']) ? 1 : 0,
            isset($_POST['idor_enabled']) ? 1 : 0,
            isset($_POST['weak_auth_enabled']) ? 1 : 0,
            isset($_POST['weak_password_enabled']) ? 1 : 0,
            isset($_POST['file_exposure_enabled']) ? 1 : 0,
            isset($_POST['weak_session_enabled']) ? 1 : 0
        ]
    );

    header("Location: security-settings.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Security Settings</title>
</head>
<body>

<h2>Security Control Panel</h2>

<form method="POST">

<label>
<input type="checkbox"
name="sqli_enabled"
<?= $settings['sqli_enabled'] ? 'checked' : '' ?>>
SQL Injection Protection
</label>

<br><br>

<label>
<input type="checkbox"
name="xss_enabled"
<?= $settings['xss_enabled'] ? 'checked' : '' ?>>
XSS Protection
</label>

<br><br>

<label>
<input type="checkbox"
name="idor_enabled"
<?= $settings['idor_enabled'] ? 'checked' : '' ?>>
IDOR Protection
</label>

<br><br>

<label>
<input type="checkbox"
name="weak_auth_enabled"
<?= $settings['weak_auth_enabled'] ? 'checked' : '' ?>>
Authentication Protection
</label>

<br><br>

<label>
<input type="checkbox"
name="weak_password_enabled"
<?= $settings['weak_password_enabled'] ? 'checked' : '' ?>>
Password Protection
</label>

<br><br>

<label>
<input type="checkbox"
name="file_exposure_enabled"
<?= $settings['file_exposure_enabled'] ? 'checked' : '' ?>>
File Protection
</label>

<br><br>

<label>
<input type="checkbox"
name="weak_session_enabled"
<?= $settings['weak_session_enabled'] ? 'checked' : '' ?>>
Session Protection
</label>

<br><br>

<button type="submit">
Save Settings
</button>

</form>

</body>
</html>