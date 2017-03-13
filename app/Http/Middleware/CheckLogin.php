<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $member = $request->session()->get('member');
    if (!$member) {
      return redirect('/login');
    }

    return $next($request);
  }
}
