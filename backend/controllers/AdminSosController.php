<?php

class AdminSosController
{
    public static function index(): array
    {
        Session::requireLogin(['admin']);

        return [
            'page_title' => 'SOS Alerts',
            'sos_alerts' => SosAlert::findAllDetailed(),
        ];
    }
}