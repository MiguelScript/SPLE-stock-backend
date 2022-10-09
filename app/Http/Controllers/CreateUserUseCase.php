<?php

namespace Src\Users\UseCases;

use Src\Users\Services\CreateUserService;
use Src\Users\Services\FindUserByEmail;
use Illuminate\Support\Facades\Hash;
use Exception;

class CreateUserUseCase
{

    protected $create_users_service;
    protected $find_user_by_email;

    public function __construct(CreateUserService $create_users_service, FindUserByEmail  $find_user_by_email)
    {
        $this->create_users_service = $create_users_service;
        $this->find_user_by_email = $find_user_by_email;
    }

    public function __invoke($request)
    {

        try {
            $userName              = $request->input('name');
            $userLastName          = $request->input('last_name');
            $userEmail             = $request->input('email');
            $userPassword          = Hash::make($request->get('password'));
            $role                  = $request->input('role');

            $already_exist = $this->find_user_by_email->__invoke($userEmail);
            var_dump($already_exist);
            /* if (!empty($already_exist)) {
                throw new Exception("User Already Exist");
            } */

            $user_id = $this->create_users_service->__invoke(
                $userName,
                $userLastName,
                $userEmail,
                $userPassword,
                $role
            );

            return response(['msg' => 'Se ha creado el usuaio', 'user' => $user_id], 201);
        } catch (\Throwable $th) {

            $response = array();
            //var_dump($th);
            if ($th->getMessage() == "User Already Exist") {
                $response['msg'] = "Este usuario ya existe";
            }
            return response($response, 400);
        }
    }
}
