<?php
class MyException extends Exception{
    public $type='';
    public $my_code='';
    // Переопределим исключение так, что параметр message станет обязательным
    public function __construct($message, $code = 0, Exception $previous = null) {
        $this->my_code = $code;
        // некоторый код
        // убедитесь, что все передаваемые параметры верны
        $code = 0;
        parent::__construct($message, $code, $previous);
    }
    /*
    // Переопределим строковое представление объекта.
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
    */
    public function getType() {
        return $this->type;
    }
    public function getMyCode() {
        return $this->my_code;
    }
}