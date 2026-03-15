<?php

namespace Database\Seeders;

use App\Models\SidebarModule;
use App\Models\SidebarOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SidebarSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data (delete in correct order due to foreign keys)
        SidebarOption::query()->delete();
        SidebarModule::query()->delete();

        // 1. Dashboard Module
        $dashboardModule = SidebarModule::create([
            'title' => 'Dashboard',
            'icon' => 'fa-tachometer-alt',
            'permission' => 'dashboard',
            'order' => 1,
        ]);

        // 2. Members Module
        $membersModule = SidebarModule::create([
            'title' => 'Members',
            'icon' => 'fa-users',
            'permission' => 'members',
            'order' => 2,
        ]);

        SidebarOption::create([
            'sidebar_module_id' => $membersModule->id,
            'title' => 'All Members',
            'route' => 'members.manage',
            'permission' => 'members.manage',
            'order' => 1,
        ]);

        SidebarOption::create([
            'sidebar_module_id' => $membersModule->id,
            'title' => 'Manage Invoices',
            'route' => 'invoices.manage',
            'permission' => 'invoices.manage',
            'order' => 2,
        ]);

        // 3. Settings Module (Parent module for various settings)
        $settingsModule = SidebarModule::create([
            'title' => 'Settings',
            'icon' => 'fa-cogs',
            'permission' => 'settings',
            'order' => 3,
        ]);

        // Settings sub-options
        SidebarOption::create([
            'sidebar_module_id' => $settingsModule->id,
            'title' => 'Permissions',
            'route' => 'permissions.manage',
            'permission' => 'permissions.manage',
            'order' => 1,
        ]);

        SidebarOption::create([
            'sidebar_module_id' => $settingsModule->id,
            'title' => 'Roles',
            'route' => 'roles.manage',
            'permission' => 'roles.manage',
            'order' => 2,
        ]);

        SidebarOption::create([
            'sidebar_module_id' => $settingsModule->id,
            'title' => 'Users',
            'route' => 'users.manage',
            'permission' => 'users.manage',
            'order' => 3,
        ]);

        SidebarOption::create([
            'sidebar_module_id' => $settingsModule->id,
            'title' => 'Currencies',
            'route' => 'currencies.manage',
            'permission' => 'currencies.manage',
            'order' => 4,
        ]);

        SidebarOption::create([
            'sidebar_module_id' => $settingsModule->id,
            'title' => 'Sidebar Management',
            'route' => 'sidebar.manage',
            'permission' => 'sidebar.manage',
            'order' => 5,
        ]);
    }
}
