<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // default admin account — change the password immediately after first login
        $admin = User::create([
            'name'        => 'System Administrator',
            'email'       => 'admin@company.com',
            'password'    => Hash::make('Admin@1234'),
            'employee_id' => 'ADM001',
            'department'  => 'IT Operations',
            'role'        => 'admin',
        ]);

        // sample team member
        User::create([
            'name'        => 'John Mensah',
            'email'       => 'john.mensah@company.com',
            'password'    => Hash::make('Member@1234'),
            'employee_id' => 'MEM001',
            'department'  => 'Applications Support',
            'phone'       => '+233 XX XXX XXXX',
            'role'        => 'member',
        ]);

        // sample activities that mirror what an applications support team typically tracks
        $activities = [
            ['title' => 'Daily SMS count vs SMS count from logs', 'category' => 'SMS Monitoring', 'description' => 'Compare the SMS count reported by the platform against the count extracted from system logs. Investigate and document any discrepancies.'],
            ['title' => 'System uptime check', 'category' => 'System Health', 'description' => 'Verify that all core services are running and uptime meets SLA requirements.'],
            ['title' => 'Failed transaction review', 'category' => 'Transaction Monitoring', 'description' => 'Review failed transactions from the previous period, identify root causes, and escalate where necessary.'],
            ['title' => 'Database backup verification', 'category' => 'System Health', 'description' => 'Confirm that the scheduled database backup completed successfully and backup file integrity is intact.'],
            ['title' => 'Queue depth monitoring', 'category' => 'SMS Monitoring', 'description' => 'Check message queue depth and clear any stuck messages. Log counts before and after.'],
            ['title' => 'Error log review', 'category' => 'System Health', 'description' => 'Review application error logs for recurring issues or new error patterns that may need investigation.'],
            ['title' => 'API response time check', 'category' => 'System Health', 'description' => 'Test key API endpoints and record response times. Flag any endpoints exceeding acceptable thresholds.'],
            ['title' => 'End-of-day handover report', 'category' => 'Operations', 'description' => 'Document all pending issues, escalations in progress, and key observations for the incoming shift.'],
        ];

        foreach ($activities as $activity) {
            Activity::create(array_merge($activity, ['created_by' => $admin->id]));
        }
    }
}
