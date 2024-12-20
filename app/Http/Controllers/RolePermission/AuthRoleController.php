<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\AuthRoleRequest;
use App\Http\Resources\AuthPermissionResource;
use App\Models\RolePermission\Permission;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class AuthRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(AuthRoleRequest $request): JsonResponse
    {
        try {
            $data = Permission::selectedColumn()
                ->whereNull('parent_id')
                ->with([
                    'auth_all_child_side_bar',
                    'auth_all_child_tab',
                    'permission_action' => function ($query1) {
                        $query1->when(request('user_type') != 'SUPER', function ($query2) {
                            $query2->whereHas('auth_role_permission_action');
                        })
                            ->with('action');
                    }
                ])
                ->when(request('user_type') != 'SUPER', function ($query) {
                    $query->whereIn('id', request('db_auth_permission_ids'));
                })
                ->where('status', true)
                ->orderBy('order_no', 'ASC')
                ->get();
            return ResponseService::globalResponse(
                'success',
                'Auth role permission get successful',
                200,
                [
                    'role_permission' => AuthPermissionResource::collection($data)
//                    'auth_data' => Cache::get('login_info_' . auth()->id())
                ]
            );
        } catch (\Exception $e) {
            return ResponseService::errorResponse($e);
        }
    }


}
