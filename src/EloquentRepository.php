<?php

namespace codenamegary\L4Utils;

use \Illuminate\Database\Eloquent\Model;
use \Exception;

abstract class EloquentRepository implements Repository {

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    abstract protected function getModel();

    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function get($id, array $related = array())
    {
        return $this->newQuery()->with($related)->where('id', $id)->first();
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = array())
    {
        if (!$model = $this->get($id))
            throw new Exception('ERROR: Cannot update record with id ' . $id . ', does not exist.');
        $model->fill($data);
        $this->save($model);
        return $model;
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $model = $this->make($data);
        $this->save($model);
        return $model;
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function delete($id)
    {
        if (!$model = $this->get($id))
            throw new Exception('ERROR: Cannot delete record with id ' . $id . ', does not exist.');
        $model->delete();
        return $model;
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function make(array $data = array())
    {
        $model = $this->getModel();
        $model->fill($data);
        return $model;
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function save(Model $model)
    {
        $model->save();
        return $model;
    }
	
	/**
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function filter(callback $callback, $query = null)
	{
		if(!$query) $query = $this->newQuery();
		$callback($query);
		return $query;
	}

}
