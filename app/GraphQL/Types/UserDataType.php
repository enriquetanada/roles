<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserDataType extends GraphQLType
{
    protected $attributes = [
        'name' => 'UserDataType',
        'description' => 'A user data type information',
    ];

    public function fields(): array
    {
        return [
            'roles' => [
                'type' => Type::listOf(GraphQL::type('RoleType')),
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('UserType')),
            ],
            'error' => [
                'type' => Type::string(),
            ],
            'message' => [
                'type' => Type::string(),
            ],
        ];
    }
}
?>
