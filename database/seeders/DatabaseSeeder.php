<?php
namespace Database\Seeders;
use App\Models\Priority; use App\Models\Role; use App\Models\User; use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder { public function run(): void { foreach(['admin','board_manager','member','viewer'] as $role) Role::firstOrCreate(['name'=>$role]); foreach([['Low','#22c55e',1],['Medium','#f59e0b',2],['High','#ef4444',3],['Critical','#7f1d1d',4]] as [$name,$color,$weight]) Priority::firstOrCreate(['name'=>$name],compact('color','weight')); User::firstOrCreate(['email'=>'admin@example.com'],['name'=>'System Administrator','password'=>'password']); } }
