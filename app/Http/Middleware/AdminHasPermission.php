<?php

namespace App\Http\Middleware;

use App\Models\AdminMenuActivity;
use App\Models\AdminRolePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->user_type == "admin") {
            return $next($request);
        } else {
            $request_route = \Request::route()->getName();
            $activity = AdminMenuActivity::where('route_name', $request_route)->first();
            $user_role = Auth::user()->role_id;
            if (isset($activity)) {
                $activity_id = $activity->id;
                $condition = [
                    'role_id' => $user_role,
                    'activity_id' => $activity_id
                ];
                if (AdminRolePermission::where($condition)->exists()) {
                    return $next($request);
                } else {
                    return permissionDenied();
                }
            } else {
                return permissionDenied();
            }
        }
    }
}
