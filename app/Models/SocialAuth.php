<?php

namespace App\Models;

use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Facades\Socialite;

class SocialAuth extends Model
{
    /**
     *  @param string $key
     *  @param string|array $key
     */
     public static function path($key = null)
    {
        $table = (new self)->getTable();
        $tableUcwords = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));

        $path = [
            'table'  => $table,
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'Controller',
        ];
        return $key ? @$path[$key] : $path;
    }

     /**
     *  @param string $key
     *  @param string $flag
     *  @return array
     */
    public static function validate($key = null, $flag = '[]')
    {
        $class = self::path('requests');
        $formRequests = new $class;
        $validate =  [
            'rules'       =>  $formRequests->rules($flag),
            'attributes'  =>  $formRequests->attributes($flag),
            'messages'    =>  $formRequests->messages($flag),
            'questions'   =>  $formRequests->questions($flag),
        ];
        return $key? @$validate[$key] : $validate;
    }

    public static function getData($id, $user_id = null)
    {
        if ($id) {
            $get =  self::where('id', $id)->first();
        } elseif ($user_id) {
            $get = self::where('user_id', $user_id)->first();
        }

        if ($get) {
            return $get->toArray();
        }
    }

    public static function checkUser($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $ck = self::where('provider', $provider)
                ->where('_id', $user->getId())
                ->where('_email', $user->getEmail())
                ->first();
            $ck_user = Users::where('email', $user->getEmail())
                ->first();
            if ($ck) {
                if ($ck_user) {
                    Auth::loginUsingId($ck_user->id, true);
                    return self::redirect($ck_user->role_id);
                } else {
                    $create_user = Users::insertGetId([
                        'name'  => $user->getName(),
                        'email'  => $user->getEmail(),
                        'password'  => Hash::make(123456),
                        'role_id'   => 9,
                    ]);
                    if ($create_user) {
                        $update = self::updateUserToTable($ck->id, $create_user);
                        if ($update['success']) {
                            Auth::loginUsingId($create_user, true);
                            return self::redirect(9);
                        }
                    }
                }
            } elseif ($ck_user) {
                $add = self::addToTable($user, $provider);
                if ($add['success']) {
                    $update = self::updateUserToTable($add['data']['id'], $ck_user->id);
                    if ($update['success']) {
                        Auth::loginUsingId($ck_user->id, true);
                        return self::redirect($ck_user->role_id);
                    }
                }
            } else {
                $create_user = Users::insertGetId([
                    'name'  => $user->getName(),
                    'email'  => $user->getEmail(),
                    'password'  => Hash::make(123456),
                    'role_id'   => 9,
                ]);

                if ($create_user) {
                    $add = self::addToTable($user, $provider);
                    if ($add['success']) {
                        $update = self::updateUserToTable($add['data']['id'], $create_user);
                        if ($update['success']) {
                            Auth::loginUsingId($create_user, true);
                            return self::redirect(9);
                        }
                    }
                }
            }
        } catch (DomainException $e) {
            return $e;
        }
    }

    public static function addToTable($user, $provider)
    {
        if ($user) {
            $add = self::insertGetId([
                'provider'   => $provider,
                '_id'   => $user->getId(),
                '_email'   => $user->getEmail(),
                '_name'   => $user->getName(),
                '_avatar'   => $user->getAvatar(),
            ]);
            if ($add) {
                $response = [
                    'success'   => true,
                    'data'      => self::getData($add),
                ];
            }
        }
        return $response;
    }

    public static function updateUserToTable($id, $user_id)
    {
        if ($id && $user_id) {
            $update = self::where('id', $id)->update([
                'user_id'   => $user_id
            ]);
            if ($update) {
                $response = [
                    'success'   => true
                ];
            }
        }
        return $response;
    }

    public static function redirect($role_id)
    {
        $role = Roles::where('id', $role_id)->first();
        return redirect($role->name);
    }
}
