<?php
namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Models\User;
use App\Models\Helper;
use Log;
use Auth;

class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'UserQuery query',
    ];

    public function type(): Type
    {
        return GraphQL::type('UserType');
    }
    public function resolve($root, $args)
    {
        try {
            if (Auth::guard('user')->check()) {
                $user = new User();
                $userData = $user->getLoggedInUser();
                return $userData;
            }
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}

?>
