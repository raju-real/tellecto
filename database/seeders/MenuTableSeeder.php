<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use App\Models\AdminMenuActivity;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => "Admin",
                'description' => "Admin related menu and activity",
                'activities' => [
                    array('activity_name' => 'View Admin List', 'route_name' => 'admins.index','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Create Admin', 'route_name' => 'admins.create','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Store Admin', 'route_name' => 'admins.store','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Admin Details', 'route_name' => 'admins.Show','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Edit Admin', 'route_name' => 'admins.edit','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Update Admin', 'route_name' => 'admins.update','is_dependant' => 'Yes','auto_select' => 'No'),
                    //array('activity_name' => 'Delete Admin', 'route_name' => 'admins.destroy','is_dependant' => 'No','auto_select' => 'No'),
                ]
            ],
            [
                'name' => "Category",
                'description' => "Category related menu and activity",
                'activities' => [
                    array('activity_name' => 'View Category List', 'route_name' => 'categories.index','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Create Category', 'route_name' => 'categories.create','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Store Category', 'route_name' => 'categories.store','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Category Details', 'route_name' => 'categories.Show','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Edit Category', 'route_name' => 'categories.edit','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Update Category', 'route_name' => 'categories.update','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Delete Category', 'route_name' => 'categories.destroy','is_dependant' => 'No','auto_select' => 'No'),
                ]
            ],
            [
                'name' => "Business",
                'description' => "Business related menu and activity",
                'activities' => [
                    array('activity_name' => 'View Business List', 'route_name' => 'businesses.index','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Create Business', 'route_name' => 'businesses.create','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Store Business', 'route_name' => 'businesses.store','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Business Details', 'route_name' => 'businesses.Show','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Edit Business', 'route_name' => 'businesses.edit','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Update Business', 'route_name' => 'businesses.update','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Delete Business', 'route_name' => 'businesses.destroy','is_dependant' => 'No','auto_select' => 'No'),
                ]
            ],
            [
                'name' => "Role",
                'description' => "Role related menu and activity",
                'activities' => [
                    array('activity_name' => 'View Role List', 'route_name' => 'admin-roles.index','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Create Role', 'route_name' => 'admin-roles.create','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Store Role', 'route_name' => 'admin-roles.store','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Role Details', 'route_name' => 'admin-roles.Show','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Edit Role', 'route_name' => 'admin-roles.edit','is_dependant' => 'No','auto_select' => 'No'),
                    array('activity_name' => 'Update Role', 'route_name' => 'admin-roles.update','is_dependant' => 'Yes','auto_select' => 'No'),
                    array('activity_name' => 'Delete Role', 'route_name' => 'admin-roles.destroy','is_dependant' => 'No','auto_select' => 'No'),
                ]
            ],
        ];

        foreach ($menus as $menu) {
            $menu_data = [
                'name' => $menu['name'],
                'description' => $menu['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ];
            AdminMenu::updateOrInsert(['name' => $menu['name']],$menu_data);
            if(count($menu['activities'])) {
                foreach ($menu['activities'] as $activity) {
                    $menu_id = AdminMenu::where('name',$menu)->first()->id;
                    $identify = [
                        'menu_id'=>$menu_id,
                        'route_name' => $activity['route_name']
                    ];
                    $activity_data = [
                        'menu_id'=>$menu_id,
                        'activity_name' => $activity['activity_name'],
                        'route_name' => $activity['route_name'],
                        'is_dependant' => $activity['is_dependant'],
                        'auto_select' => $activity['auto_select'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    AdminMenuActivity::updateOrInsert($identify,$activity_data);
                }
            }
        }
    }
}
