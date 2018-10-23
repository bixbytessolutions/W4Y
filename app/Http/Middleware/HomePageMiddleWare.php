<?php

namespace App\Http\Middleware;

use Closure;

class HomePageMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = $request->session();
        if(\Auth::check()){
            if($session->get('bid_redirect_url')!=null){
                $url = $session->get('bid_redirect_url');
                $session->forget('bid_redirect_url');
                return redirect($url);
            }
        }
        return $next($request);
    }
}
