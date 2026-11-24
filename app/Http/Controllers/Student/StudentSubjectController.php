<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\TeacherSectionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    /**
     * Display a listing of subjects for the current student.
     */
    public function index()
    {
        // Get the current student's latest enrollment (any status - pending, enrolled, etc.)
        // This allows viewing subjects before enrollment is approved
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->latest()
            ->first();

        // If no enrollment exists, show subjects based on admission
        if (!$enrollment) {
            $admission = \App\Models\Admission::where('user_id', Auth::id())
                ->where('is_draft', false)
                ->latest()
                ->first();
            
            if ($admission) {
                // Show subjects based on admission year level and strand
                return $this->showSubjectsForAdmission($admission);
            }
            
            return view('student.subjects.index', [
                'enrollment' => null,
                'subjects' => collect(),
                'message' => 'You need to submit an admission application to view available subjects. Please complete your admission first.'
            ]);
        }

        // If enrollment exists but no section assigned, still show subjects
        // (subjects can be viewed even without section assignment)
        if (!$enrollment->section) {
            return view('student.subjects.index', [
                'enrollment' => $enrollment,
                'subjects' => $this->getSubjectsForEnrollment($enrollment, false),
                'message' => 'Your section has not been assigned yet. Subjects shown below are based on your grade level and strand.'
            ]);
        }

        // Get subjects for the current enrollment
        $subjects = $this->getSubjectsForEnrollment($enrollment, true);

        return view('student.subjects.index', [
            'enrollment' => $enrollment,
            'subjects' => $subjects,
            'message' => null
        ]);
    }

    /**
     * Show subjects based on admission (before enrollment)
     */
    private function showSubjectsForAdmission($admission)
    {
        $subjects = Subject::where(function($q) use ($admission) {
                $q->whereNull('grade_level')->orWhere('grade_level', $admission->year_level);
            })
            ->when($admission->strand, function($q) use ($admission) {
                $q->where(function($query) use ($admission) {
                    $query->whereNull('strand')->orWhere('strand', $admission->strand);
                });
            })
            ->orderBy('name')
            ->get();

        return view('student.subjects.index', [
            'enrollment' => null,
            'admission' => $admission,
            'subjects' => $subjects,
            'message' => 'Subjects shown below are based on your admission application. These may change once you are enrolled and assigned to a section.'
        ]);
    }

    /**
     * Get subjects for an enrollment, filtering out previous year level subjects
     * unless the student is retaking them (failed grade < 75)
     */
    private function getSubjectsForEnrollment($enrollment, $includeTeacherAssignments = true)
    {
        $studentId = Auth::id();
        $currentGradeLevel = $enrollment->grade_level;
        
        // Get all previous enrollments to determine previous grade levels
        $previousEnrollments = Enrollment::where('user_id', $studentId)
            ->where('id', '!=', $enrollment->id)
            ->where('status', '!=', 'cancelled')
            ->get();
        
        // Get grade levels that are lower than current (previous years)
        $gradeLevelOrder = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $currentIndex = array_search($currentGradeLevel, $gradeLevelOrder);
        $previousGradeLevels = $currentIndex > 0 ? array_slice($gradeLevelOrder, 0, $currentIndex) : [];
        
        // Get subjects that student failed (grade < 75) - these can be retaken
        $failedSubjects = \App\Models\Grade::where('student_id', $studentId)
            ->where('grade_value', '<', 75)
            ->pluck('subject_id')
            ->unique();
        
        // Fetch subjects based on grade level, strand, and section
        $subjects = Subject::where(function($q) use ($enrollment) {
                $q->whereNull('grade_level')->orWhere('grade_level', $enrollment->grade_level);
            })
            ->when($enrollment->strand, function($q) use ($enrollment) {
                $q->where(function($query) use ($enrollment) {
                    $query->whereNull('strand')->orWhere('strand', $enrollment->strand);
                });
            })
            ->when($enrollment->section && $enrollment->grade_level, function($q) use ($enrollment) {
                $assignmentSubjectIds = TeacherSectionAssignment::where('grade_level', $enrollment->grade_level)
                    ->where('section', $enrollment->section)
                    ->when($enrollment->school_year, function($query) use ($enrollment) {
                        $query->where('school_year', $enrollment->school_year);
                    })
                    ->when($enrollment->strand, function($query) use ($enrollment) {
                        $query->where(function($q) use ($enrollment) {
                            $q->where('strand', $enrollment->strand)
                              ->orWhereNull('strand');
                        });
                    })
                    ->when($enrollment->tvl_branch, function($query) use ($enrollment) {
                        $query->where(function($q) use ($enrollment) {
                            $q->where('tvl_branch', $enrollment->tvl_branch)
                              ->orWhereNull('tvl_branch');
                        });
                    })
                    ->pluck('subject_id')
                    ->unique();

                if ($assignmentSubjectIds->isNotEmpty()) {
                    $q->orWhereIn('id', $assignmentSubjectIds);
                }
            })
            ->get()
            ->filter(function($subject) use ($currentGradeLevel, $previousGradeLevels, $failedSubjects) {
                // If subject is for current grade level, always show
                if ($subject->grade_level === null || $subject->grade_level === $currentGradeLevel) {
                    return true;
                }
                
                // If subject is from previous grade level, only show if student failed it (retaking)
                if (in_array($subject->grade_level, $previousGradeLevels)) {
                    return $failedSubjects->contains($subject->id);
                }
                
                // If subject is for future grade level, don't show
                return false;
            })
            ->values();
        
        // Load teacher assignments if needed
        if ($includeTeacherAssignments && $enrollment->section) {
            $subjects->load(['teacherAssignments' => function($q) use ($enrollment) {
                $q->where('grade_level', $enrollment->grade_level)
                  ->where('section', $enrollment->section)
                  ->when($enrollment->school_year, function($query) use ($enrollment) {
                      $query->where('school_year', $enrollment->school_year);
                  })
                  ->when($enrollment->strand, function($query) use ($enrollment) {
                      $query->where(function($q) use ($enrollment) {
                          $q->where('strand', $enrollment->strand)->orWhereNull('strand');
                      });
                  })
                  ->when($enrollment->tvl_branch, function($query) use ($enrollment) {
                      $query->where(function($q) use ($enrollment) {
                          $q->where('tvl_branch', $enrollment->tvl_branch)->orWhereNull('tvl_branch');
                      });
                  })
                  ->with('teacher');
            }]);
        }
        
        return $subjects->sortBy('name')->values();
    }
}

