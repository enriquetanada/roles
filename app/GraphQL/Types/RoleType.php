<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'RoleType',
        'description' => 'A role type information'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::int(),
                'alias' => 'id',
            ],
            'name' => [
                'type' => Type::string(),
                'alias' => 'name',
            ],
            'permissions' => [
                'type' => Type::listOf(GraphQL::type('PermissionType')),
            ],
        ];
    }
}
?>
