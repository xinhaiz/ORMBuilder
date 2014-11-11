<?php

namespace Lib;

final class State {

    /**
     * @param string $message
     * @param int $newline
     */
    public static function notice($message, $newline = true) {
        $notice = (strcasecmp(PHP_OS, 'linux') === 0 ? shell_exec('echo -e "\033[0;36m' . $message . '\033[0m"') : $message);
        echo ($newline === true ? $notice : trim($notice));
    }

    /**
     * @param string $message
     * @param int $level
     */
    public static function warning($message) {
        echo (strcasecmp(PHP_OS, 'linux') === 0 ? shell_exec('echo -e "\033[0;33m[Warning] ' . $message . '\033[0m"') : $message);
    }

    /**
     * @param string $message
     * @param int $level
     */
    public static function error($message) {
        $message = (strcasecmp(PHP_OS, 'linux') === 0 ? shell_exec('echo -e "\033[0;31m[Error] ' . $message . '\033[0m"') : $message);
        throw new \Lib\Exception($message);
    }
}