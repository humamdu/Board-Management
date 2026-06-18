<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Board extends Model { protected $fillable=['workspace_id','owner_id','name','description','color','visibility','archived']; public function lists(){return $this->hasMany(KanbanList::class)->orderBy('sort_order');} public function cards(){return $this->hasMany(Card::class);} public function members(){return $this->belongsToMany(User::class,'board_members')->withPivot('role')->withTimestamps();} public function tags(){return $this->hasMany(Tag::class);} }
