<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $grades = ['X', 'XI', 'XII'];
        $classNames = ['A', 'B', 'C', 'D', 'E'];
        
        foreach ($grades as $grade) {
            foreach ($classNames as $className) {
                SchoolClass::create([
                    'grade' => $grade,
                    'class_name' => $className,
                    'is_active' => true,
                ]);
            }
        }
        
        // Update existing student records
        // Code to map old class format to new class format would go here
        // This could be complex depending on your old format
        
        // Remove temporary column if it exists
        if (Schema::hasColumn('students', 'class_temp')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('class_temp');
            });
        }
    }
}