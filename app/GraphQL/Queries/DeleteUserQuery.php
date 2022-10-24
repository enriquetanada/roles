<?php
namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Models\User;
use App\Models\Helper;
use Log;
use Auth;

class DeleteUserQuery extends Query
{
    protected $attributes = [
        'name' => 'DeleteUserQuery query',
    ];

    public function type(): Type
    {
        return GraphQL::type('ResponseFrontType');
    }

    public function args(): array
    {
        return [
            'deleteUser' => ['type' => Type::Int()],
        ];
    }
    public function resolve($root, $args)
    {
        $user = new User();

        if (isset($args['deleteUser'])) {
            $user->deleteUser($args['deleteUser']);
        }

        return $user;
    }
}

?>
