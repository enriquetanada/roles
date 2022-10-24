<?php
namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Log;
use Auth;

class DeletePermissionQuery extends Query
{
    protected $attributes = [
        'name' => 'DeletePermissionQuery query',
    ];

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    public function args(): array
    {
        return [
            'deletePermission' => ['type' => Type::Int()],
        ];
    }
    public function resolve($root, $args)
    {
        $response_obj = new \stdClass();
        $user = Auth::user();
        
        if ($user->can('permission_delete')) {
            $permission = Permission::find($args['deletePermission']);
            $permission->delete();

            $response_obj->error = false;
            $response_obj->message = 'Permission was deleted successfully';
        } else {
            $response_obj->error = true;
            $response_obj->message = 'You are not authorized to perform this action';
        }

        return $response_obj;
    }
}

?>
