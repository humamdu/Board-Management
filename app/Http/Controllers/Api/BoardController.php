<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller; use App\Models\Board; use App\Models\KanbanList; use App\Models\Workspace; use Illuminate\Http\Request;
class BoardController extends Controller {
 public function index(Request $r){return Board::with('members:id,name,email')->where(fn($q)=>$q->where('owner_id',$r->user()->id)->orWhereHas('members',fn($m)=>$m->where('users.id',$r->user()->id)))->latest()->paginate(20);} 
 public function store(Request $r){$data=$r->validate(['name'=>'required|max:255','description'=>'nullable','color'=>'nullable','visibility'=>'in:private,public']);$workspace=Workspace::firstOrCreate(['owner_id'=>$r->user()->id], ['name'=>$r->user()->name.' Workspace']);$board=Board::create($data+['owner_id'=>$r->user()->id,'workspace_id'=>$workspace->id]);$board->members()->attach($r->user()->id,['role'=>'manager']);foreach(['Backlog','To Do','In Progress','Review','Done'] as $i=>$name) KanbanList::create(['board_id'=>$board->id,'name'=>$name,'sort_order'=>$i]);return $board->load('lists');}
 public function show(Board $board){$this->authorize('view',$board);return $board->load(['lists.cards.tags','lists.cards.priority','lists.cards.assignee','members:id,name,email','tags']);}
 public function update(Request $r, Board $board){$this->authorize('update',$board);$board->update($r->validate(['name'=>'sometimes|max:255','description'=>'nullable','color'=>'nullable','visibility'=>'in:private,public','archived'=>'boolean']));return $board;}
 public function destroy(Board $board){$this->authorize('delete',$board);$board->delete();return response()->noContent();}
}
