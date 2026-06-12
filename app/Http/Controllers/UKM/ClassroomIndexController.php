<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Performance;
use App\Models\Program;

class ClassroomIndexController extends Controller
{
    /**
     * Show all classrooms across all performances (sidebar entry point).
     */
    public function index()
    {
        $classrooms = Classroom::with([
            'performance.program',
            'members',
            'attendances',
        ])->get();

        $performances = Performance::with(['program', 'classroom'])
            ->whereHas('program', fn($q) => $q->where('activity_type', 'Performance'))
            ->orderBy('performance_date', 'desc')
            ->get();

        return view('pengurus.classrooms.index', compact('classrooms', 'performances'));
    }
}
