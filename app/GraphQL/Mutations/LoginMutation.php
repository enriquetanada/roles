<?php
namespace App\GraphQL\Mutations;
use Closure;
use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use Log;

class LoginMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Login',
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
        $rules['user.email'] = ['required', 'email'];
        $rules['user.password'] = ['required'];

        return $rules;
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'user.email.required' => 'Please enter your email',
            'user.password.required' => 'Please enter your password',
            'user.email.email' => 'Please enter your valid email address'
        ];
    }

    public function resolve($root, $args)
    {
        $user = $args['user'];

        $userModel = new User();

        $response_obj = $userModel->checkBasicAuthentication($user);

        return $response_obj;
    }
}

?>
