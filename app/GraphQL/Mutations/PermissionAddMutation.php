<?php
namespace App\GraphQL\Mutations;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Log;
use Auth;
use GraphQL\Type\Definition\ResolveInfo;

class PermissionAddMutation extends Mutation
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

        $rules['permission.name'] = ['required', 'unique:permissions,name'];

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

        if ($user->can('permission_create')) {
            Permission::create([
                'name' => $args['permission']['name']
            ]);
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
