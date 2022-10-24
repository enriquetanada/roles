<?php
namespace App\GraphQL\Mutations;
use Closure;
use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use Log;

class UserAddMutation extends Mutation
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

        $rules['user.name'] = ['required'];
        $rules['user.role'] = ['required'];
        $rules['user.password'] = ['required'];
        $rules['user.email'] = ['required', 'email', 'unique:users,email'];

        return $rules;
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'user.email.required' => 'Please enter an email',
            'user.password.required' => 'Please enter a password',
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

        $response_obj = $userModel->createUser($user);

        return $response_obj;
    }
}

?>
