<?php
namespace Liip\Cors;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware {
    public function handle(Request $request, Closure $next) {
        
        $response = $next($request);                                        
        
        // just allow everything
        $response->header('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Allow-Headers', strtoupper($request->headers->get('Access-Control-Request-Headers')));
        $response->header('Access-Control-Allow-Methods',  strtoupper($request->headers->get('Access-Control-Request-Method')));

        return $response;
    }
}
