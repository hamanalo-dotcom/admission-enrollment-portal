<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. DEDICATED INDEX ON teacher_id
        // Create dedicated index on teacher_id FIRST to support the existing foreign key.
        // If an index named 'teacher_section_assignments_teacher_id_index' already exists,
        // verify that it actually indexes ['teacher_id'] as a non-unique index.
        $teacherIdIdx = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'teacher_section_assignments_teacher_id_index'");
        if (!empty($teacherIdIdx)) {
            $cols = array_column($teacherIdIdx, 'Column_name');
            $isNonUnique = ($teacherIdIdx[0]->Non_unique == 1);
            if ($cols !== ['teacher_id'] || !$isNonUnique) {
                throw new \RuntimeException(
                    "UNEXPECTED SCHEMA STATE: Index 'teacher_section_assignments_teacher_id_index' exists with mismatched definition: " .
                    "columns=[" . implode(', ', $cols) . "], non_unique=" . ($isNonUnique ? 'true' : 'false') . ". " .
                    "Expected non-unique index on ['teacher_id']. Halting to prevent unsafe state."
                );
            }
        } else {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->index('teacher_id', 'teacher_section_assignments_teacher_id_index');
            });
        }

        // 2. BASE COLUMN: subject_id
        // Verify definition if already present: must be BIGINT UNSIGNED and NULLABLE
        if (Schema::hasColumn('teacher_section_assignments', 'subject_id')) {
            $colInfo = DB::select("
                SELECT DATA_TYPE, COLUMN_TYPE, IS_NULLABLE 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'teacher_section_assignments' 
                  AND COLUMN_NAME = 'subject_id'
            ");
            if (!empty($colInfo)) {
                $dataType   = strtolower($colInfo[0]->DATA_TYPE);
                $colType    = strtolower($colInfo[0]->COLUMN_TYPE);
                $isNullable = strtoupper($colInfo[0]->IS_NULLABLE);
                $isUnsigned = (strpos($colType, 'unsigned') !== false);

                if ($dataType !== 'bigint' || !$isUnsigned || $isNullable !== 'YES') {
                    throw new \RuntimeException(
                        "UNEXPECTED SCHEMA STATE: Column 'subject_id' exists with mismatched definition: " .
                        "data_type='{$dataType}', column_type='{$colType}', is_nullable='{$isNullable}'. " .
                        "Expected BIGINT UNSIGNED and NULLABLE. Halting to prevent unsafe state."
                    );
                }
            }
        } else {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('teacher_id');
            });
        }

        // 3. FOREIGN KEY: subject_id -> subjects(id) ON DELETE RESTRICT
        // Check information_schema independently and verify all components:
        // local_col = subject_id, ref_table = subjects, ref_col = id, delete_rule = RESTRICT
        $fkInfo = DB::select("
            SELECT 
                kcu.COLUMN_NAME,
                kcu.REFERENCED_TABLE_NAME, 
                kcu.REFERENCED_COLUMN_NAME, 
                rc.DELETE_RULE
            FROM information_schema.KEY_COLUMN_USAGE kcu
            JOIN information_schema.REFERENTIAL_CONSTRAINTS rc
              ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
             AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
            WHERE kcu.TABLE_SCHEMA = DATABASE()
              AND kcu.TABLE_NAME = 'teacher_section_assignments'
              AND kcu.CONSTRAINT_NAME = 'teacher_section_assignments_subject_id_foreign'
        ");

        if (!empty($fkInfo)) {
            $localCol = strtolower($fkInfo[0]->COLUMN_NAME);
            $refTable = strtolower($fkInfo[0]->REFERENCED_TABLE_NAME);
            $refCol   = strtolower($fkInfo[0]->REFERENCED_COLUMN_NAME);
            $delRule  = strtoupper($fkInfo[0]->DELETE_RULE);

            if ($localCol !== 'subject_id' || $refTable !== 'subjects' || $refCol !== 'id' || $delRule !== 'RESTRICT') {
                throw new \RuntimeException(
                    "UNEXPECTED SCHEMA STATE: Foreign key 'teacher_section_assignments_subject_id_foreign' exists with mismatched definition: " .
                    "local_col='{$localCol}' (expected 'subject_id'), ref_table='{$refTable}' (expected 'subjects'), " .
                    "ref_col='{$refCol}' (expected 'id'), delete_rule='{$delRule}' (expected 'RESTRICT'). Halting."
                );
            }
        } else {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->foreign('subject_id')
                      ->references('id')
                      ->on('subjects')
                      ->onDelete('restrict');
            });
        }

        // 4. BASE COLUMN: tvl_branch
        // Verify definition if already present: ENUM('CSS','EIM') and NULLABLE
        if (Schema::hasColumn('teacher_section_assignments', 'tvl_branch')) {
            $branchInfo = DB::select("
                SELECT DATA_TYPE, COLUMN_TYPE, IS_NULLABLE 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'teacher_section_assignments' 
                  AND COLUMN_NAME = 'tvl_branch'
            ");
            if (!empty($branchInfo)) {
                $dataType   = strtolower($branchInfo[0]->DATA_TYPE);
                $normType   = strtolower(str_replace([' ', '"'], ['', "'"], $branchInfo[0]->COLUMN_TYPE));
                $isNullable = strtoupper($branchInfo[0]->IS_NULLABLE);

                if ($dataType !== 'enum' || $normType !== "enum('css','eim')" || $isNullable !== 'YES') {
                    throw new \RuntimeException(
                        "UNEXPECTED SCHEMA STATE: Column 'tvl_branch' exists with mismatched definition: " .
                        "type='{$branchInfo[0]->COLUMN_TYPE}', is_nullable='{$isNullable}'. " .
                        "Expected enum('CSS','EIM') and NULLABLE. Halting to prevent unsafe state."
                    );
                }
            }
        } else {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->enum('tvl_branch', ['CSS', 'EIM'])->nullable()->after('strand');
            });
        }

        // Helper closure to verify generated column metadata if column already exists
        $verifyGeneratedColumn = function(string $colName, string $expectedType, string $expectedExpr) {
            if (Schema::hasColumn('teacher_section_assignments', $colName)) {
                $info = DB::select("
                    SELECT COLUMN_TYPE, EXTRA, GENERATION_EXPRESSION 
                    FROM information_schema.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                      AND TABLE_NAME = 'teacher_section_assignments' 
                      AND COLUMN_NAME = '{$colName}'
                ");
                if (!empty($info)) {
                    $extra = strtoupper($info[0]->EXTRA);
                    $normExpr = str_replace(['`', ' ', '"', "'"], '', strtolower($info[0]->GENERATION_EXPRESSION ?? ''));
                    $normExpectedExpr = str_replace(['`', ' ', '"', "'"], '', strtolower($expectedExpr));
                    $colType = strtolower($info[0]->COLUMN_TYPE);

                    $isGenerated = (strpos($extra, 'GENERATED') !== false || strpos($extra, 'VIRTUAL') !== false);
                    $expectedLower = strtolower($expectedType);
                    if ($expectedLower === 'bigint unsigned') {
                        $typeMatches = (strpos($colType, 'bigint') !== false && strpos($colType, 'unsigned') !== false);
                    } else {
                        $typeMatches = (strpos($colType, $expectedLower) !== false);
                    }
                    $exprMatches = ($normExpr === $normExpectedExpr);

                    if (!$isGenerated || !$typeMatches || !$exprMatches) {
                        throw new \RuntimeException(
                            "UNEXPECTED SCHEMA STATE: Generated column '{$colName}' exists with mismatched definition: " .
                            "type='{$info[0]->COLUMN_TYPE}' (expected '{$expectedType}'), extra='{$extra}', " .
                            "expr='{$info[0]->GENERATION_EXPRESSION}' (expected '{$expectedExpr}'). Halting."
                        );
                    }
                }
                return true; // Already exists and verified
            }
            return false; // Does not exist, needs creation
        };

        // 5. GENERATED VIRTUAL COLUMNS
        // subject_id_clean: unsignedBigInteger (COALESCE(subject_id, 0))
        if (!$verifyGeneratedColumn('subject_id_clean', 'bigint unsigned', 'coalesce(subject_id, 0)')) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('subject_id_clean')->virtualAs("COALESCE(subject_id, 0)");
            });
        }

        // strand_clean: VARCHAR(50) (COALESCE(strand, 'JHS'))
        if (!$verifyGeneratedColumn('strand_clean', 'varchar(50)', "coalesce(strand, 'JHS')")) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->string('strand_clean', 50)->virtualAs("COALESCE(strand, 'JHS')");
            });
        }

        // tvl_branch_clean: VARCHAR(20) (COALESCE(tvl_branch, 'NONE'))
        if (!$verifyGeneratedColumn('tvl_branch_clean', 'varchar(20)', "coalesce(tvl_branch, 'NONE')")) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->string('tvl_branch_clean', 20)->virtualAs("COALESCE(tvl_branch, 'NONE')");
            });
        }

        // 6. COMPOSITE UNIQUE CONSTRAINT
        // Confirm new unique index is strictly UNIQUE and contains all 7 columns in exact order
        $expectedCols = ['teacher_id', 'subject_id_clean', 'grade_level', 'section', 'school_year', 'strand_clean', 'tvl_branch_clean'];
        $newIndexes = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'unique_teacher_subject_assignment'");
        if (!empty($newIndexes)) {
            $cols = array_column($newIndexes, 'Column_name');
            $isUnique = ($newIndexes[0]->Non_unique == 0);
            if ($cols !== $expectedCols || !$isUnique) {
                throw new \RuntimeException(
                    "UNEXPECTED SCHEMA STATE: Index 'unique_teacher_subject_assignment' exists with mismatched definition: " .
                    "columns=[" . implode(', ', $cols) . "], unique=" . ($isUnique ? 'true' : 'false') . ". " .
                    "Expected UNIQUE on [" . implode(', ', $expectedCols) . "]. Halting."
                );
            }
        } else {
            Schema::table('teacher_section_assignments', function (Blueprint $table) use ($expectedCols) {
                $table->unique($expectedCols, 'unique_teacher_subject_assignment');
            });
        }

        // 7. DROP OLD CONSTRAINT ONLY AFTER NEW INDEX AND DEDICATED INDEX ARE ACTIVE
        // Ensure dedicated teacher_id index is confirmed before dropping unique_teacher_assignment
        $confirmedTeacherIdx = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'teacher_section_assignments_teacher_id_index'");
        $confirmedNewIdx = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'unique_teacher_subject_assignment'");
        if (empty($confirmedTeacherIdx) || empty($confirmedNewIdx)) {
            throw new \RuntimeException(
                "ABORTING DROP: Refusing to drop 'unique_teacher_assignment' because supporting indexes are not confirmed."
            );
        }

        $oldIndexes = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'unique_teacher_assignment'");
        if (!empty($oldIndexes)) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->dropUnique('unique_teacher_assignment');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. DATA PRESERVATION CHECK (safely handles partially applied states where columns may not exist)
        $hasSubjectCol = Schema::hasColumn('teacher_section_assignments', 'subject_id');
        $hasBranchCol = Schema::hasColumn('teacher_section_assignments', 'tvl_branch');

        $conditions = [];
        if ($hasSubjectCol) $conditions[] = 'subject_id IS NOT NULL';
        if ($hasBranchCol) $conditions[] = 'tvl_branch IS NOT NULL';

        if (!empty($conditions)) {
            $populatedCount = DB::table('teacher_section_assignments')
                ->whereRaw('(' . implode(' OR ', $conditions) . ')')
                ->count();

            if ($populatedCount > 0) {
                throw new \RuntimeException(
                    "ROLLBACK ABORTED TO PREVENT ACADEMIC DATA LOSS: " .
                    "The teacher_section_assignments table contains {$populatedCount} record(s) " .
                    "with populated subject_id or tvl_branch data. " .
                    "Dropping these columns will permanently destroy teacher subject links and TVL branch history. " .
                    "A manual database backup and data archiving procedure must be performed before rollback."
                );
            }
        }

        // 2. CONSTRAINT RESTORATION CHECK: Abort if restoring the old 4-column constraint would fail
        $hasOriginalDuplicates = DB::table('teacher_section_assignments')
            ->select('teacher_id', 'grade_level', 'section', 'school_year')
            ->groupBy('teacher_id', 'grade_level', 'section', 'school_year')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasOriginalDuplicates) {
            throw new \RuntimeException(
                "ROLLBACK ABORTED: Restoring the original unique constraint (unique_teacher_assignment) " .
                "would fail because multiple assignments exist for the same teacher, grade level, section, and school year. " .
                "Rollback has been aborted without modifying or deleting any records."
            );
        }

        // 3. SAFE REVERSAL SEQUENCE
        // Step A: Restore original 4-column unique constraint FIRST so an index on teacher_id exists
        $oldIndexes = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'unique_teacher_assignment'");
        if (empty($oldIndexes)) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->unique(
                    ['teacher_id', 'grade_level', 'section', 'school_year'],
                    'unique_teacher_assignment'
                );
            });
        }

        // Step B: Drop new composite unique constraint if present
        $newIndexes = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'unique_teacher_subject_assignment'");
        if (!empty($newIndexes)) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->dropUnique('unique_teacher_subject_assignment');
            });
        }

        // Step C: Drop generated virtual columns if present
        $columnsToDrop = [];
        if (Schema::hasColumn('teacher_section_assignments', 'subject_id_clean')) $columnsToDrop[] = 'subject_id_clean';
        if (Schema::hasColumn('teacher_section_assignments', 'strand_clean')) $columnsToDrop[] = 'strand_clean';
        if (Schema::hasColumn('teacher_section_assignments', 'tvl_branch_clean')) $columnsToDrop[] = 'tvl_branch_clean';
        if (!empty($columnsToDrop)) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }

        // Step D: Drop foreign key on subject_id independently if it exists
        $hasSubjectFk = !empty(DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'teacher_section_assignments' 
              AND CONSTRAINT_NAME = 'teacher_section_assignments_subject_id_foreign'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        "));

        if ($hasSubjectFk) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->dropForeign(['subject_id']);
            });
        }

        // Step E: Drop base column subject_id if present
        if (Schema::hasColumn('teacher_section_assignments', 'subject_id')) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->dropColumn('subject_id');
            });
        }

        // Step F: Drop tvl_branch base column if present
        if (Schema::hasColumn('teacher_section_assignments', 'tvl_branch')) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->dropColumn('tvl_branch');
            });
        }

        // Step G: Drop dedicated teacher_id index (supported by unique_teacher_assignment)
        $teacherIdIdx = DB::select("SHOW INDEX FROM teacher_section_assignments WHERE Key_name = 'teacher_section_assignments_teacher_id_index'");
        if (!empty($teacherIdIdx)) {
            Schema::table('teacher_section_assignments', function (Blueprint $table) {
                $table->dropIndex('teacher_section_assignments_teacher_id_index');
            });
        }
    }
};
