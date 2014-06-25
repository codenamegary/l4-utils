<?php

namespace codenamegary\L4Utils;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Validator;

abstract class Validator implements MessageProviderInterface
{

    /**
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * @var integer
     */
    protected $modelId;

    /**
     * @var array
     */
    protected $input;
    
    /**
     * @param $modelId  integer
     */
    public function __construct($modelId = null)
    {
        $this->modelId = $modelId;
        $this->errors = new MessageBag;
    }

    /**
     * @return array
     */
    abstract public function getRules();

    /**
     * @param $input    array
     */
    public function setInput($input = null)
    {
        $this->input = $input;
    }

    /**
     * @return array
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Overwrite this function to return custom messages you want the validator to use.
     * 
     *     Reference: http://laravel.com/docs/validation#custom-error-messages
     * 
     * @return array
     */
    public function getMessages()
    {
        return array();
    }
        
    /**
     * Run the validator, return true or false for success / fail.
     * 
     * @return boolean
     */
    public function validates()
    {
        $this->errors = new MessageBag;
        $validation = Validator::make($this->getInput(), $this->getRules(), $this->getMessages());
        if ($validation->passes())
            return true;
        $this->errors = $validation->messages();
        return false;
    }
    
    /**
     * Runs the validator, returns true or false for fail / success.
     * 
     * @return boolean
     */
    public function fails()
    {
        return !$this->validates();
    }
    
    /**
     * Just executes the validator so that you can check the validation output manually.
     */
    public function run()
    {
        $this->validates();
    }

    /**
     * @return Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Simple utility function that returns $output if $this->exists
     * or $default if not.
     */
    public function ifExists($output, $default = '')
    {
        return $this->modelId !== null ? $output : $default;
    }

    /**
     * @param $arg integer
     */
    public function setModelId($arg = null)
    {
        $this->modelId = $arg;
    }
    
    /**
     * @return integer
     */
    public function getModelId()
    {
        return $this->modelId;
    }

}