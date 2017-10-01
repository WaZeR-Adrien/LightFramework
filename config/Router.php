<?php
namespace Project\Config;

class Router
{
    private $_branch; // target branch
    private $_page; // target page
    private $_twig; // twig object

    public function __construct($branch,$page,$twig)
    {
        $this->_branch = $branch;
        $this->_page = $page;
        $this->_twig = $twig;
    }

    /**
     * @return callable
     */
    public function getController()
    {
        if (is_null($this->_branch)) {
            return call_user_func(array('\Project\Controllers\\' . ucfirst($this->_page), 'index'), $this->_twig);
        } else {
            return call_user_func(array('\Project\Controllers\\' . ucfirst($this->_branch) . '\\' . ucfirst($this->_page), 'index'), $this->_twig);
        }
    }

    public function run()
    {
        echo $this->getController();
    }
}