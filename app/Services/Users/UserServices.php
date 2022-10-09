<?php

namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use Src\Users\Object\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserServices
{
    public function CreateUser(
        Request $request
    ) {
        $userName              = $request->input('name');
        $userLastName          = $request->input('last_name');
        $userEmail             = $request->input('email');
        $userPassword          = Hash::make($request->get('password'));
        $role                  = $request->input('role');

        $already_exist = $this->repository->find_by_email($userEmail);
        if ($already_exist) {
            throw new Exception("Error Processing Request", 1);
        }

        $data = array(
            'name' => $userName,
            'last_name' => $userLastName,
            'email' => $userEmail,
            'password' => $userPassword,
            'role_id' => $role,
        );

        $user =  $this->repository->create($data);
        return $user;
    }

    public function FindUserByEmail(
        string $email
    ) {
        return $this->repository->find_by_email($email);
    }

    public function GetUsers($offset, $limit, $search, $status)
    {
        if ($search == "") {
            $query = $this->repository->get_users_by_offset_and_limit($offset, $limit);
        } else {
            $query = $this->repository->get_users_by_offset_and_limit_and_search($offset, $limit, $search);
        }

        return $query;
    }

    public function UpdateUser(
        $userId,
        $userName,
        $userEmail,
        $userPassword
    ) {

        return $this->repository->update(
            $userId,
            $userName,
            $userEmail,
            $userPassword
        );
    }
}
