<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$luvyBtfKZkES7eo2yG8xLemhbSO.oIhBhlU6slWC6F.qEMkXQ8Wou', // 12345678
        ]);

        $admin->assignRole('admin');

        $permission = Permission::create(['name' => 'Role access']);
        $permission1 = Permission::create(['name' => 'Role edit']);
        $permission2 = Permission::create(['name' => 'Role create']);
        $permission3 = Permission::create(['name' => 'Role delete']);

        $permission4 = Permission::create(['name' => 'User access']);
        $permission5 = Permission::create(['name' => 'User edit']);
        $permission6 = Permission::create(['name' => 'User create']);
        $permission7 = Permission::create(['name' => 'User delete']);
        $permission8 = Permission::create(['name' => 'User update role']);

        $permission9 = Permission::create(['name' => 'Permission access']);
        $permission10 = Permission::create(['name' => 'Permission edit']);
        $permission11 = Permission::create(['name' => 'Permission create']);
        $permission12 = Permission::create(['name' => 'Permission delete']);

        $permission13 = Permission::create(['name' => 'Attendance access']);
        $permission14 = Permission::create(['name' => 'Attendance create']);
        $permission15 = Permission::create(['name' => 'Attendance edit']);
        $permission16 = Permission::create(['name' => 'Attendance delete']);

        $permission17 = Permission::create(['name' => 'Classes access']);
        $permission18 = Permission::create(['name' => 'Classes create']);
        $permission19 = Permission::create(['name' => 'Classes edit']);
        $permission20 = Permission::create(['name' => 'Classes delete']);

        $permission21 = Permission::create(['name' => 'Dashbord access']);
        $permission22 = Permission::create(['name' => 'Dashbord create']);
        $permission23 = Permission::create(['name' => 'Dashbord edit']);
        $permission24 = Permission::create(['name' => 'Dashbord delete']);

        $permission25 = Permission::create(['name' => 'Exam access']);
        $permission26 = Permission::create(['name' => 'Exam create']);
        $permission27 = Permission::create(['name' => 'Exam edit']);
        $permission28 = Permission::create(['name' => 'Exam delete']);

        $permission29 = Permission::create(['name' => 'ExamMarks access']);
        $permission30 = Permission::create(['name' => 'ExamMarks create']);
        $permission31 = Permission::create(['name' => 'ExamMarks edit']);
        $permission32 = Permission::create(['name' => 'ExamMarks delete']);

        $permission33 = Permission::create(['name' => 'Exam result']);

        $permission34 = Permission::create(['name' => 'ExamSchedule access']);
        $permission35 = Permission::create(['name' => 'ExamSchedule create']);
        $permission36 = Permission::create(['name' => 'ExamSchedule edit']);
        $permission37 = Permission::create(['name' => 'ExamSchedule delete']);

        $permission38 = Permission::create(['name' => 'Expense access']);
        $permission39 = Permission::create(['name' => 'Expense create']);
        $permission40 = Permission::create(['name' => 'Expense edit']);
        $permission41 = Permission::create(['name' => 'Expense delete']);

        $permission42 = Permission::create(['name' => 'FeeCollection access']);
        $permission43 = Permission::create(['name' => 'FeeCollection create']);
        $permission44 = Permission::create(['name' => 'FeeCollection edit']);
        $permission45 = Permission::create(['name' => 'FeeCollection delete']);

        $permission46 = Permission::create(['name' => 'MailSetting access']);
        $permission47 = Permission::create(['name' => 'MailSetting create']);
        $permission48 = Permission::create(['name' => 'MailSetting edit']);
        $permission49 = Permission::create(['name' => 'MailSetting delete']);

        $permission50 = Permission::create(['name' => 'Salary access']);
        $permission51 = Permission::create(['name' => 'Salary create']);
        $permission52 = Permission::create(['name' => 'Salary edit']);
        $permission53 = Permission::create(['name' => 'Salary delete']);

        $permission54 = Permission::create(['name' => 'Salarysheet access']);
        $permission55 = Permission::create(['name' => 'Salarysheet create']);
        $permission56 = Permission::create(['name' => 'Salarysheet edit']);
        $permission57 = Permission::create(['name' => 'Salarysheet delete']);

        $permission58 = Permission::create(['name' => 'Student access']);
        $permission59 = Permission::create(['name' => 'Student create']);
        $permission60 = Permission::create(['name' => 'Student edit']);
        $permission61 = Permission::create(['name' => 'Student delete']);

        $permission62 = Permission::create(['name' => 'StudentPromotion access']);
        $permission63 = Permission::create(['name' => 'StudentPromotion create']);
        $permission64 = Permission::create(['name' => 'StudentPromotion edit']);
        $permission65 = Permission::create(['name' => 'StudentPromotion delete']);

        $permission66 = Permission::create(['name' => 'Subject access']);
        $permission67 = Permission::create(['name' => 'Subject create']);
        $permission68 = Permission::create(['name' => 'Subject edit']);
        $permission69 = Permission::create(['name' => 'Subject delete']);

        $permission70 = Permission::create(['name' => 'System config control']);
        $permission71 = Permission::create(['name' => 'System config create']);

        $admin->givePermissionTo(Permission::all());
    }
}
