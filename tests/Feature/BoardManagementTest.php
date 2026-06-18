<?php
namespace Tests\Feature;
use App\Models\User; use Illuminate\Foundation\Testing\RefreshDatabase; use Tests\TestCase;
class BoardManagementTest extends TestCase { use RefreshDatabase; public function test_user_can_create_board(): void { $user=User::factory()->create(); $this->actingAs($user)->postJson('/api/boards',['name'=>'Launch','visibility'=>'private'])->assertOk()->assertJsonPath('name','Launch'); } }
