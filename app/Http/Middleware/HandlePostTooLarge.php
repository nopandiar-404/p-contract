<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpFoundation\Response;

class HandlePostTooLarge
{
    /**
     * Middleware untuk menangani error POST size yang terlalu besar
     * dengan pesan yang lebih user-friendly
     *
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (PostTooLargeException $e) {
            // Get configured limits
            $postMaxSize = $this->getFormattedSize(ini_get('post_max_size'));
            $uploadMaxFilesize = $this->getFormattedSize(ini_get('upload_max_filesize'));
            
            $message = "Upload file terlalu besar. ";
            $message .= "Batas maksimal upload: " . $uploadMaxFilesize . ". ";
            $message .= "Batas maksimal POST: " . $postMaxSize . ". ";
            $message .= "Silakan upload file yang lebih kecil.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'error' => 'post_too_large',
                    'limits' => [
                        'upload_max_filesize' => $uploadMaxFilesize,
                        'post_max_size' => $postMaxSize
                    ]
                ], 413);
            }
            
            return back()
                ->withError($message)
                ->withInput();
        }
    }
    
    /**
     * Convert bytes to human-readable format
     */
    private function getFormattedSize($value)
    {
        if ($value === '-1') return 'Unlimited';
        
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int)$value;
        
        switch ($last) {
            case 'g':
                return round($value, 2) . ' GB';
            case 'm':
                return round($value, 2) . ' MB';
            case 'k':
                return round($value / 1024, 2) . ' MB';
            default:
                return round($value / 1024 / 1024, 2) . ' MB';
        }
    }
}
