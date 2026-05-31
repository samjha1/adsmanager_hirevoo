<?php
/**
 * Laravel — PHP built-in dev server router.
 *
 * Use:  php -S 127.0.0.1:8001 server.php
 *
 * Without this file, PHP's built-in server short-circuits static-looking
 * URLs (e.g. /api/track/impression/{key}.gif) and returns 404 instead of
 * routing through public/index.php. This shim forwards everything to the
 * Laravel front controller while still serving real files in /public.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri) && ! is_dir(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
