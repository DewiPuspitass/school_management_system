<?php

namespace App\Http\Controllers\Helpers;

use App\Models\ExamSchedule;
use Illuminate\Http\Request;

trait ExamMarksRegistrationHelper
{
    private function getExamSchedulesLookup(Request $request)
    {
        return ExamSchedule::where('class_id', $request->class_id)
            ->where('exam_id', $request->exam_id)
            ->get()
            ->keyBy('subject_id');
    }

    private function prepareAllStudentMarks(Request $request, array $subjects_id, $schedules, \Closure $attendanceCallback): array
    {
        $allData = [];

        foreach ($request->studentId as $student_id) {
            foreach ($subjects_id as $subject_id) {
                $schedule = $schedules->get($subject_id);

                if (! $schedule) {
                    continue;
                }

                $attendanceMarks = $attendanceCallback($subject_id, $student_id);

                $entry = $this->calculateSingleMark($request, $student_id, $subject_id, $schedule, $attendanceMarks);

                if ($entry) {
                    $allData[] = $entry;
                }
            }
        }

        return $allData;
    }

    private function calculateSingleMark(Request $request, $student_id, $subject_id, $schedule, $attendanceMarks): ?array
    {
        $totalMarks = $request->class_work[$student_id][$subject_id] +
            $request->home_work[$student_id][$subject_id] +
            $request->exam[$student_id][$subject_id] +
            $attendanceMarks;

        if ($totalMarks > $schedule->full_marks) {
            return null;
        }

        return [
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'class_id' => $request->class_id,
            'exam_id' => $request->exam_id,
            'class_work' => $request->class_work[$student_id][$subject_id],
            'home_work' => $request->home_work[$student_id][$subject_id],
            'mark' => $request->exam[$student_id][$subject_id],
            'attendance_mark' => $attendanceMarks,
            'total_mark' => $totalMarks,
            'full_marks' => $schedule->full_marks,
            'pass_marks' => $schedule->pass_marks,
            'created_at' => now(),
        ];
    }
}
