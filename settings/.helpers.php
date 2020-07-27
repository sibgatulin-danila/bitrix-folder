<?php

/*
 * Прерывает работу скрипта, запущенного не из командной строки
 */
function testCli() {
    if (php_sapi_name() !== 'cli') {
        echo "No FPM, please...";
        die();
    }
}
