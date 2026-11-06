<?php

class View
{
    /**
     * Renders a view from /frontend/views, wrapped in the shared head/foot partials.
     * $data keys are extracted into local variables available inside the view.
     */
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        $viewsRoot = dirname(__DIR__, 2) . '/frontend/views';

        require $viewsRoot . '/partials/head.php';
        require $viewsRoot . "/$view.php";
        require $viewsRoot . '/partials/foot.php';
    }
}