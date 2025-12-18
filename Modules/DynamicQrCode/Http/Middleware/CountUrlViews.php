<?php

namespace Modules\DynamicQrCode\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountUrlViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->fullUrl();

        $view = DB::table('dynamic_qr_codes')->where('url', $url)->first();

        if ($view) {
            // Erhöhen Sie den Zähler, wenn die URL bereits vorhanden ist
            DB::table('dynamic_qr_codes')->where('url', $url)->increment('view_count');
        } else {
            // Wenn die URL nicht vorhanden ist, fügen Sie sie hinzu
            DB::table('dynamic_qr_codes')->insert(['url' => $url, 'view_count' => 1]);
        }

        return $next($request);
    }
}
