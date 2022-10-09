<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class CRUDRepository implements RepositoryInterface
{
    protected $model;

    /**
     * PostRepository constructor.
     *
     * @param Model $post
     */
    

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
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
        /* if (null == $user = $this->model->find($id)) {
            throw new ModelNotFoundException("User not found");
        }

        return $user; */
    }
}