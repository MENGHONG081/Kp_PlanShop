<?php
include 'chat.php'; // This runs your loader
echo "Searching for key at: " . __DIR__ . "/.env<br>";
echo "API Key found: " . (getenv('GEMINI_API_KEY') ? "YES (Starts with ".substr(getenv('GEMINI_API_KEY'),0,5).")" : "NO");