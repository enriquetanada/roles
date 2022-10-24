<?php

namespace App\GraphQL\InputObject;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class UserInput extends InputType
{
    protected $inputObject = true;
    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'A user information',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::int(),
            ],
            'name' => [
                'type' => Type::string(),
            ],
            'email' => [
                'type' => Type::string(),
            ],
            'role' => [
                'type' => Type::string(),
            ],
            'password' => [
                'type' => Type::string(),
            ],

        ];
    }
}
?>
