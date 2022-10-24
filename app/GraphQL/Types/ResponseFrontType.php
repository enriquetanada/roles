<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ResponseFrontType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ResponseFrontType',
        'description' => 'A response type information',
    ];

    public function fields(): array
    {
        return [
            'error' => [
                'type' => Type::boolean(),
            ],
            'message' => [
                'type' => Type::string(),
            ],
            'token' => [
                'type' => Type::string(),
            ],
            'permissions' => [
                'type' => Type::listOf(GraphQL::type('PermissionType')),
            ],
            'id' => [
                'type' => Type::string(),
            ],
            'name' => [
                'type' => Type::string(),
            ],
            'roles' => [
                'type' => Type::listOf(GraphQL::type('RoleType')),
            ],
            'permissions' => [
                'type' => Type::listOf(GraphQL::type('PermissionType')),
            ],
            'roles' => [
                'type' => Type::listOf(GraphQL::type('RoleType')),
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('UserType')),
            ],
        ];
    }
}
?>
