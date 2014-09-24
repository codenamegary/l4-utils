<?php

namespace codenamegary\L4Utils;

class ObjectPresenter {

    /**
     * @var object
     */
    protected $object;

    /**
     * @param mixed $object     Any type of object you wish to present.
     */
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    /**
     * @param mixed $object     Any type of object you wish to present.
     * @return codenamegary\L4Utils\ObjectPresenter
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $methodName = "get" . ucfirst($name);
        if(method_exists($this, $methodName))
            return $this->$methodName();
        if(method_exists($this->object, $methodName))
            return $this->object->$methodName();
        if(property_exists($this->object, $name))
            return $this->object->$name;
        return $default;
    }

}
