<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make program_id nullable in performances table and add job-specific columns
        Schema::table('performances', function (Blueprint $table) {
            $table->foreignId('program_id')->nullable()->change();
            
            // Add job-specific columns
            $table->string('institution')->nullable()->after('status');
            $table->decimal('fee', 15, 2)->default(0)->after('institution');
            $table->string('pic')->nullable()->after('fee');
            $table->text('rundown')->nullable()->after('pic');
        });

        // 2. Migrate existing Job data to Performances
        if (Schema::hasTable('jobs')) {
            $jobs = DB::table('jobs')->get();
            foreach ($jobs as $job) {
                // Insert into performances
                $performanceId = DB::table('performances')->insertGetId([
                    'program_id' => null,
                    'title' => $job->event_name,
                    'venue' => $job->location,
                    'performance_date' => $job->date,
                    'performance_time' => $job->time,
                    'description' => $job->notes,
                    'status' => 'Selesai',
                    'institution' => $job->institution,
                    'fee' => $job->fee,
                    'pic' => $job->pic,
                    'rundown' => $job->rundown,
                    'created_at' => $job->created_at ?? now(),
                    'updated_at' => $job->updated_at ?? now(),
                ]);

                // Map classroom to the new performance
                DB::table('classrooms')
                    ->where('job_id', $job->id)
                    ->update([
                        'performance_id' => $performanceId,
                    ]);

                // Migrate job members to classroom members
                if (Schema::hasTable('job_members') && Schema::hasTable('classroom_members')) {
                    $classroomId = DB::table('classrooms')
                        ->where('performance_id', $performanceId)
                        ->value('id');
                    
                    if ($classroomId) {
                        $jobMembers = DB::table('job_members')->where('job_id', $job->id)->get();
                        foreach ($jobMembers as $jm) {
                            DB::table('classroom_members')->insertOrIgnore([
                                'classroom_id' => $classroomId,
                                'member_id' => $jm->member_id,
                                'role' => 'Peserta',
                            ]);
                        }
                    }
                }
            }
        }

        // 3. Drop job_id foreign key and column from classrooms
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->dropColumn('job_id');
        });

        // 4. Drop job_members and jobs tables
        Schema::dropIfExists('job_members');
        Schema::dropIfExists('jobs');
    }

    public function down(): void
    {
        // Consolidating migration - rollback is not needed
    }
};
