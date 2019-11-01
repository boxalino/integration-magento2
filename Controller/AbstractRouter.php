<?php

namespace Boxalino\Integration\Controller;

/**
 * Narrative use-case #3
 * 
 * Custom router for the Boxalino Narrative dynamically rendered pages
 * Manages the default match logic
 * Must be extended and completed with the desired logic for matching routes and actions
 */
abstract class AbstractRouter implements \Magento\Framework\App\RouterInterface
{

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var \Boxalino\Integration\Helper\Data
     */
    protected $helper;

    /**
     * Request path: from base url to query string
     *
     * @var null|string
     */
    protected $_requestPath = null;


    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Boxalino\Integration\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\ResponseInterface $response,
        \Boxalino\Integration\Helper\Data $helper
    ){
        $this->actionFactory = $actionFactory;
        $this->request = $request;
        $this->response = $response;
        $this->helper = $helper;
    }


    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->helper->isBoxalinoActive())
        {
            return $request;
        }

        // get the path form base to query string
        $path = trim($request->getPathInfo(), '/');
        $p = $p = explode('/', $path);

        $this->_requestPath = array_filter($p);

        // if path is empty, there's no need to continue
        if (empty($this->_requestPath) || false === $this->matchPath()) {
            return false;
        }

        // set params
        foreach ($this->getParams() as $key => $value) {
            $request->setParam($key, $value);
        }

        // main page
        $request->setModuleName('boxalinointegration')->setControllerName($this->getController())->setActionName($this->getAction());
        $request->setAlias(\Magento\Framework\UrlInterface::REWRITE_REQUEST_PATH_ALIAS, $identifier);
        
        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }

    /**
     * Return the params that has to be set on the request
     *
     * @return array
     */
    abstract protected function getParams();

    /**
     * Match the router path against the logic the url are being generated
     *
     * @param int|null $id
     * @return boolean
     */
    abstract protected function matchPath();
    
    
    /**
     * Implement abstract method that will return the controller name that
     * will be dispatched
     *
     * @return string
     */
    protected function getController()
    {
        $callingClass = get_called_class();

        return $callingClass::BOXALINO_INTEGRATION_ROUTER_CONTROLLER;
    }

    /**
     * Implement abstract method that will return the controller action that
     * will be dispatched.
     *
     * @return string
     */
    protected function getAction()
    {
        $callingClass = get_called_class();

        return $callingClass::BOXALINO_INTEGRATION_ROUTER_ACTION;
    }

}