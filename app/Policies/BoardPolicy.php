<?php
namespace App\Policies;
use App\Models\Board; use App\Models\User;
class BoardPolicy { public function view(User $u, Board $b): bool {return $b->visibility==='public'||$b->owner_id===$u->id||$b->members()->where('users.id',$u->id)->exists();} public function update(User $u, Board $b): bool {return $b->owner_id===$u->id||$b->members()->where('users.id',$u->id)->wherePivotIn('role',['manager','member'])->exists();} public function delete(User $u, Board $b): bool {return $b->owner_id===$u->id;} }
