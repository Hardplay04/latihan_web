<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InstructorApplicationsController extends Controller
{
    public function index()
    {
        $pending = User::query()
            ->where('instructor_status', 'pending')
            ->orderByDesc('instructor_applied_at')
            ->get(['id','name','email','instructor_application','instructor_applied_at']);

        return Inertia::render('Admin/InstructorApplications', [
            'pending' => $pending,
        ]);
    }

    public function approve(Request $request, User $user)
    {
        if ($user->instructor_status !== 'pending') {
            return back()->with('error', 'Status pengajuan bukan pending.');
        }

        $note = $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ])['note'] ?? null;

        $user->update([
            'role' => 'instructor',
            'instructor_status' => 'approved',
            'instructor_reviewed_at' => now(),
            'instructor_reviewed_by' => Auth::id(),
            'instructor_review_note' => $note,
        ]);

        return back()->with('success', 'Pengajuan disetujui. User menjadi Guru/Dosen.');
    }

    public function reject(Request $request, User $user)
    {
        if ($user->instructor_status !== 'pending') {
            return back()->with('error', 'Status pengajuan bukan pending.');
        }

        $note = $request->validate([
            'note' => ['required', 'string', 'max:500'],
        ])['note'];

        $user->update([
            'role' => 'student',                 // tetap student
            'instructor_status' => 'rejected',
            'instructor_reviewed_at' => now(),
            'instructor_reviewed_by' => Auth::id(),
            'instructor_review_note' => $note,
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}
