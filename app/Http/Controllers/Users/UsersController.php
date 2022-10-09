<?php

namespace App\Http\Controllers;

use App\Exceptions\User\EmailAlreadyExist;
use Illuminate\Http\Request;
use App\Services\Users\CreateUserService;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function getUsers()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // $userName              = $request->input('name');
            // $userLastName          = $request->input('last_name');
            // $userEmail             = $request->input('email');
            // $userPassword          = Hash::make($request->get('password'));
            // $role                  = $request->input('role');
            
            $user_create = new CreateUserService();
            $user = $user_create->__invoke($request);
            
            return response(['msg' => 'Se ha creado el usuario', 'data' => [ 'user_id' => $user]], 201);
        } catch (\Throwable $th) {
            if ($th instanceof EmailAlreadyExist) {
                return $this->errorResponse('Este correo ya existe', 400, $th->getMessage());
            }
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userId                = $request->input('id');
        $userName              = $request->input('name');
        $userLastName          = $request->input('last_name');
        $userEmail             = $request->input('email');
        $userPassword          = Hash::make($request->get('password'));
        $role                  = $request->input('role');

        $user_repository = new UserRepository();

        $data = array(
            'name' => $userName,
            'last_name' => $userLastName,
            'email' => $userEmail,
            'role_id' => $role,
        );

        if ($userPassword) {
            $data['password'] = $userPassword;
        }

        $user =  $user_repository->update($data, $userId);

        return response(['msg' => 'Se ha creado el usuario', 'data' => [ 'user_id' => $user]], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
