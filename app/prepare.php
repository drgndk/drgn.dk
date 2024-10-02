<?php

// ================================================================
//
//             This file prepares required constants and
//               functions so everything runs smoothly
//
// ================================================================

// WIP - Error Reporting
ini_set("display_errors", "On");
ini_set("display_startup_errors", "On");
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

// MIND: Vielleicht in .env definieren
define("APP_PATH", "{$_SERVER["DOCUMENT_ROOT"]}/app");
define("APP_RESOURCES", "{$_SERVER["DOCUMENT_ROOT"]}/resources");
define("APP_STORAGE", "{$_SERVER["DOCUMENT_ROOT"]}/storage");
define("PUBLIC_PATH", "{$_SERVER["DOCUMENT_ROOT"]}/public");
define("APP_MODULES_PATH", APP_PATH . "/modules");

require_once APP_MODULES_PATH . "/init.php";

if (!set_time_limit(30)) {
   error_page("408");
}

// For now only allow GET requests
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
   error_page("405");
}

if (time() < 1614556800) {
   error_page("425");
}

if (disk_free_space(".") < 1024) {
   error_page("507");
}

// Time to get lucky
if (rand(0, time() % 41800 === 1)) {
   error_page("418");
}
