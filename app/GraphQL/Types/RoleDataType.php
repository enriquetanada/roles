<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleDataType extends GraphQLType
{
    protected $attributes = [
        'name' => 'RoleDataType',
        'description' => 'A user data type information',
    ];

    public function fields(): array
    {
        return [
            'roles' => [
                'type' => Type::listOf(GraphQL::type('RoleType')),
            ],
            'permissions' => [
                'type' => Type::listOf(GraphQL::type('PermissionType')),
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
