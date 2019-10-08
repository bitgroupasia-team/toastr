<?php

use Bitgroupasia\Toastr\Toastr;

if (! function_exists('toastr')) {
    /**
     * @param string $message
     * @param string $type
     * @param string $title
     * @param array $options
     *
     * @return Toastr
     */
    function toastr(string $message = null, string $type = 'success', string $title = '', array $options = []): Toastr
    {
        if (is_null($message)) {
            return app('toastr');
        }

        return app('toastr')->addNotification($type, $message, $title, $options);
    }
}
