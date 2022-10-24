<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use App\Notifications\EmailRegistration;

use Auth;
use Exception;
use Hash;
use Log;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'api';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getLoggedInUser()
    {
        $member = Auth::guard('user')->user();
        return $member;
    }

    public function checkBasicAuthentication($user)
    {
        $email = $user['email'];
        $password = $user['password'];
        $user = self::where('email', $email)->first();
        $response_obj = new \stdClass();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                $response_obj->error = false;
                $response_obj->message = 'Valid Credentials.';
                $response_obj->token = $user->createToken(
                    'User Authentication'
                )->accessToken;
                $response_obj->permissions = $user->getPermissionsViaRoles();
                $user->token = $response_obj->token;
                
                $user->save();
            } else {
                $response_obj->error = true;
                $response_obj->message = 'Invalid Credentials.';
                $response_obj->token = '';
            }
        } else {
            $response_obj->error = true;
            $response_obj->message = 'Your account does not exist.';
            $response_obj->token = '';
        }

        return $response_obj;
    }

    public function saveNewUser($args) {
        $bytes = random_bytes(9);
        $password = bin2hex($bytes);
        $response_obj = new \stdClass();

        $user = self::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($password)
        ]);
        $registrationData = [
            'body' => 'Please login using this password ' . $password. '',
            'text' => 'Please click this to login',
            'url' => url('/'),
            'message' => 'Thank you!'
        ];

        $user->notify(new EmailRegistration($registrationData));

        $user->assignRole('User');
        if ($user) {
            $response_obj->message = 'Please check your inbox';
            $response_obj->error = false;
        } else {
            $response_obj->message = 'User cant be added';
            $response_obj->error = true;
        }

        return $response_obj;
    }
    
    public function getAllUsers() 
    {
        return User::with('roles')->get();
    }
    
    public function createUser($args)   {
        $response_obj = new \stdClass();

        try {
            $user = Auth::user();

            if ($user->can('user_create')) {
                $user = self::create([
                    'name' => $args['name'],
                    'email' => $args['email'],
                    'password' => Hash::make($args['password'])
                ]);
                $registrationData = [
                    'body' => 'Please login using this password ' . $args['password']. '',
                    'text' => 'Please click this to login',
                    'url' => url('/'),
                    'message' => 'Thank you!'
                ];
        
                $user->notify(new EmailRegistration($registrationData));        
    
                $user->assignRole($args['role']);
                if ($user) {
                    $response_obj->message = 'User was added successfully';
                    $response_obj->error = false;
                } else {
                    $response_obj->message = 'User cant be added';
                    $response_obj->error = true;
                }
            } else {
                $response_obj->error = true;
                $response_obj->message = 'You are not authorized to perform this action';
            }
        } catch (Exception $e) {
            Log::debug(print_r($e->getMessage(), true));
            $response_obj->error = true;
            $response_obj->message = $e->getMessage();
        } finally {
            return $response_obj;
        }
    }

    public function updateUser($args) {
        $response_obj = new \stdClass();

        try {
            $user = self::find($args['id']);
            $userAuth = Auth::user();

            if ($userAuth->can('user_edit')) {
                $user->update([
                    'name' => $args['name'],
                    'email' => $args['email'],
                    'password' => $user->password
                ]);
    
                $user->syncRoles($args['role']);
    
                if ($user) {
                    $response_obj->message = 'User was updated successfully';
                    $response_obj->error = false;
                } else {
                    $response_obj->message = 'User cant be added';
                    $response_obj->error = true;
                }
            } else {
                $response_obj->error = true;
                $response_obj->message = 'You are not authorized to perform this action';
            }
        } catch (Exception $e) {
            Log::debug(print_r($e->getMessage(), true));
            $response_obj->error = true;
            $response_obj->message = $e->getMessage();
        } finally {
            return $response_obj;
        }
    }

    public function deleteUser($id) {
        try {
            $response_obj = new \stdClass();
            $user = Auth::user();
    
            if ($user->can('user_delete')) {
                $user = self::findOrFail($id);
                $user->delete();
        
                $response_obj->error = false;
                $response_obj->message = 'User was deleted successfully';
            } else {
                $response_obj->error = true;
                $response_obj->message = 'You are not authorized to perform this action';
            }
            return $response_obj;
        } catch (Expception $e) {
            Log::debug(print_r($e->getMessage(), true));
        }
    }
}
