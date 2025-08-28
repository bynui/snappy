<?php

namespace Core\Error;

class ErrorWrapper extends \Exception{
    protected $rawCode;

    public function __construct(\Throwable | string $input, int $code = 0){
        if ($input instanceof \Throwable) {
            $msg = $input->getMessage();
            $rawCode = $input->getCode();
            $code = is_numeric($rawCode) ? (int)$rawCode : 0;
            parent::__construct($msg, $code, $input);
            $this->rawCode = $rawCode;
        } else {
            parent::__construct((string) $input, $code);
            $this->rawCode = $code;
        }
    }

    public function getOriginal(): \Throwable{
        return $this->getPrevious() ?? $this;
    }

    public function getRawCode(){
        return $this->rawCode;
    }
}

?>