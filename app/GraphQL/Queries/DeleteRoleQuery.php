<?php
namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Spatie\Permission\Models\Role;
use Log;
use Auth;

class DeleteRoleQuery extends Query
{
    protected $attributes = [
        'name' => 'DeleteRoleQuery query',
    ];

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    public function args(): array
    {
        return [
            'deleteRole' => ['type' => Type::Int()],
        ];
    }
    public function resolve($root, $args)
    {
        $response_obj = new \stdClass();
        $user = Auth::user();
        
        if ($user->can('role_delete')) {
            $role = Role::find($args['deleteRole']);
            $role->delete();
    
            $response_obj->error = false;
            $response_obj->message = 'Role was deleted successfully';
        } else {
            $response_obj->error = true;
            $response_obj->message = 'You are not authorized to perform this action';
        }
        return $response_obj;
    }
}

?>
