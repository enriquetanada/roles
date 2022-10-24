<?php
namespace App\GraphQL\Mutations;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Spatie\Permission\Models\Permission;

use Log;
use Auth;
class PermissionEditMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Permission',
    ];

    public function args(): array
    {
        return [
            'permission' => ['type' => GraphQL::type('PermissionInput')],
        ];
    }

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    protected function rules(array $args = []): array
    {
        $rules = [];
        $permission = $args['permission'];
        $permissionId = $permission['id'];
        $rules['permission.name'] = ['required', 'unique:permissions,name,' . $permissionId . ',id'];

        return $rules;
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'permission.name.required' => 'Please enter permission name',
            'permission.name.unique' => 'Permission already exist',
        ];
    }

    public function resolve($root, $args)
    {
        $response_obj = new \stdClass();
        $user = Auth::user();

        if ($user->can('permission_edit')) {
            $permission = Permission::find($args['permission']['id']);
        
            $permission->update([
                'name' => $args['permission']['name']
            ]);
            

            $response_obj->error = false;
            $response_obj->message = 'Permission was updated successfully';
        } else {
            $response_obj->error = true;
            $response_obj->message = 'You are not authorized to perform this action';
        }
    
        return $response_obj;
    }
}

?>
