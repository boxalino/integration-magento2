<?php

namespace Boxalino\Integration\Controller;

use Evozon_Blog_Model_Config_Post;

/**
 * Narrative use-case #3
 * 
 * Custom router for the Boxalino Narrative dynamically rendered pages
 * Manages the default match logic
 */
class Landing extends \Boxalino\Integration\Controller\AbstractRouter
{

    const BOXALINO_INTEGRATION_ROUTER_CONTROLLER = "narrative";

    const BOXALINO_INTEGRATION_ROUTER_ACTION = "landing";
    
    const BOXALINO_INTEGRATION_SEGMENT_LANDING = "campaign";

    /**
     * Match the router path against the logic the landing pages url are being generated
     *
     * @param int $id | null
     * @return boolean
     */
    protected function matchPath()
    {
        $path = $this->_requestPath;
        if (count($path) < 2) {
            return false;
        }

        if (empty($path[0]) || $path[0] != self::BOXALINO_INTEGRATION_SEGMENT_LANDING) {
            return false;
        }

        return true;
    }

    /**
     * Return the params that has to be set on the request
     *
     * @return []
     */
    protected function getParams()
    {
        return ['campaign'=>$this->_requestPath[1]];
    }
    
}