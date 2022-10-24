<?php
namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Models\User;
use Spatie\Permission\Models\Permission;

use Auth;
class PermissionQuery extends Query
{
    protected $attributes = [
        'name' => 'PermissionQuery query',
    ];

    public function type(): Type
    {
        $user = Auth::user();
        if ($user->can('permission_view')) {
            return Type::listOf(GraphQL::type('PermissionType'));
        } else {
            return GraphQL::type('ResponseFrontType');
        }
    }

    public function resolve($root, $args)
    {
        $user = Auth::user();
        $response_obj = new \stdClass();

        if ($user->can('permission_view')) {
            return Permission::all();
        } else {
            $response_obj->error = true;
            $response_obj->message = 'You are not authorized to perform this action';
            return $response_obj;
        }        
    }
}

?>
