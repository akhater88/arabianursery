<?php

namespace App\Console\Commands;

use App\Models\NurseryUser;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NurseryRoles extends Command
{
    protected $signature = 'nursery:roles';
    protected $description = 'seed nursery roles';

    public function handle()
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'nursery-admin',
            'guard_name'=>'nursery_web'
        ]
        );
        $operatorRole = Role::firstOrCreate([
                'name' => 'nursery-operator',
                'guard_name'=>'nursery_web'
            ]
        );
        $adminPermission = Permission::firstOrCreate([
                'name' => 'admin',
                'guard_name'=>'nursery_web'
            ]
        );
        $adminPermission->assignRole($adminRole);
        $operatorPermission = Permission::firstOrCreate([
                'name' => 'operator',
                'guard_name'=>'nursery_web'
            ]
        );
        $operatorPermission->assignRole($operatorRole);

        $nurseryUsers =  NurseryUser::withoutRole('nursery-operator')->get();
        foreach ($nurseryUsers as $nurseryUser){
            $nurseryUser->syncRoles('nursery-admin');
        }
    }
}

