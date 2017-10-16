<?php
namespace Kernel\Tools;

class Alert
{
    private $_code;

    /**
     * Alert constructor.
     * $type SUC : success
     * $type ERR : error
     * $type WAIT : wait
     * @param $code
     * @param $type
     */
    public function __construct($code, $type)
    {
        if ($type === 'suc') { $this->_code = "suc_$code"; }
        elseif ($type === 'err') { $this->_code = "err_$code"; }
        elseif ($type === 'warn') { $this->_code = "warn_$code"; }
    }

    /**
     * Create a new session with alert code
     */
    public function setAlert()
    {
        $_SESSION['alerts'][$this->_code] = true;
    }

    /**
     * Remove the session
     */
    public function remove()
    {
        unset($_SESSION['alerts'][$this->_code]);
    }
}