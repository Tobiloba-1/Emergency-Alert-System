<?php

class ReportsController
{
    public static function index(): array
    {
        Session::requireLogin(['admin']);

        return [
            'page_title'         => 'Reports & Analytics',
            'total_alerts'       => Alert::countAll(),
            'severity_breakdown' => Alert::severityBreakdown(),
            'target_breakdown'   => Alert::targetRoleBreakdown(),
            'delivery_stats'     => Alert::deliveryStats(),
            'daily_counts'       => Alert::dailyCounts(30),
            'sos_total'          => SosAlert::countAll(),
            'sos_active'         => SosAlert::countActive(),
            'sos_response'       => SosAlert::responseStats(),
        ];
    }
}