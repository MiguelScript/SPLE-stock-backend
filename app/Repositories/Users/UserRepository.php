<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    private $table = "users";
    
    protected $model;

    /**
     * UserRepository constructor.
     *
     * @param User $post
     */
    public function __construct()
    {
        $this->model = new User();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function get_users_by_offset_and_limit($offset, $limit)
    {
        return $this->model->skip($offset)->take($limit)->orderBy('id', 'desc')->get();
    }

    public function get_users_by_offset_and_limit_and_search($offset, $limit, $search)
    {
        return $this->model->where('nombre', 'like', '%' . $search . '%')->skip($offset)->take($limit)->orderBy('id', 'desc')->get();
    }

    public function create($data)
    {
        return $this->model->insert($data);
    }

    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (null == $user = $this->model->find($id)) {
            throw new ModelNotFoundException("User not found");
        }

        return $user;
    }

    public function find_by_email($email)
    {
        return DB::table($this->table)
        ->where("email", '=', $email)
        ->first();
    }
}
