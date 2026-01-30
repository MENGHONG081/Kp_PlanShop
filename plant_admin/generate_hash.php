<?php
// Run this file once to generate a password hash for '123456'
$hash = password_hash('123456', PASSWORD_DEFAULT);
echo $hash;
