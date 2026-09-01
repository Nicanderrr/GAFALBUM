<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminAIController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
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

    public function test_admin_ai_system_context_includes_user_directory(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'name' => 'Admin User',
            'service_number' => 'ADMIN-1',
        ]);

        User::factory()->create([
            'name' => 'Nasara Sulemana',
            'service_number' => 'SN9001',
            'email' => null,
        ]);

        User::factory()->create([
            'name' => 'Kojo Mensah',
            'service_number' => 'SN9002',
        ]);

        $this->actingAs($admin);

        $controller = app(AdminAIController::class);
        $method = new ReflectionMethod($controller, 'getSystemContext');
        $method->setAccessible(true);
        $context = $method->invoke($controller);

        $this->assertStringContainsString('user_directory', $context);
        $this->assertStringContainsString('Nasara Sulemana', $context);
        $this->assertStringContainsString('SN9001', $context);
        $this->assertStringContainsString('recent_users', $context);
    }
}
