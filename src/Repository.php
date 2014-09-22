<?php

namespace codenamegary\L4Utils;

use \Illuminate\Database\Eloquent\Model;

interface Repository {

    public function get($id, array $related = array());

    public function update($id, array $data = array());

    public function create(array $data);

    public function delete($id);

    public function make(array $data = array());

    public function save(Model $model);

}
