# Security Best Practices Guide

This guide provides comprehensive security best practices for the KP Plant Shop project.

## Table of Contents

1. [Authentication & Authorization](#authentication--authorization)
2. [Input Validation & Sanitization](#input-validation--sanitization)
3. [Output Encoding](#output-encoding)
4. [Database Security](#database-security)
5. [File Upload Security](#file-upload-security)
6. [Session Management](#session-management)
7. [HTTPS & Transport Security](#https--transport-security)
8. [API Security](#api-security)
9. [Error Handling](#error-handling)
10. [Logging & Monitoring](#logging--monitoring)

---

## Authentication & Authorization

### Password Security

**Requirements**:
- Minimum 8 characters
- Mix of uppercase and lowercase letters
- At least one number
- At least one special character
- Use bcrypt with cost factor of 12

**Implementation**:
```php
// Hashing
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Verification
if (password_verify($input, $hash)) {
    // Password matches
}
```

### Session Management

**Secure Configuration**:
```php
ini_set('session.cookie_httponly', 1);      // Prevent JavaScript access
ini_set('session.cookie_secure', 1);        // HTTPS only
ini_set('session.cookie_samesite', 'Strict'); // CSRF protection
```

**Session Regeneration**:
```php
// After successful login
session_regenerate_id(true);

// Store login time
$_SESSION['login_time'] = time();
```

### Authorization Checks

**Always verify user ownership**:
```php
// Verify order belongs to current user
$stmt = $pdo->prepare("SELECT user_id FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if ($order['user_id'] != $_SESSION['user_id']) {
    die('Unauthorized');
}
```

---

## Input Validation & Sanitization

### Validation Strategy

**Always validate on the server side**:
```php
// Never trust client-side validation alone
if (empty($_POST['email'])) {
    $errors[] = 'Email is required';
}

if (!validateEmail($_POST['email'])) {
    $errors[] = 'Invalid email format';
}
```

### Common Validations

**Email**:
```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email';
}
```

**Phone Number**:
```php
if (!preg_match('/^[0-9\-\+\(\)\s]{7,}$/', $phone)) {
    $errors[] = 'Invalid phone number';
}
```

**Numeric Values**:
```php
if (!is_numeric($amount) || floatval($amount) <= 0) {
    $errors[] = 'Invalid amount';
}
```

**URL**:
```php
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    $errors[] = 'Invalid URL';
}
```

### Sanitization

**Remove HTML special characters**:
```php
$safe = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
```

**Trim whitespace**:
```php
$input = trim($input);
```

**Use prepared statements** (prevents SQL injection):
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

---

## Output Encoding

### Context-Specific Encoding

**HTML Context**:
```php
<p><?php echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8'); ?></p>
```

**JavaScript Context**:
```php
<script>
var data = <?php echo json_encode($userInput); ?>;
</script>
```

**URL Context**:
```php
<a href="page.php?id=<?php echo urlencode($id); ?>">Link</a>
```

**Attribute Context**:
```php
<div data-value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"></div>
```

---

## Database Security

### Prepared Statements

**Always use prepared statements**:
```php
// GOOD - Safe from SQL injection
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// BAD - Vulnerable to SQL injection
$query = "SELECT * FROM users WHERE email = '$email'";
```

### Parameterized Queries

**Named parameters**:
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND status = :status");
$stmt->execute([':email' => $email, ':status' => 'active']);
```

### Principle of Least Privilege

**Database user permissions**:
- Create separate database user for application
- Grant only necessary permissions
- Never use root/admin account
- Use read-only user for SELECT queries

### Connection Security

```php
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
```

---

## File Upload Security

### Validation Checklist

**Before accepting any file**:
- [ ] Check file size
- [ ] Verify MIME type using `finfo`
- [ ] Check file extension
- [ ] Verify file is actually what it claims to be
- [ ] Generate safe filename
- [ ] Store outside web root if possible
- [ ] Set proper file permissions

### Implementation

```php
function validateFileUpload($file, $allowedTypes = [], $maxSize = 5242880) {
    $errors = [];
    
    // Check size
    if ($file['size'] > $maxSize) {
        $errors[] = 'File too large';
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        $errors[] = 'File type not allowed';
    }
    
    return $errors;
}
```

### Safe Filename Generation

```php
function generateSafeFilename($originalName) {
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $name = pathinfo($originalName, PATHINFO_FILENAME);
    
    // Remove special characters
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
    
    // Add timestamp and random string
    return $name . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
}
```

---

## Session Management

### Session Timeout

**Implement session timeout**:
```php
$timeout = 1800; // 30 minutes

if (isset($_SESSION['login_time']) && time() - $_SESSION['login_time'] > $timeout) {
    session_destroy();
    header('Location: login.php?expired=1');
    exit;
}

// Update login time on activity
$_SESSION['login_time'] = time();
```

### Session Fixation Prevention

**Regenerate session ID on login**:
```php
session_regenerate_id(true);
```

### Session Data Validation

**Verify session data integrity**:
```php
// Store user agent hash
$_SESSION['user_agent'] = md5($_SERVER['HTTP_USER_AGENT']);

// Verify on each request
if ($_SESSION['user_agent'] !== md5($_SERVER['HTTP_USER_AGENT'])) {
    session_destroy();
    die('Session compromised');
}
```

---

## HTTPS & Transport Security

### Configuration

**Force HTTPS**:
```php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}
```

### Security Headers

**Set security headers**:
```php
// Prevent clickjacking
header('X-Frame-Options: SAMEORIGIN');

// Prevent MIME type sniffing
header('X-Content-Type-Options: nosniff');

// XSS protection
header('X-XSS-Protection: 1; mode=block');

// Content Security Policy
header("Content-Security-Policy: default-src 'self'");

// HSTS (HTTP Strict Transport Security)
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

---

## API Security

### CSRF Protection

**All state-changing requests must verify CSRF token**:
```php
if ($_POST) {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token invalid');
    }
}
```

### Rate Limiting

**Prevent brute force attacks**:
```php
if (!checkRateLimit('login_' . $email, 5, 300)) {
    http_response_code(429);
    die('Too many attempts');
}
```

### API Authentication

**Use tokens for API access**:
```php
// Verify API token
$token = $_SERVER['HTTP_X_API_TOKEN'] ?? '';
if (!verifyAPIToken($token)) {
    http_response_code(401);
    die('Unauthorized');
}
```

### CORS Configuration

**Restrict cross-origin requests**:
```php
header('Access-Control-Allow-Origin: https://trusted-domain.com');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 3600');
```

---

## Error Handling

### User-Friendly Error Messages

**Never expose sensitive information**:
```php
// BAD - Exposes database structure
echo "Error: " . $e->getMessage();

// GOOD - Generic message
echo "An error occurred. Please try again later.";
```

### Error Logging

**Log detailed errors internally**:
```php
error_log(
    json_encode([
        'timestamp' => date('Y-m-d H:i:s'),
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'user_id' => $_SESSION['user_id'] ?? null
    ]),
    3,
    '/var/log/app_errors.log'
);
```

### Exception Handling

**Use try-catch blocks**:
```php
try {
    // Database operation
} catch (PDOException $e) {
    logSecurityEvent('database_error', ['error' => 'Database error']);
    die('An error occurred. Please try again later.');
}
```

---

## Logging & Monitoring

### Security Events to Log

**Always log**:
- Successful logins
- Failed login attempts
- Password changes
- Permission changes
- File uploads
- Payment transactions
- CSRF token mismatches
- Rate limit violations
- Unauthorized access attempts

### Logging Implementation

```php
function logSecurityEvent($event, $details = []) {
    $logEntry = json_encode([
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'user_id' => $_SESSION['user_id'] ?? 'GUEST',
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'details' => $details
    ]) . PHP_EOL;
    
    error_log($logEntry, 3, '/var/log/security.log');
}
```

### Log Monitoring

**Review logs regularly**:
- Check for failed login patterns
- Monitor for unusual activity
- Track file uploads
- Monitor rate limit violations
- Alert on security events

### Log Rotation

**Implement log rotation**:
```bash
# /etc/logrotate.d/app
/var/log/security.log {
    daily
    rotate 30
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}
```

---

## Security Checklist

### Before Deployment

- [ ] All passwords use bcrypt with cost 12
- [ ] All forms have CSRF tokens
- [ ] All inputs are validated server-side
- [ ] All outputs are properly escaped
- [ ] All database queries use prepared statements
- [ ] Session cookies are HttpOnly and Secure
- [ ] HTTPS is enforced
- [ ] Security headers are set
- [ ] Error messages are generic
- [ ] Sensitive errors are logged
- [ ] File uploads are validated
- [ ] Rate limiting is implemented
- [ ] Logs are monitored
- [ ] Database user has least privilege
- [ ] Dependencies are up to date

### Regular Maintenance

- [ ] Review security logs weekly
- [ ] Update dependencies monthly
- [ ] Run security scans quarterly
- [ ] Conduct penetration testing annually
- [ ] Review access logs for anomalies
- [ ] Update security policies as needed

---

## Additional Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Manual](https://www.php.net/manual/en/security.php)
- [MDN Web Security](https://developer.mozilla.org/en-US/docs/Web/Security)
- [CWE Top 25](https://cwe.mitre.org/top25/)

---

**Last Updated**: March 17, 2026
**Version**: 1.0
