<?php
require_once('config.inc.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<pre>Session ID: " . session_id() . "\n";
print_r($_SESSION);
echo "</pre>";
?>
