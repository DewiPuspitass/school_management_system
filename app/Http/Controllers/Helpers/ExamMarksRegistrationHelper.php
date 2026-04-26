<?php

namespace App\Http\Controllers\Helpers;

use App\Models\ExamSchedule;
use Illuminate\Http\Request;

trait ExamMarksRegistrationHelper
{
    private function prepareExamMarksData(Request $request, array $subjects_id): array
    {
        $data = [];

        foreach ($request->studentId as $student_id) {
            foreach ($subjects_id as $subject_id) {
                $markEntry = $this->buildSingleMarkEntry($request, $student_id, $subject_id);

                if ($markEntry) {
                    $data[] = $markEntry;
                }
            }
        }

        return $data;
    }

    private function buildSingleMarkEntry(Request $request, $student_id, $subject_id): ?array
    {
        $exam_schedule_check = ExamSchedule::where([
            'class_id' => $request->class_id,
            'exam_id' => $request->exam_id,
            'subject_id' => $subject_id,
        ])->first();

        $AttendancehMarks = $this->attendanceMarks($subject_id, $student_id);

        $fullMarks = $exam_schedule_check->full_marks;
        $passMarks = $exam_schedule_check->pass_marks;

        $total_marks = $request->class_work[$student_id][$subject_id] +
            $request->home_work[$student_id][$subject_id] +
            $request->exam[$student_id][$subject_id] +
            $AttendancehMarks;

        if (! empty($exam_schedule_check)) {
            if (! ($total_marks > $fullMarks)) {
                return [
                    'student_id' => $student_id,
                    'subject_id' => $subject_id,
                    'class_id' => $request->class_id,
                    'exam_id' => $request->exam_id,
                    'class_work' => $request->class_work[$student_id][$subject_id],
                    'home_work' => $request->home_work[$student_id][$subject_id],
                    'mark' => $request->exam[$student_id][$subject_id],
                    'attendance_mark' => $AttendancehMarks,
                    'total_mark' => $total_marks,
                    'full_marks' => $fullMarks,
                    'pass_marks' => $passMarks,
                    'created_at' => now(),
                ];
            }
        }

        return null;
    }
}
