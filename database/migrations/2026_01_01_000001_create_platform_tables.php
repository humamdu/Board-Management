<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('roles', fn(Blueprint $t)=>[$t->id(),$t->string('name')->unique(),$t->timestamps()]);
  Schema::create('workspaces', fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->foreignId('owner_id')->constrained('users')->cascadeOnDelete(),$t->timestamps()]);
  Schema::create('boards', function(Blueprint $t){$t->id();$t->foreignId('workspace_id')->constrained()->cascadeOnDelete();$t->foreignId('owner_id')->constrained('users')->cascadeOnDelete();$t->string('name');$t->text('description')->nullable();$t->string('color',20)->default('#2563eb');$t->enum('visibility',['private','public'])->default('private');$t->boolean('archived')->default(false);$t->timestamps();$t->index(['workspace_id','archived']);});
  Schema::create('board_members', function(Blueprint $t){$t->id();$t->foreignId('board_id')->constrained()->cascadeOnDelete();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->enum('role',['manager','member','viewer'])->default('member');$t->timestamps();$t->unique(['board_id','user_id']);});
  Schema::create('kanban_lists', function(Blueprint $t){$t->id();$t->foreignId('board_id')->constrained()->cascadeOnDelete();$t->string('name');$t->unsignedInteger('sort_order')->default(0);$t->timestamps();$t->index(['board_id','sort_order']);});
  Schema::create('priorities', fn(Blueprint $t)=>[$t->id(),$t->string('name')->unique(),$t->string('color',20),$t->unsignedTinyInteger('weight'),$t->timestamps()]);
  Schema::create('cards', function(Blueprint $t){$t->id();$t->foreignId('board_id')->constrained()->cascadeOnDelete();$t->foreignId('kanban_list_id')->constrained()->cascadeOnDelete();$t->foreignId('priority_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();$t->foreignId('created_by')->constrained('users')->cascadeOnDelete();$t->string('title');$t->longText('description')->nullable();$t->enum('type',['task','note'])->default('task');$t->enum('status',['open','in_progress','review','done','archived'])->default('open');$t->date('start_date')->nullable();$t->date('due_date')->nullable();$t->decimal('estimated_hours',8,2)->nullable();$t->unsignedInteger('position')->default(0);$t->boolean('archived')->default(false);$t->timestamps();$t->softDeletes();$t->index(['board_id','kanban_list_id','position']);$t->fullText(['title','description']);});
  Schema::create('tags', fn(Blueprint $t)=>[$t->id(),$t->foreignId('board_id')->constrained()->cascadeOnDelete(),$t->string('name'),$t->string('color',20)->default('#64748b'),$t->timestamps(),$t->unique(['board_id','name'])]);
  Schema::create('card_tag', fn(Blueprint $t)=>[$t->foreignId('card_id')->constrained()->cascadeOnDelete(),$t->foreignId('tag_id')->constrained()->cascadeOnDelete(),$t->primary(['card_id','tag_id'])]);
  Schema::create('checklists', fn(Blueprint $t)=>[$t->id(),$t->foreignId('card_id')->constrained()->cascadeOnDelete(),$t->string('name'),$t->timestamps()]);
  Schema::create('checklist_items', fn(Blueprint $t)=>[$t->id(),$t->foreignId('checklist_id')->constrained()->cascadeOnDelete(),$t->string('title'),$t->boolean('is_completed')->default(false),$t->unsignedInteger('sort_order')->default(0),$t->timestamps()]);
  Schema::create('comments', fn(Blueprint $t)=>[$t->id(),$t->foreignId('card_id')->constrained()->cascadeOnDelete(),$t->foreignId('user_id')->constrained()->cascadeOnDelete(),$t->longText('comment'),$t->timestamps(),$t->fullText('comment')]);
  Schema::create('attachments', fn(Blueprint $t)=>[$t->id(),$t->foreignId('card_id')->constrained()->cascadeOnDelete(),$t->foreignId('user_id')->constrained()->cascadeOnDelete(),$t->string('disk')->default('local'),$t->string('path'),$t->string('original_name'),$t->string('mime_type'),$t->unsignedBigInteger('size'),$t->timestamps()]);
  Schema::create('activities', fn(Blueprint $t)=>[$t->id(),$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(),$t->string('action'),$t->morphs('entity'),$t->json('properties')->nullable(),$t->timestamps(),$t->index(['entity_type','entity_id'])]);
 }
 public function down(): void { foreach(['activities','attachments','comments','checklist_items','checklists','card_tag','tags','cards','priorities','kanban_lists','board_members','boards','workspaces','roles'] as $table) Schema::dropIfExists($table); }
};
