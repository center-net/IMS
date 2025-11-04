<?php

namespace App\Http\Middleware;

use App\Models\SystemLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip logging for common non-HTML or internal routes
        $path = $request->path();
        if ($this->shouldSkip($request, $path)) {
            return $next($request);
        }

        $response = $next($request);

        // Only log after response to ensure route info resolved
        try {
            // حاول استخراج عنوان الصفحة من رد HTML
            $title = $this->extractTitleFromResponse($response);

            SystemLog::create([
                'user_id' => optional($request->user())->id,
                'type' => 'route',
                'action' => 'view',
                // خزّن عنوان الصفحة إن وُجد، وإلا استخدم اسم المسار أو المسار النصي
                'route' => $title ?: ($request->route()?->getName() ?: $path),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => (string) $request->header('User-Agent'),
                'context' => [
                    'query' => $request->query(),
                    'input' => $this->trimInput($request->all()),
                    'status' => $response->getStatusCode(),
                ],
                'message' => __('logs.messages.route_view'),
                'locale' => app()->getLocale(),
            ]);
        } catch (\Throwable $e) {
            // Avoid breaking the request pipeline if logging fails
        }

        return $response;
    }

    protected function shouldSkip(Request $request, string $path): bool
    {
        $skipPrefixes = [
            'livewire', 'vendor', 'storage', 'build', 'assets', 'js', 'css', 'images', 'img', 'favicon.ico',
        ];
        foreach ($skipPrefixes as $prefix) {
            if (str_starts_with($path, trim($prefix, '/'))) {
                return true;
            }
        }
        // Skip if AJAX-only Livewire update
        if ($path === 'livewire/update') {
            return true;
        }
        // Skip OPTIONS preflight
        if ($request->method() === 'OPTIONS') {
            return true;
        }
        return false;
    }

    protected function trimInput(array $input): array
    {
        // Avoid logging sensitive fields
        $sensitive = ['password', 'current_password', 'new_password', 'password_confirmation'];
        foreach ($sensitive as $key) {
            if (array_key_exists($key, $input)) {
                $input[$key] = '***';
            }
        }
        return $input;
    }

    /**
     * Extract page <title> from HTML response if available.
     */
    protected function extractTitleFromResponse(Response $response): ?string
    {
        $contentType = $response->headers->get('Content-Type');
        // اعمل فقط على HTML
        if ($contentType && !str_contains(strtolower($contentType), 'text/html')) {
            return null;
        }
        $content = method_exists($response, 'getContent') ? $response->getContent() : null;
        if (!$content || !is_string($content)) {
            return null;
        }
        // التقط أول عنصر <title> في المستند
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $content, $m)) {
            $title = trim(html_entity_decode($m[1] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
            return $title !== '' ? $title : null;
        }
        return null;
    }
}
