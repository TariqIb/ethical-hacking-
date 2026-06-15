<?php

require_once '../config/config.php';
require_once '../includes/functions.php';

echo isProtectionEnabled('sqli_enabled')
    ? 'SQL Protection ON'
    : 'SQL Protection OFF';