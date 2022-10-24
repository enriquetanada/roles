<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PermissionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'PermissionType',
        'description' => 'A permission type information'
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
