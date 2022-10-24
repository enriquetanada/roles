<?php
namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Log;
uSE Auth;
class RoleDataQuery extends Query
{
    protected $attributes = [
        'name' => 'RoleDataQuery query',
    ];

    public function type(): Type
    {
        $user = Auth::user();
        if ($user->can('role_view')) {
            return GraphQL::type('RoleDataType');
        } else {
            return GraphQL::type('ResponseFrontType');
        }
    }

    public function resolve($root, $args)
    {
        $user = Auth::user();
        $response_obj = new \stdClass();

        if ($user->can('role_view')) {
            $response_obj = new \stdClass();
            $response_obj->roles = Role::with('permissions')->get();
            $response_obj->permissions = Permission::all();
            return $response_obj;
        } else {
            $response_obj->error = true;
            $response_obj->message = 'You are not authorized to perform this action';
            return $response_obj;
        }  
    }
}

?>
