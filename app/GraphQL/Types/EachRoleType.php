<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EachRoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'EachRoleType',
        'description' => 'A each role type information'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::int(),
            ],
            'name' => [
                'type' => Type::string(),
            ]
        ];
    }
}
?>
