<?php


namespace AsaanShopping\SearchByLocation\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Entity\TypeFactory $eavTypeFactory,
        \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory,
        \Magento\Eav\Model\AttributeSetManagement $attributeSetManagement,
        \Magento\Eav\Model\AttributeManagement $attributeManagement
)
    {
        $this->eavTypeFactory = $eavTypeFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->attributeManagement  =   $attributeManagement;
        $this->eavSetupFactory = $eavSetupFactory;
    }


    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'latitude',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Latitude',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Addresses',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'longitude',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Longitude',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Addresses',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'street',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Street',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Addresses',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'city',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'City',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Addresses',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'country',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Country',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Addresses',
                'option' => array('values' => array(""))
            ]
        );

        $this->createAttributeSets();

    }

    public function CreateAttribute(){

    }

    public function createAttributeSets() {
        $entityTypeCode = 'catalog_product';
        $entityType     = $this->eavTypeFactory->create()->loadByCode($entityTypeCode);
        $defaultSetId   = $entityType->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $data = [
            'attribute_set_name'    => 'Addresses',
            'entity_type_id'        => $entityType->getId(),
            'sort_order'            => 200,
        ];
        $attributeSet->setData($data);

        $this->attributeSetManagement->create($entityTypeCode, $attributeSet, $defaultSetId);

        $this->attributeManagement->assign(
            'catalog_product',
            $attributeSet->getId(),
            $attributeSet->getDefaultGroupId(),
            'country','city','street','longitude','latitude',
            $attributeSet->getCollection()->count() * 10
        );
    }
}
