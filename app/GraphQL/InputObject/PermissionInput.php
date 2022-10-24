<?php

namespace App\GraphQL\InputObject;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class PermissionInput extends InputType
{
    protected $inputObject = true;
    protected $attributes = [
        'name' => 'PermissionInput',
        'description' => 'A permission information',
    ];

    public function fields(): array
    {
        return [
            'name' => [
                'type' => Type::string(),
            ],
            'id' => [
                'type' => Type::int(),
            ],
        ];
    }
}
?>
