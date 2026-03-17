<?php
/**
 * Security Helper Functions
 * Provides CSRF protection, input validation, and security utilities
 */

// Generate CSRF token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate password strength
function validatePasswordStrength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    return $errors;
}

// Validate file upload
function validateFileUpload($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], $maxSize = 5242880) {
    $errors = [];
    
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        $errors[] = 'No file uploaded';
        return $errors;
    }
    
    // Check file size (default 5MB)
    if ($file['size'] > $maxSize) {
        $errors[] = 'File size exceeds maximum allowed size';
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        $errors[] = 'File type not allowed';
    }
    
    // Verify file is actually an image
    if (strpos($mimeType, 'image/') !== 0) {
        $errors[] = 'Uploaded file is not a valid image';
    }
    
    return $errors;
}

// Generate safe filename
function generateSafeFilename($originalName) {
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $name = pathinfo($originalName, PATHINFO_FILENAME);
    
    // Remove special characters and replace spaces
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $name));
    
    // Add timestamp and random string for uniqueness
    $safe = $name . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    
    return $safe;
}

// Escape output for HTML
function escapeHTML($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Escape output for JavaScript
function escapeJS($data) {
    return json_encode($data);
}

// Escape output for URL
function escapeURL($data) {
    return urlencode($data);
}

// Check if user is authenticated
function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Require authentication
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: login.php');
        exit;
    }
}

// Set security headers
function setSecurityHeaders() {
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Enable XSS protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' https://fonts.gstatic.com; connect-src 'self' https:;");
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions Policy
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

// Log security event
function logSecurityEvent($event, $details = []) {
    $logFile = __DIR__ . '/logs/security.log';
    
    // Create logs directory if it doesn't exist
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $user = $_SESSION['user_id'] ?? 'GUEST';
    
    $logEntry = json_encode([
        'timestamp' => $timestamp,
        'event' => $event,
        'user_id' => $user,
        'ip_address' => $ip,
        'details' => $details
    ]) . PHP_EOL;
    
    error_log($logEntry, 3, $logFile);
}

// Rate limiting helper
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    $key = 'ratelimit_' . $identifier;
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'first_attempt' => time()
        ];
    }
    
    $data = $_SESSION[$key];
    $now = time();
    
    // Reset if time window has passed
    if ($now - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = [
            'attempts' => 1,
            'first_attempt' => $now
        ];
        return true;
    }
    
    // Check if limit exceeded
    if ($data['attempts'] >= $maxAttempts) {
        return false;
    }
    
    // Increment attempts
    $_SESSION[$key]['attempts']++;
    return true;
}
