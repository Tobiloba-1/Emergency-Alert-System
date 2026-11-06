<?php

class RateLimiter
{
    public static function tooManyAttempts(string $key, int $max = 5, int $windowSeconds = 300): bool
    {
        $now = time();
        $bucket = $_SESSION['attempts'][$key] ?? ['count' => 0, 'first' => $now];

        if ($now - $bucket['first'] > $windowSeconds) {
            $bucket = ['count' => 0, 'first' => $now];
        }

        $bucket['count']++;
        $_SESSION['attempts'][$key] = $bucket;

        return $bucket['count'] > $max;
    }

    public static function reset(string $key): void
    {
        unset($_SESSION['attempts'][$key]);
    }
}
