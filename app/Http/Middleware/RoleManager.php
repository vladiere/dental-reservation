<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {
        if (!Auth::check()) {
            return redirect()->route("login");
        }
        $auth_role = Auth::user()->role;

        switch ($role) {
            case "admin":
                return $next($request);
                break;
            case "subadmin":
                return $next($request);
                break;
            case "patient":
                return $next($request);
                break;
            case "dentist":
                return $next($request);
                break;
        }

        switch ($auth_role) {
            case "dentist":
                return redirect()->route("dentist_dashboard");
                break;
            case "admin":
                return redirect()->route("admin_dashboard");
                break;
            case "subadmin":
                return redirect()->route("subadmin_dashboard");
                break;
            case "patient":
                return redirect()->route("patient_dashboard");
                break;
            default:
                return redirect()->route("dashboard");
                break;
        }

        return redirect()->route("login");
    }
}