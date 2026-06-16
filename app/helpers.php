<?php

if (!function_exists('formatDate')) {
    function formatDate($date, string $format = 'd M Y'): string
    {
        if (!$date) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($date, string $format = 'd M Y, h:i A'): string
    {
        if (!$date) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('formatPKR')) {
    function formatPKR($amount): string
    {
        return 'PKR '.number_format((float) $amount, 0);
    }
}
