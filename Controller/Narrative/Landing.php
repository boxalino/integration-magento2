<?php

namespace Boxalino\Integration\Controller\Narrative;

/**
 * Narrative use-case #3
 * 
 * Sample controller to load the narrative view attached to a certain choice (ex:landing page)
 */
class Landing extends \Magento\Framework\App\Action\Action
{

    /**
     * the Boxalino choice ID to use for the requests
     */
    const BOXALINO_CHOICE_ID = 'narrative_landing';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Boxalino\Integration\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Boxalino\Integration\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Boxalino\Integration\Helper\Data $helper
    ){
        parent::__construct($context);
        
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
    }


    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        if (!$this->helper->isBoxalinoActive())
        {
            return $resultPage;
        }

        // set page title
        $resultPage->getConfig()->getTitle()->set(
            $this->helper->getSeoPageTitleByChoice(self::BOXALINO_CHOICE_ID)
                ?: __('Our product selection')
        );


        // set page heading
        $pageTitle = $this->helper->getSeoMetaTitleByChoice(self::BOXALINO_CHOICE_ID)
            ?: __("Our product selection");
        $resultPage->getLayout()->getBlock('page.main.title')->setPageTitle($pageTitle);


        // meta description
        $resultPage->getConfig()->setDescription(
            $this->helper->getSeoMetaDescriptionByChoice(self::BOXALINO_CHOICE_ID)
                ?: $this->helper->getSeoMetaTagsDescriptionByChoice(self::BOXALINO_CHOICE_ID)
                ?: __('Our products selected personally for you')
        );

        // set breadcrumbs
        /* @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $this->_url->getUrl('')
            ]
        );

        // insert intermediate breacrumbs if any
        // the configured JSON per store-view look like [{"label":"Marken","link":"/brands/"}]
        $intermediateBreacrumbsJson = $this->helper->getSeoBreadcrumbsByChoice(self::BOXALINO_CHOICE_ID);
        if (($intermediateBreacrumbsData = json_decode($intermediateBreacrumbsJson, true)) &&
            is_array($intermediateBreacrumbsData))
        {
            foreach ($intermediateBreacrumbsData as $breadcrumbData)
            {
                if (isset($breadcrumbData['label']) && isset($breadcrumbData['link']))
                {
                    $label = $breadcrumbData['label'];
                    $breadcrumbs->addCrumb($label,
                        [
                            'label' => $label,
                            'title' => $label,
                            'link' => $breadcrumbData['link']
                        ]
                    );
                }
            }
        }

        $breadcrumbs->addCrumb('boxalinointegration',
            [
                'label' => __($pageTitle),
                'title' => __($pageTitle),
            ]
        );

        return $resultPage;
    }

}