<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AddSuperAdmin extends Command
{
    protected $signature = 'admin:add-super {name} {email} {password}';
    protected $description = 'Adds a new super admin user';

    public function handle()
    {
        $admin = AdminUser::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($this->argument('password')),
        ]);

        $this->info("Super admin {$admin->name} created successfully!");
    }
}

