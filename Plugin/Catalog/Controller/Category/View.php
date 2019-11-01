<?php

namespace Boxalino\Integration\Plugin\Catalog\Controller\Category;


/**
 * Narrative use-case #2
 * 
 * Plugin to set narrative meta title, description and other extra-info properties 
 * existing or manually added (key-value) in Boxalino Intelligence Admin
 * on category view results page
 */
class View
{

    /**
     * the Boxalino choice ID to used for the requests
     * must match the one used in the XML integration
     */
    const BOXALINO_CHOICE_ID = 'narrative_category';

    /**
     * @var \Boxalino\Integration\Helper\Data
     */
    protected $helper;

    /**
     * @param \Boxalino\Integration\Helper\Data $helper
     */
    public function __construct(
        \Boxalino\Integration\Helper\Data $helper
    ){
        $this->helper = $helper;
    }

    /**
     * @see \Magento\Catalog\Controller\Category\View::execute()
     *
     * @param \Magento\Catalog\Model\Category $subject
     * @param \Magento\Framework\Controller\ResultInterface $result
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function afterExecute(
        \Magento\Catalog\Controller\Category\View $subject,
        \Magento\Framework\Controller\ResultInterface $result
    ){
        if ($result instanceOf \Magento\Framework\View\Result\Page && $this->helper->isBoxalinoActive())
        {
            // page title
            $metaTitle = $this->helper->getSeoMetaTitleByChoice(self::BOXALINO_CHOICE_ID);
            if ($metaTitle)
                $result->getConfig()->getTitle()->set($metaTitle);

            // meta (tags) description
            $metaDescription = $this->helper->getSeoMetaDescriptionByChoice(self::BOXALINO_CHOICE_ID) 
                ?: $this->helper->getSeoMetaTagsDescriptionByChoice(self::BOXALINO_CHOICE_ID);
            if ($metaDescription)
                $result->getConfig()->setDescription($metaDescription);
        }

        return $result;
    }

}