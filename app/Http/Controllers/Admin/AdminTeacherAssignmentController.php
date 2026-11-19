<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherSectionAssignment;
use App\Models\User;
use App\Models\Section;
use App\Models\Subject;
use App\Notifications\TeacherAssignmentNotification;
use Illuminate\Http\Request;

class AdminTeacherAssignmentController extends Controller
{
    /**
     * Display a listing of teacher assignments
     */
    public function index(Request $request)
    {
        $query = TeacherSectionAssignment::with(['teacher','subject']);

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        // Filter by school year
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        // Filter by strand (for SHS)
        if ($request->filled('strand')) {
            $query->where('strand', $request->strand);
        }

        // Search by teacher name
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('teacher', function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $assignments = $query->latest()->paginate(15);

        // Get all teachers for dropdown
        $teachers = User::where('role', 'teacher')->orderBy('lastname')->orderBy('firstname')->get();

        // Get all grade levels
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

        // Get current school year (default to current academic year)
        $currentSchoolYear = $request->school_year ?? date('Y') . '-' . (date('Y') + 1);

        $strands = ['GAS', 'TVL'];

        return view('admin.teacher-assignments.index', compact('assignments', 'teachers', 'gradeLevels', 'currentSchoolYear', 'strands'));
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('lastname')->orderBy('firstname')->get();
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands = ['GAS', 'TVL'];
        
        // Get current school year
        $currentSchoolYear = date('Y') . '-' . (date('Y') + 1);

        // Flat list of sections; views will group/filter as needed
        $sections = Section::all();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.teacher-assignments.create', compact('teachers', 'gradeLevels', 'strands', 'currentSchoolYear', 'sections', 'subjects'));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        // Parse sections from JSON if it's a string
        $sectionsInput = $request->input('sections');
        if (is_string($sectionsInput)) {
            $sectionsArray = json_decode($sectionsInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($sectionsArray)) {
                $request->merge(['sections' => $sectionsArray]);
            }
        }
        
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'grade_level' => 'required|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'sections' => 'required|array|min:1',
            'sections.*' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'strand' => 'nullable|in:GAS,TVL',
            'tvl_branch' => 'nullable|in:CSS,EIM',
            'school_year' => 'required|string|max:20',
        ]);

        // Enforce strand rules and validate sections existence per year level
        $isShs = in_array($validated['grade_level'], ['Grade 11', 'Grade 12']);
        if ($isShs && empty($validated['strand'])) {
            return back()->withErrors(['strand' => 'Strand is required for Grade 11 and 12.'])->withInput();
        }
        if ($isShs && ($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            return back()->withErrors(['tvl_branch' => 'Please select a TVL branch for TVL strand.'])->withInput();
        }
        if (!$isShs) {
            $validated['strand'] = null;
        }

        // Validate that each selected section exists for the chosen grade (and strand for SHS)
        $sectionQuery = Section::where('grade_level', $validated['grade_level']);
        if ($isShs) {
            $sectionQuery->where('strand', $validated['strand']);
        } else {
            $sectionQuery->whereNull('strand');
        }
        $validSectionNames = $sectionQuery->pluck('name')->unique()->toArray();
        if (empty($validSectionNames)) {
            $defaults = [
                'Grade 7' => ['HOPE', 'FAITH', 'LOVE', 'WISDOM'],
                'Grade 8' => ['CASILI', 'LANDONG', 'MORA', 'SAN JUAN'],
                'Grade 9' => ['ACACIA', 'LAUAN', 'NARRA'],
                'Grade 10' => ['BERYL', 'DIAMOND', 'JADE'],
            ];
            $shsDefaults = [
                'Grade 11' => [
                    'GAS' => ['ZAMORA'],
                    'TVL' => [
                        'CSS' => ['PADOLINA'],
                        'EIM' => ['ZARA', 'ALCARAZ'],
                    ],
                ],
                'Grade 12' => [
                    'GAS' => ['AMALTHEA'],
                    'TVL' => [
                        'CSS' => ['TITANIA'],
                        'EIM' => ['PROTEUS'],
                    ],
                ],
            ];
            if ($isShs) {
                if ($validated['strand'] === 'TVL') {
                    $branch = $request->input('tvl_branch');
                    $validSectionNames = $shsDefaults[$validated['grade_level']]['TVL'][$branch] ?? [];
                } else {
                    $validSectionNames = $shsDefaults[$validated['grade_level']][$validated['strand']] ?? [];
                }
            } else {
                $validSectionNames = $defaults[$validated['grade_level']] ?? [];
            }
        }
        $invalidSections = array_values(array_diff($validated['sections'], $validSectionNames));
        if (!empty($invalidSections)) {
            return back()->withErrors(['sections' => 'Invalid section(s) for the selected year level/strand: ' . implode(', ', $invalidSections)])->withInput();
        }

        // Validate teacher is actually a teacher
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user is not a teacher.'])->withInput();
        }

        $sections = $validated['sections'];
        $createdCount = 0;
        $skippedCount = 0;
        $errors = [];
        $createdSections = [];

        // Create assignment for each selected section
        foreach ($sections as $section) {
            // Check if assignment already exists (handle strand nullable properly)
            $exists = TeacherSectionAssignment::where('teacher_id', $validated['teacher_id'])
                ->where('grade_level', $validated['grade_level'])
                ->where('section', $section)
                ->where('school_year', $validated['school_year'])
                ->where(function($q) use ($validated, $isShs, $request) {
                    if ($isShs) {
                        $q->where('strand', $validated['strand']);
                        if (($validated['strand'] ?? null) === 'TVL') {
                            $q->where('tvl_branch', $request->input('tvl_branch'));
                        } else {
                            $q->whereNull('tvl_branch');
                        }
                    } else {
                        $q->whereNull('strand')
                          ->whereNull('tvl_branch');
                    }
                })
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            TeacherSectionAssignment::create([
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $validated['subject_id'],
                'grade_level' => $validated['grade_level'],
                'section' => $section,
                'strand' => $validated['strand'] ?? null,
                'tvl_branch' => (($validated['strand'] ?? null) === 'TVL') ? $request->input('tvl_branch') : null,
                'school_year' => $validated['school_year'],
            ]);
            $createdCount++;
            $createdSections[] = $section;
        }

        if ($createdCount > 0) {
            // Send notification to teacher about the new assignments
            $teacher->notify(new TeacherAssignmentNotification('assigned', [
                'grade_level' => $validated['grade_level'],
                'section' => count($createdSections) === 1 ? $createdSections[0] : implode(', ', $createdSections),
                'sections_count' => $createdCount,
                'strand' => $validated['strand'] ?? null,
                'school_year' => $validated['school_year'],
            ]));

            $message = "Teacher assigned to {$createdCount} section(s) successfully!";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} section(s) were already assigned.";
            }
            return redirect()->route('admin.teacher-assignments.index')
                ->with('success', $message);
        } else {
            return back()->withErrors(['assignment' => 'All selected sections are already assigned to this teacher for this school year.'])->withInput();
        }
    }

    /**
     * Show the form for editing an assignment
     */
    public function edit(TeacherSectionAssignment $teacherAssignment)
    {
        $teachers = User::where('role', 'teacher')->orderBy('lastname')->orderBy('firstname')->get();
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
        $strands = ['GAS', 'TVL'];
        
        $sections = Section::all();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.teacher-assignments.edit', compact('teacherAssignment', 'teachers', 'gradeLevels', 'strands', 'sections', 'subjects'));
    }

    /**
     * Update an assignment
     */
    public function update(Request $request, TeacherSectionAssignment $teacherAssignment)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'grade_level' => 'required|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'section' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'strand' => 'nullable|in:GAS,TVL',
            'tvl_branch' => 'nullable|in:CSS,EIM',
            'school_year' => 'required|string|max:20',
        ]);

        // Enforce strand rules and validate section existence per year level
        $isShs = in_array($validated['grade_level'], ['Grade 11', 'Grade 12']);
        if ($isShs && empty($validated['strand'])) {
            return back()->withErrors(['strand' => 'Strand is required for Grade 11 and 12.'])->withInput();
        }
        if ($isShs && ($validated['strand'] ?? null) === 'TVL' && empty($request->input('tvl_branch'))) {
            return back()->withErrors(['tvl_branch' => 'Please select a TVL branch for TVL strand.'])->withInput();
        }
        if (!$isShs) {
            $validated['strand'] = null;
        }

        $sectionQuery = Section::where('grade_level', $validated['grade_level']);
        if ($isShs) {
            $sectionQuery->where('strand', $validated['strand']);
        } else {
            $sectionQuery->whereNull('strand');
        }
        $isValidSection = $sectionQuery->where('name', $validated['section'])->exists();
        if (!$isValidSection) {
            // allow default mapping if DB has not been seeded
            $defaults = [
                'Grade 7' => ['HOPE', 'FAITH', 'LOVE', 'WISDOM'],
                'Grade 8' => ['CASILI', 'LANDONG', 'MORA', 'SAN JUAN'],
                'Grade 9' => ['ACACIA', 'LAUAN', 'NARRA'],
                'Grade 10' => ['BERYL', 'DIAMOND', 'JADE'],
            ];
            $shsDefaults = [
                'Grade 11' => [
                    'GAS' => ['ZAMORA'],
                    'TVL' => [
                        'CSS' => ['PADOLINA'],
                        'EIM' => ['ZARA', 'ALCARAZ'],
                    ],
                ],
                'Grade 12' => [
                    'GAS' => ['AMALTHEA'],
                    'TVL' => [
                        'CSS' => ['TITANIA'],
                        'EIM' => ['PROTEUS'],
                    ],
                ],
            ];
            $allowed = $defaults[$validated['grade_level']] ?? [];
            if ($isShs) {
                if ($validated['strand'] === 'TVL') {
                    $branch = $request->input('tvl_branch');
                    $allowed = $shsDefaults[$validated['grade_level']]['TVL'][$branch] ?? [];
                } else {
                    $allowed = $shsDefaults[$validated['grade_level']][$validated['strand']] ?? [];
                }
            }
            if (!in_array($validated['section'], $allowed, true)) {
                return back()->withErrors(['section' => 'Selected section does not belong to the chosen year level/strand.'])->withInput();
            }
        }

        // Validate teacher is actually a teacher
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user is not a teacher.'])->withInput();
        }

        // Check if assignment already exists (excluding current assignment)
        $exists = TeacherSectionAssignment::where('id', '!=', $teacherAssignment->id)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('grade_level', $validated['grade_level'])
            ->where('section', $validated['section'])
            ->where('school_year', $validated['school_year'])
            ->where(function($q) use ($validated, $request) {
                if (!empty($validated['strand'])) {
                    $q->where('strand', $validated['strand']);
                    if (($validated['strand'] ?? null) === 'TVL') {
                        $q->where('tvl_branch', $request->input('tvl_branch'));
                    } else {
                        $q->whereNull('tvl_branch');
                    }
                } else {
                    $q->whereNull('strand')
                      ->whereNull('tvl_branch');
                }
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['assignment' => 'This teacher is already assigned to this section for this school year.'])->withInput();
        }

        // Get old values for notification
        $oldDetails = [
            'grade_level' => $teacherAssignment->grade_level,
            'section' => $teacherAssignment->section,
            'strand' => $teacherAssignment->strand,
            'school_year' => $teacherAssignment->school_year,
        ];

        $teacherAssignment->update(array_merge($validated, [
            'tvl_branch' => (($validated['strand'] ?? null) === 'TVL') ? $request->input('tvl_branch') : null,
        ]));

        // Send notification to teacher about the updated assignment
        $teacher = User::find($validated['teacher_id']);
        if ($teacher) {
            $teacher->notify(new TeacherAssignmentNotification('updated', [
            'grade_level' => $validated['grade_level'],
            'section' => $validated['section'],
                'subject' => optional(Subject::find($validated['subject_id']))->name,
            'strand' => $validated['strand'] ?? null,
            'school_year' => $validated['school_year'],
            ]));
        }

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment updated successfully!');
    }

    /**
     * Remove an assignment
     */
    // Delete functionality removed to preserve all data
    public function destroy(TeacherSectionAssignment $teacherAssignment)
    {
        // Data preservation: Delete functionality has been disabled
        // Get teacher and assignment details (for reference, not deletion)
        $teacher = $teacherAssignment->teacher;
        $details = [
            'grade_level' => $teacherAssignment->grade_level,
            'section' => $teacherAssignment->section,
            'strand' => $teacherAssignment->strand,
            'school_year' => $teacherAssignment->school_year,
        ];

        // $teacherAssignment->delete(); // Disabled to preserve data

        // Data preservation: Delete functionality has been disabled
        return redirect()->route('admin.teacher-assignments.index')
            ->with('error', 'Delete functionality has been disabled to preserve all data.');
    }

    /**
     * Get sections for a grade level (AJAX)
     */
    public function getSections(Request $request)
    {
        $gradeLevel = $request->input('grade_level');
        $strand = $request->input('strand');
        $tvlBranch = $request->input('tvl_branch');

        // If no grade level provided, return empty array
        if (!$gradeLevel) {
            return response()->json([]);
        }

        $isShs = in_array($gradeLevel, ['Grade 11', 'Grade 12']);

        $query = Section::where('grade_level', $gradeLevel);

        if ($isShs) {
            // For SHS, only return sections that match the selected strand
            if (!$strand) {
                return response()->json([]);
            }
            $query->where('strand', $strand);
        } else {
            // For JHS, ensure strand is null
            $query->whereNull('strand');
        }

        $sections = $query->orderBy('name')->pluck('name')->unique()->values()->toArray();

        // Fallback defaults if DB has no entries yet
        if (empty($sections)) {
            $defaults = [
                'Grade 7' => ['HOPE', 'FAITH', 'LOVE', 'WISDOM'],
                'Grade 8' => ['CASILI', 'LANDONG', 'MORA', 'SAN JUAN'],
                'Grade 9' => ['ACACIA', 'LAUAN', 'NARRA'],
                'Grade 10' => ['BERYL', 'DIAMOND', 'JADE'],
            ];
            $shsDefaults = [
                'Grade 11' => [
                    'GAS' => ['ZAMORA'],
                    'TVL' => [
                        'CSS' => ['PADOLINA'],
                        'EIM' => ['ZARA', 'ALCARAZ'],
                    ],
                ],
                'Grade 12' => [
                    'GAS' => ['AMALTHEA'],
                    'TVL' => [
                        'CSS' => ['TITANIA'],
                        'EIM' => ['PROTEUS'],
                    ],
                ],
            ];

            if (!$isShs) {
                $sections = $defaults[$gradeLevel] ?? [];
            } else {
                if ($strand === 'TVL') {
                    $sections = $shsDefaults[$gradeLevel]['TVL'][$tvlBranch] ?? [];
                } else {
                    $sections = $shsDefaults[$gradeLevel][$strand] ?? [];
                }
            }
        }

        return response()->json($sections);
    }
}

