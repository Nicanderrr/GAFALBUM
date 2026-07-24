<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAiAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ai_chat_route_requires_configuration(): void
    {
        config()->set('services.openai.api_key', null);
        putenv('GROQ_API_KEY');
        $_ENV['GROQ_API_KEY'] = null;
        $_SERVER['GROQ_API_KEY'] = null;

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.ai.chat'), [
                'message' => 'Give me a summary.',
            ])
            ->assertStatus(422)
            ->assertJson([
                'error' => 'AI is not configured. Add OPENAI_API_KEY or GROQ_API_KEY to the environment file.',
            ]);
    }
}
