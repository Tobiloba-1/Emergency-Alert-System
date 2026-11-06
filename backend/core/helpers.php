<?php
/** Safe output escaping for views. */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/** Tailwind classes for a severity badge. */
function severity_badge_classes(string $severity): string
{
    return match ($severity) {
        'critical' => 'bg-alert/10 text-alert border border-alert/30',
        'warning'  => 'bg-warning/10 text-warning border border-warning/30',
        default    => 'bg-navy/10 text-navy border border-navy/30',
    };
}

/** Tailwind classes for an SOS status badge. */
function sos_status_badge_classes(string $status): string
{
    return match ($status) {
        'active'       => 'bg-alert/10 text-alert border border-alert/30',
        'acknowledged' => 'bg-warning/10 text-warning border border-warning/30',
        default        => 'bg-confirm/10 text-confirm border border-confirm/30',
    };
}