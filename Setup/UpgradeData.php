<?php

namespace Boxalino\Integration\Setup;


/**
 * Narrative use-case #2
 * 
 * Adding a custom parameter on category to set context parameter value
 */
class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory
     */
    protected $attributeFactory;

    /**
     * UpgradeData constructor.
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory $attributeFactory
    ){
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @throws \Exception
     */
    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup, 
        \Magento\Framework\Setup\ModuleContextInterface $context
    ){
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0)
        {
            /* @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $defaultStoreId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
            $attributeCode = 'boxalino_narrative_parameter';

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                $attributeCode,
                [
                    'type' => 'varchar',
                    'input' => 'text',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'apply_to' => '',
                    'sort_order' => 0,
                    'visible_on_front' => 0,
                    'required' => 0,
                    'searchable' => 0,
                    'comparable' => 0,
                    'used_in_product_listing' => 0,
                    'used_for_sort_by' => 0,
                    'unique' => 0,
                    'is_used_in_grid' => 0,
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 0,
                    'visible_in_advanced_search' => 0,
                    'filterable' => 0,
                    'filterable_in_search' => 0,
                    'used_for_promo_rules' => 0,
                    'is_html_allowed_on_front' => 0,
                    'user_defined' => 0
                ]
            );

            /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
            $attribute = $this->attributeFactory->create()->loadByCode(\Magento\Catalog\Model\Category::ENTITY, $attributeCode);

            $attribute->setDefaultFrontendLabel([
                $defaultStoreId => 'Narrative Context',
            ])->save();

            $existingAttributeSetIds = $eavSetup->getAllAttributeSetIds(\Magento\Catalog\Model\Category::ENTITY);
            foreach ($existingAttributeSetIds as $attributeSetId)
            {
                $eavSetup->addAttributeGroup(\Magento\Catalog\Model\Category::ENTITY, $attributeSetId, 'Boxalino Narrative');

                $eavSetup->addAttributeToGroup(
                    \Magento\Catalog\Model\Category::ENTITY,
                    $attributeSetId,
                    'Boxalino Narrative',
                    $attributeCode
                );
            }
        }

        $setup->endSetup();
    }

}