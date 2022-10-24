<?php
namespace App\GraphQL\Mutations;
use Closure;
use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Spatie\Permission\Models\Role;

use Log;
use Auth;

class RoleAddMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Role',
    ];

    public function args(): array
    {
        return [
            'role' => ['type' => GraphQL::type('RoleInput')],
        ];
    }

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    protected function rules(array $args = []): array
    {
        $rules = [];

        $rules['role.role'] = ['required'];
        $rules['role.permission'] = ['required'];

        return $rules;
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'role.role.required' => 'Please enter a role',
            'role.permission.required' => 'Please select a permission',
        ];
    }

    public function resolve($root, $args)
    {
        $response_obj = new \stdClass();
        $user = Auth::user();

        if ($user->can('role_create')) {
            $roles = $args['role'];
            $role = Role::create([
                'name' => $roles['role']
            ]);
            $role->givePermissionTo(json_decode($roles['permission']));
            
            $response_obj->error = false;
            $response_obj->message = 'Permission was added successfully';
        } else {
            $response_obj->error = true;
            $response_obj->message = 'You are not authorized to perform this action';
        }

        return $response_obj;
    }
}

?>
