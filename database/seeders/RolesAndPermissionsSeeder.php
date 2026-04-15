<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $patientPermissions = [
            'book appointment',
            'cancel appointment',
            'view own appointments',
            'view own medical records',
            'make payment',
            'join teleconsultation',
        ];

        $doctorPermissions = [
            'manage own schedule',
            'view assigned appointments',
            'update appointment status',
            'add medical notes',
            'start teleconsultation',
            'view own earnings',
        ];

        $adminPermissions = [
            'manage doctors',
            'manage patients',
            'manage all appointments',
            'view reports',
            'manage payments',
            'manage system settings',
        ];

        foreach (array_merge($patientPermissions, $doctorPermissions, $adminPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $patient = Role::firstOrCreate(['name' => 'patient']);

        $patient->syncPermissions($patientPermissions);
        $doctor->syncPermissions($doctorPermissions);
        $admin->syncPermissions($adminPermissions);
    }
}
