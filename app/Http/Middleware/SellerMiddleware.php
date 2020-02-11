<?php
namespace App\Http\Middleware;
use App\Property;
use Closure;
use Illuminate\Support\Facades\Auth;
class SellerMiddleware
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


        if(Auth::user()->role_id==2){
            return $next($request);

            //return redirect('seller/dashboard');
       }
        return redirect('/');
    }
}
