<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'users_total' => User::count(),
            'students_total' => User::where('role', 'student')->count(),
            'instructors_total' => User::where('role', 'instructor')->count(),
            'admins_total' => User::where('role', 'admin')->count(),
            'pending_instructor_applications' => User::where('instructor_status', 'pending')->count(),
        ];

        $latestPending = User::query()
            ->where('instructor_status', 'pending')
            ->orderByDesc('instructor_applied_at')
            ->limit(7)
            ->get([
                'id',
                'name',
                'email',
                'instructor_application',
                'instructor_applied_at'
            ]);

        return Inertia::render('Admin/Dashboard', [
            'counts' => $counts,
            'latestPending' => $latestPending,
        ]);
    }
}
