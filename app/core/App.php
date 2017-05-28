<?php

/**
 * The App class sets up the application and the bootstrap.
 *
 * @class App  
 */
class App
{

    /**
     * This is the property that grabs the first part 
     * of the url after the / and tells us what controller to use. 
     *
     * @property controller
     * @type String
     */
    protected $controller = 'home';

    /**
     * This is the property that grabs the second part of the url after the "controller"
     * It tells us which function to use inside of the controller.
     *
     * @property method
     * @type String
     */
    protected $method = 'index';

    // 
    protected $params = [];

    /**
     * @constructor
     */
    public function __construct()
    {
        $url = $this->parseUrl();

        // Check if controller exists.
        if(file_exists('../app/controllers/'.$url[0].'.php'))
        {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once('../app/controllers/'.$this->controller.'.php');

        $this->controller = new $this->controller;

        // Check if method exists.
        if(isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // If params, set params, else set empty.
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Explode and trim the sanitized url. It allows access to the different parts of the url.
    public function parseUrl()
    {
        if(isset($_GET['url']))
        {
            return $url = explode('/',filter_var(rtrim($_GET['url'], ''), FILTER_SANITIZE_URL));
        }
    }

}