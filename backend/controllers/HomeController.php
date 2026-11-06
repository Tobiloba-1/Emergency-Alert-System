<?php

class HomeController
{
    public static function index(): array
    {
        if (Session::isLoggedIn()) {
            $destination = match (Session::role()) {
                'admin'    => '/admin/dashboard',
                'security' => '/security/dashboard',
                default    => '/user/dashboard',
            };
            header('Location: ' . APP_URL . $destination);
            exit;
        }

        return ['page_title' => 'Home'];
    }
}