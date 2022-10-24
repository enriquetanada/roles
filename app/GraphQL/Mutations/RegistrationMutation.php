<?php
namespace App\GraphQL\Mutations;
use Closure;
use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use Log;

class RegistrationMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Registration',
    ];

    public function args(): array
    {
        return [
            'user' => ['type' => GraphQL::type('LoginInput')],
        ];
    }

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    protected function rules(array $args = []): array
    {
        $rules = [];
        $rules['user.email'] = ['required', 'email', 'unique:users,email'];
        $rules['user.name'] = ['required'];

        return $rules;
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'user.email.required' => 'Please enter your email',
            'user.name.required' => 'Please enter your name',
            'user.email.email' => 'Please enter your valid email address',
            'user.email.unique' => 'Email already exist',
        ];
    }

    public function resolve($root, $args)
    {
        $user = $args['user'];

        $userModel = new User();

        $response_obj = $userModel->saveNewUser($user);

        return $response_obj;
    }
}

?>
