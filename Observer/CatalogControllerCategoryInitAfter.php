<?php

namespace Boxalino\Integration\Observer;


/**
 * Narrative use-case #2
 * 
 * Observer triggered after initializing the category in the category view controller
 * If the category has a narrative/campaign property - set it as context to trigger the narrative match
 */
class CatalogControllerCategoryInitAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ){
        $this->request = $request;
    }

    /**
     * Sets the correct narrative parameter on category
     * 
     * "campaign" is the generic practice to name the parameter; it is customary per integration
     * !!!please contact us to confirm what is the context parameter key!!!
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Catalog\Model\Category $category */
        $category = $observer->getCategory();

        if ($boxalinoNarrativeCode = $category->getBoxalinoNarrativeParameter())
        {
            $requestParams = $this->request->getParams();
            $requestParams['campaign'] = $boxalinoNarrativeCode;
            $this->request->setParams($requestParams);
        }
    }

}