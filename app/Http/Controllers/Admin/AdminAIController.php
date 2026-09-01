<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\SiteHero;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminAIController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'history' => ['nullable', 'array'],
            'history.*.role' => ['required_with:history', 'in:user,assistant'],
            'history.*.content' => ['required_with:history', 'string', 'max:4000'],
        ]);

        $provider = $this->chatProvider();

        if (! $provider) {
            return response()->json([
                'error' => 'AI is not configured. Add OPENAI_API_KEY or GROQ_API_KEY to the environment file.',
            ], 422);
        }

        try {
            $messages = array_merge(
                [[
                    'role' => 'system',
                    'content' => $this->getSystemContext(),
                ]],
                array_slice($validated['history'] ?? [], -10),
                [[
                    'role' => 'user',
                    'content' => $validated['message'],
                ]]
            );

            $response = Http::withToken($provider['api_key'])
                ->timeout(45)
                ->post($provider['url'], [
                    'model' => $provider['model'],
                    'messages' => $messages,
                    'temperature' => 0.45,
                    'max_tokens' => 900,
                ]);

            if ($response->failed()) {
                $providerMessage = (string) data_get($response->json(), 'error.message', '');
                $providerCode = (string) data_get($response->json(), 'error.code', '');

                Log::warning('GAF admin AI chat failed', [
                    'provider' => $provider['name'],
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'error' => $this->providerErrorMessage($provider['name'], $response->status(), $providerMessage, $providerCode),
                ], 502);
            }

            return response()->json([
                'reply' => $this->normalizePlainReply((string) data_get($response->json(), 'choices.0.message.content', 'I could not generate a response.')),
            ]);
        } catch (\Throwable $exception) {
            Log::error('GAF admin AI chat exception', ['message' => $exception->getMessage()]);

            return response()->json([
                'error' => 'AI request failed. Check the logs for details.',
            ], 500);
        }
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'prompt' => ['nullable', 'string', 'max:2000'],
        ]);

        $provider = $this->visionProvider();

        if (! $provider) {
            return response()->json([
                'error' => 'AI image analysis is not configured. Add OPENAI_API_KEY or GROQ_API_KEY to the environment file.',
            ], 422);
        }

        $file = $validated['file'];
        $prompt = $validated['prompt'] ?: 'Analyze this image for the GAFALBUM admin team.';
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return response()->json([
                'reply' => 'I can currently analyze images only. Please upload a JPG, PNG, or WEBP file.',
            ]);
        }

        try {
            $base64Image = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = (string) $file->getMimeType();

            $response = Http::withToken($provider['api_key'])
                ->timeout(60)
                ->post($provider['url'], [
                    'model' => $provider['model'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an admin assistant for GAFALBUM. Analyze gallery images, event photos, ceremony media, screenshots, receipts, branding assets, and admin uploads. Be practical, concise, and operations-focused.',
                        ],
                        [
                            'role' => 'user',
                            'content' => [
                                ['type' => 'text', 'text' => $prompt],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => "data:{$mimeType};base64,{$base64Image}",
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'temperature' => 0.35,
                    'max_tokens' => 900,
                ]);

            if ($response->failed()) {
                $providerMessage = (string) data_get($response->json(), 'error.message', '');
                $providerCode = (string) data_get($response->json(), 'error.code', '');

                Log::warning('GAF admin AI image analysis failed', [
                    'provider' => $provider['name'],
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'error' => $this->providerErrorMessage($provider['name'], $response->status(), $providerMessage, $providerCode),
                ], 502);
            }

            return response()->json([
                'reply' => $this->normalizePlainReply((string) data_get($response->json(), 'choices.0.message.content', 'I could not analyze that image.')),
            ]);
        } catch (\Throwable $exception) {
            Log::error('GAF admin AI image analysis exception', ['message' => $exception->getMessage()]);

            return response()->json([
                'error' => 'Image analysis failed. Check the logs for details.',
            ], 500);
        }
    }

    private function chatProvider(): ?array
    {
        $openAiKey = config('services.openai.api_key');

        if ($openAiKey) {
            return [
                'name' => 'OpenAI',
                'api_key' => $openAiKey,
                'url' => 'https://api.openai.com/v1/chat/completions',
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            ];
        }

        $groqKey = env('GROQ_API_KEY');

        if ($groqKey) {
            return [
                'name' => 'Groq',
                'api_key' => $groqKey,
                'url' => 'https://api.groq.com/openai/v1/chat/completions',
                'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
            ];
        }

        return null;
    }

    private function visionProvider(): ?array
    {
        $openAiKey = config('services.openai.api_key');

        if ($openAiKey) {
            return [
                'name' => 'OpenAI',
                'api_key' => $openAiKey,
                'url' => 'https://api.openai.com/v1/chat/completions',
                'model' => env('OPENAI_VISION_MODEL', 'gpt-4o-mini'),
            ];
        }

        $groqKey = env('GROQ_API_KEY');

        if ($groqKey) {
            return [
                'name' => 'Groq',
                'api_key' => $groqKey,
                'url' => 'https://api.groq.com/openai/v1/chat/completions',
                'model' => env('GROQ_VISION_MODEL', 'llama-3.2-11b-vision-preview'),
            ];
        }

        return null;
    }

    private function providerErrorMessage(string $provider, int $status, string $message, string $code): string
    {
        $details = trim($message.($code !== '' ? ' ('.$code.')' : ''));

        if ($details !== '') {
            return $provider.' error: '.$details;
        }

        return $provider.' API error: HTTP '.$status;
    }

    private function normalizePlainReply(string $reply): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $reply);
        $text = preg_replace('/[`*_#>~]+/u', '', $text);
        $text = preg_replace('/^\s*[-\x{2022}]\s*/mu', '', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        $text = preg_replace('/[ \t]{2,}/', ' ', $text);

        return trim((string) $text);
    }

    private function getSystemContext(): string
    {
        $viewer = auth()->user();
        $successfulTransactions = Transaction::query()->where('status', 'success');
        $pendingTransactions = Transaction::query()->where('status', 'pending');

        $recentPayments = Transaction::with(['user', 'image', 'items.media'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (Transaction $transaction) => [
                'reference' => $transaction->reference,
                'status' => $transaction->status,
                'amount' => (float) $transaction->amount,
                'user' => $transaction->user?->name,
                'service_number' => $transaction->user?->service_number,
                'event' => $transaction->image?->title,
                'items' => $transaction->items->count(),
                'created_at' => optional($transaction->created_at)->format('Y-m-d H:i'),
            ])
            ->values()
            ->all();

        $recentEvents = Image::with(['category', 'media'])
            ->latest()
            ->take(12)
            ->get()
            ->map(fn (Image $image) => [
                'id' => $image->id,
                'title' => $image->title,
                'status' => $image->status ?? 'published',
                'category' => $image->category?->name,
                'price' => (float) $image->price,
                'media_count' => $image->media->count(),
                'published_at' => optional($image->published_at)->format('Y-m-d H:i'),
                'created_at' => optional($image->created_at)->format('Y-m-d H:i'),
            ])
            ->values()
            ->all();

        $recentUsers = User::query()
            ->where('is_admin', false)
            ->latest()
            ->take(20)
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'service_number' => $user->service_number,
                'email' => $user->email,
                'created_at' => optional($user->created_at)->format('Y-m-d H:i'),
            ])
            ->values()
            ->all();

        $userDirectory = User::query()
            ->where('is_admin', false)
            ->orderBy('name')
            ->take(250)
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'service_number' => $user->service_number,
                'email' => $user->email,
            ])
            ->values()
            ->all();

        $categoryBreakdown = Category::withCount('images')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => [
                'name' => $category->name,
                'events' => (int) $category->images_count,
            ])
            ->values()
            ->all();

        $heroImages = SiteHero::orderBy('key')
            ->get()
            ->map(fn (SiteHero $hero) => [
                'key' => $hero->key,
                'has_image' => (bool) $hero->image_path,
                'updated_at' => optional($hero->updated_at)->format('Y-m-d H:i'),
            ])
            ->values()
            ->all();

        $topEvents = TransactionItem::query()
            ->selectRaw('images.title, COUNT(transaction_items.id) as files_sold, SUM(transaction_items.amount) as revenue')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('images', 'images.id', '=', 'transaction_items.image_id')
            ->where('transactions.status', 'success')
            ->groupBy('images.title')
            ->orderByDesc('revenue')
            ->take(10)
            ->get()
            ->map(fn ($row) => [
                'title' => $row->title,
                'files_sold' => (int) $row->files_sold,
                'revenue' => (float) $row->revenue,
            ])
            ->values()
            ->all();

        $context = [
            'business' => [
                'name' => 'GAFALBUM',
                'focus' => 'protected event gallery, paid downloads for photos and videos, and admin management of gallery media',
                'viewer' => [
                    'name' => $viewer?->name,
                    'service_number' => $viewer?->service_number,
                    'is_admin' => (bool) ($viewer?->is_admin ?? false),
                ],
            ],
            'snapshot' => [
                'events' => Image::count(),
                'published_events' => Image::query()->where('status', 'published')->count(),
                'draft_events' => Image::query()->where('status', 'draft')->count(),
                'archived_events' => Image::query()->where('status', 'archived')->count(),
                'media_files' => ImageMedia::count(),
                'categories' => Category::count(),
                'users' => User::query()->where('is_admin', false)->count(),
                'admins' => User::query()->where('is_admin', true)->count(),
                'cart_items' => CartItem::count(),
                'successful_payments' => (clone $successfulTransactions)->count(),
                'pending_payments' => (clone $pendingTransactions)->count(),
                'revenue' => (float) (clone $successfulTransactions)->sum('amount'),
                'pending_value' => (float) (clone $pendingTransactions)->sum('amount'),
                'files_sold' => TransactionItem::whereHas('transaction', fn ($query) => $query->where('status', 'success'))->count(),
            ],
            'recent_events' => $recentEvents,
            'recent_users' => $recentUsers,
            'user_directory' => $userDirectory,
            'recent_payments' => $recentPayments,
            'top_events' => $topEvents,
            'categories_breakdown' => $categoryBreakdown,
            'hero_images' => $heroImages,
            'admin_routes' => [
                'dashboard' => route('admin.dashboard'),
                'events' => route('admin.images.index'),
                'categories' => route('admin.categories.index'),
                'payments' => route('admin.payments.index'),
                'hero_images' => route('admin.site-heroes.index'),
                'admins' => route('admin.admins.index'),
                'users' => route('admin.users.index'),
            ],
        ];

        return "You are GAFALBUM Admin AI, embedded in the Laravel admin panel. "
            ."Help the admin understand gallery operations, event publishing, hero images, users, payments, purchases, and the exact admin workflow available in this system. "
            ."You have a live user directory below. If asked who a user is, whether a user exists, or to identify someone by name or service number, answer from that directory first. "
            ."If a person is not present in the directory, say you cannot find that user in the current system context. "
            ."Use the live system context below when answering. If asked to perform an action, explain the exact admin page or workflow; do not claim you changed database records. "
            ."If the answer is in the context, answer directly with numbers. Keep answers short, practical, and operations-focused. "
            .'System context JSON: '.json_encode($context, JSON_PRETTY_PRINT);
    }
}
