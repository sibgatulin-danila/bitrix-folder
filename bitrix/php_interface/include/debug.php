<?
define('IS_DEBUG_FILE', $_SERVER["DOCUMENT_ROOT"] . '/test/catalog/debug.txt');

function isDebug() {
//    return true;
    global $USER;
//    if ($USER->IsAdmin() or (isset($_COOKIE['is-debug-3985']) or $_COOKIE['is-debug-3985'] == '2352536'))
    if (CUser::GetID() == 2793 or ( isset($_COOKIE['is-debug-3985']) and $_COOKIE['is-debug-3985'] == '2352536'))
        return true;
    return false;
}

function preTrace() {
    if (!isDebug())
        return;
    $e = new Exception();
    ?><pre><?= $e->getTraceAsString() ?></pre><?
}

function preTime() {
    if (!isDebug())
        return;
    if (func_num_args()) {
        pre(func_get_args());
    }
    pre(date('d.m.Y H:i:s') . ' ' . (memory_get_usage(true) / 1024 / 2014));
}

function pre() {
    if (!isDebug())
        return;
    echo '<pre>';
    foreach (func_get_args() as $value)
        if (is_array($value) or is_object($value)) {
            print_r($value);
        } else {
            var_dump($value);
        }
    echo '</pre>';
}

function preDebugStart() {
    file_put_contents(IS_DEBUG_FILE, PHP_EOL . date('d.m.Y H:i:s'), FILE_APPEND);
}

if (defined('IS_DEBUG')) {
    preDebugStart();
}

function preDebug() {
//    if (!isDebug())
//        return;
    ob_start();
    echo '<pre>';
    foreach (func_get_args() as $value)
        if (is_array($value) or is_object($value)) {
            print_r($value);
        } else {
            var_dump($value);
        }
    echo '</pre>';
    file_put_contents(IS_DEBUG_FILE, PHP_EOL . ob_get_clean(), FILE_APPEND);
}

function preExit() {
    if (!isDebug())
        return;
    echo '<pre>';
    foreach (func_get_args() as $value)
        if (is_array($value) or is_object($value)) {
            print_r($value);
        } else {
            var_dump($value);
        }
    echo '</pre>';
    exit;
}

function preMemory() {
    pre('memory: ' . round(memory_get_usage() / 1024 / 1024, 3));
}
?>