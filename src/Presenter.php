<?php

namespace codenamegary\L4Utils;

use \InvalidArgumentException;
use \Exception;

class Presenter {

    /**
     * This presenter can present data from either an array or
     * an object. It will intelligently detect which type of
     * data you have set and return the matching property
     * or array element or getter method.
     *
     * @var mixed
     */
    protected $data;

    /**
     * @param mixed $data     Any type of object or an array of data you wish to present.
     */
    public function __construct($data = null)
    {
        $this->setData($data);
    }

    /**
     * @param mixed $data     Any type of object or an array of data you wish to present.
     */
    public function setData($data = null)
    {
        if(!is_null($data) && !is_array($data) && !is_object($data))
            throw new InvalidArgumentException('Presenter: $data must be null, object or array.');
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function get($name, $default = false)
    {
        if(null !== $value = $this->getLocalData($name, null))
            return $value;
        return $this->getRaw($name, $default);
    }

    public function getRaw($name, $default = false)
    {
        $dataType = gettype($this->data);
        $handlerMethod = 'get' . ucfirst($dataType) . 'Data';
        return $this->$handlerMethod($name, $default);
    }

    /**
     * Magic method that handles all requests for properties that
     * do not exist locally in this class. It will interrupt and
     * return the matching item from $this->data.
     *
     * @param string $name
     */
    public function __get($name)
    {
        return $this->get($name, false);
    }

    /**
     * Takes missing method calls and attempts to execute them based on the data object.
     * 
     * @param string $name      Name of the function being called
     * @param array $arguments  Array of arguments being passed to the function.
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if(is_object($this->data) && method_exists($this->data, $name))
            return call_user_func_array(array($this->data, $name), $arguments);
        throw new Exception('Presenter: No such function exists on the presenter or data (' . $name . ')');
    }

    /**
     * If the configured data is null, this handler will
     * be executed when retrieving properties.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    protected function getNULLData($name, $default)
    {
        return $default;
    }

    /**
     * If the configured data is any kind of object, this handler will
     * be called when retrieving properties. The handler looks for a
     * property or getter method on the data that matches $name.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    protected function getObjectData($name, $default)
    {
        if(!is_object($this->data))
            throw new Exception('Presenter: Attempted to getObjectData() but $presenter->data is not an object.');
        $methodName = "get" . ucfirst($name);
        if(method_exists($this->data, $methodName))
            return $this->data->$methodName();
        if(property_exists($this->data, $name))
            return $this->data->$name;
        return $default;
    }

    /**
     * If the configured data is an array, this handler will be called
     * when retrieving properties. The handler looks for a key on the
     * array matching $name.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    protected function getArrayData($name, $default)
    {
        if(!is_array($this->data))
            throw new Exception('Presenter: Attempted to getArrayData() but $presenter->data is not an array.');
        if(array_key_exists($name, $this->data))
            return $this->data[$name];
        return $default;
    }

    /**
     * If a property is overriden by a method on this presenter, return it.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    protected function getLocalData($name, $default)
    {
        $methodName = "get" . ucfirst($name);
        if(method_exists($this, $methodName))
            return $this->$methodName();
        return $default;
    }

}
