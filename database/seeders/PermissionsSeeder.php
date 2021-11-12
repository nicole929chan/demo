<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin01 = User::factory()->create(['name' => 'admin01']);
        $manager01 = User::factory()->create(['name' => 'manager01']);
        $manager02 = User::factory()->create(['name' => 'manager02']);
        $vendor01 = User::factory()->create(['name' => 'vendor01']);
        $vendor02 = User::factory()->create(['name' => 'vendor02']);
        $manager03 = User::factory()->create(['name' => 'manager03']);
        $marketing01 = User::factory()->create(['name' => 'marketing01']);

        $admin = Role::factory()->create(['name' => 'admin', 'label'=> 'Admin']);
        $manager = Role::factory()->create(['name' => 'manager', 'label'=> 'Manager']);
        $vendor = Role::factory()->create(['name' => 'vendor', 'label'=> 'Vendor']);

        $p1 = Permission::factory()->create(['name' => 'view-product', 'label' => ' 瀏覽商品']);
        $p2 = Permission::factory()->create(['name' => 'create-product', 'label' => ' 新增商品']);
        $p3 = Permission::factory()->create(['name' => 'update-product', 'label' => ' 更新商品']);
        $p4 = Permission::factory()->create(['name' => 'delete-product', 'label' => ' 刪除商品']);

        $manager01->roles()->save($manager);
        $manager02->roles()->save($manager);
        $manager03->roles()->save($manager);

        $vendor01->roles()->save($vendor);
        $vendor02->roles()->save($vendor);

        $manager->permissions()->attach([$p1->id, $p2->id, $p3->id, $p4->id]);
        $vendor->permissions()->attach([$p1->id]);

        $marketing01->permissions()->save($p1);
    }
}
