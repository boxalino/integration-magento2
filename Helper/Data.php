<?php

namespace Boxalino\Integration\Helper;

/**
 * Generic helper to access configurations and Boxalino plugin and SDK options
 */
class Data
{
    
    CONST BOXALINO_RESPONSE_SEO_PAGE_TITLE = "bx-page-title";

    CONST BOXALINO_RESPONSE_SEO_META_TITLE = "bx-html-meta-title";

    CONST BOXALINO_RESPONSE_SEO_META_DESCRIPTION = "bx-html-meta-description";

    CONST BOXALINO_RESPONSE_SEO_META_TAGS_DESCRIPTION = "bx-html-meta-tags-description";

    CONST BOXALINO_RESPONSE_SEO_BREADCRUMBS = "bx-seo-breadcrumbs";
    
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Boxalino\Intelligence\Helper\P13n\Adapter
     */
    protected $boxalinoP13nHelper;

    /**
     * @var \Boxalino\Intelligence\Helper\Data
     */
    protected $boxalinoHelper;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Boxalino\Intelligence\Helper\Data $boxalinoHelper,
        \Boxalino\Intelligence\Helper\P13n\Adapter $boxalinoP13nHelper
    ) {
        $this->boxalinoHelper = $boxalinoHelper;
        $this->scopeConfig = $scopeConfig;
        $this->boxalinoP13nHelper = $boxalinoP13nHelper;
    }

    /**
     * Check if Boxalino is enabled on the store view
     *
     * @return bool
     */
    public function isBoxalinoActive()
    {
        return $this->getBoxalinoHelper()->isPluginEnabled();
    }
    

    /**
     * @param $choice
     * @return mixed
     */
    public function getSeoMetaTitleByChoice($choice)
    {
        return $this->getStoreViewValueByKeyChoice(self::BOXALINO_RESPONSE_SEO_META_TITLE, $choice);
    }

    /**
     * @param $choice
     * @return mixed
     */
    public function getSeoPageTitleByChoice($choice)
    {
        return $this->getStoreViewValueByKeyChoice(self::BOXALINO_RESPONSE_SEO_PAGE_TITLE, $choice);
    }

    /**
     * @param $choice
     * @return mixed
     */
    public function getSeoMetaDescriptionByChoice($choice)
    {
        return $this->getStoreViewValueByKeyChoice(self::BOXALINO_RESPONSE_SEO_META_DESCRIPTION, $choice);
    }

    /**
     * @param $choice
     * @return mixed
     */
    public function getSeoMetaTagsDescriptionByChoice($choice)
    {
        return $this->getStoreViewValueByKeyChoice(self::BOXALINO_RESPONSE_SEO_META_TAGS_DESCRIPTION, $choice);
    }

    /**
     * @param $choice
     * @return mixed
     */
    public function getSeoBreadcrumbsByChoice($choice)
    {
        return $this->getStoreViewValueByKeyChoice(self::BOXALINO_RESPONSE_SEO_BREADCRUMBS, $choice);
    }

    /**
     * Accesses the P13nHelper from Boxalino SDK and extracts the localized value by key
     * 
     * @param $key
     * @param null $choice
     * @param null $language
     * @param null $defaultExtraInfoValue
     * @param bool $prettyPrint
     * @return mixed
     */
    public function getStoreViewValueByKeyChoice($key, $choice=null, $language=null, $defaultExtraInfoValue = null, $prettyPrint=false) {
        return $this->getBoxalinoHelper()->getResponse()->getExtraInfoLocalizedValue($key, $language, $defaultExtraInfoValue, $prettyPrint, $choice);
    }

    /**
     * Accesses the P13nHelper from Boxalino SDK and extracts non-localized value by key
     * 
     * @param $key
     * @param null $choice
     * @param null $defaultExtraInfoValue
     * @return mixed
     */
    public function getValueByKeyChoice($key, $choice=null, $defaultExtraInfoValue = null) {
        return $this->getBoxalinoHelper()->getResponse()->getExtraInfo($key, $defaultExtraInfoValue, $choice);
    }
    
    /**
     * @return \Boxalino\Intelligence\Helper\P13n\Adapter
     */
    public function getBoxalinoHelper()
    {
        return $this->boxalinoP13nHelper;
    }

}