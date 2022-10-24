<?php
namespace App\GraphQL\Mutations;
use Closure;
use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use Log;

class UserEditMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Project',
    ];

    public function args(): array
    {
        return [
            'user' => ['type' => GraphQL::type('UserInput')],
        ];
    }

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    protected function rules(array $args = []): array
    {
        $rules = [];
        $user = $args['user'];
        $userId = $user['id'];
        $rules['user.name'] = ['required'];
        $rules['user.role'] = ['required'];
        $rules['user.email'] = ['required', 'email', 'unique:users,email,' . $userId . ',id'];

        return $rules;
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'user.email.required' => 'Please enter an email',
            'user.email.unique' => 'Email already exist',
            'user.email.email' => 'Please enter a valid email',
            'user.name.required' => 'Please enter your name',
            'user.role.required' => 'Please select role',
        ];
    }

    public function resolve($root, $args)
    {
        $user = $args['user'];

        $userModel = new User();

        $response_obj = $userModel->updateUser($user);

        return $response_obj;
    }
}

?>
