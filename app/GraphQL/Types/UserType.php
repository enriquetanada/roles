<?php
namespace App\GraphQL\Types;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\CustomScalarType;
use App\Models\User;
use Crypt;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'UserType',
        'description' => 'A user type information',
        'model' => User::class,
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
            'email' => [
                'type' => Type::string(),
                'alias' => 'email',
            ],
            'roles' => [
                'type' => Type::listOf(GraphQL::type('EachRoleType')),
            ],
            
        ];
    }
}
?>
