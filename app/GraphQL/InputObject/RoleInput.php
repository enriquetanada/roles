<?php

namespace App\GraphQL\InputObject;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class RoleInput extends InputType
{
    protected $inputObject = true;
    protected $attributes = [
        'name' => 'RoleInput',
        'description' => 'A role information',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::int(),
            ],
            'role' => [
                'type' => Type::string(),
            ],
            'permission' => [
                'type' => Type::string(),
            ]

        ];
    }
}
?>
