<?php

namespace Boxalino\Integration\Controller;

/**
 * Narrative use-case #3
 * 
 * Custom router for the Boxalino Narrative dynamically rendered pages
 * Manages the default match logic
 *
 * ex: router that matches a request path like <store-url>/self::BOXALINO_INTEGRATION_SEGMENT_LANDING/<campaign-parameter-value>
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
        if(in_array(self::BOXALINO_NARRATIVE_REQUEST_PARAMETER, array_keys($this->request->getParams())))
        {
            return false;
        }

        $path = $this->_requestPath;
        if (count($path) != 2) {
            return false;
        }

        if (empty($path[0]) || $path[0] != self::BOXALINO_INTEGRATION_SEGMENT_LANDING || empty($path[1])) {
            return false;
        }

        return true;
    }

    /**
     * Return the params that has to be set on the request
     * "campaign" parameter is dynamic per your integration use-case
     * 
     * @return []
     */
    protected function getParams()
    {
        return [self::BOXALINO_NARRATIVE_REQUEST_PARAMETER =>$this->_requestPath[1]];
    }
    
}