<?php
/**
 * @author      SWENSON HE
 * @copyright   Copyright © SWENSON HE (https://www.swensonhe.com)
 */

namespace SwensonHe\DownloadableIsVisible\Ui\DataProvider\Product\Form\Modifier\Links;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\Composite;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form;

class Visible extends AbstractModifier
{
    const VISIBLE_FLAG_PATH = Composite::CHILDREN_PATH . '/' . Composite::CONTAINER_LINKS . "/children/link/children/record/children";
    const VISIBLE_FLAG_YES = 1;
    const VISIBLE_FLAG_NO = 0;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Adds is_visible field UI configuration to page meta.
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
       return $this->arrayManager->merge(
            self::VISIBLE_FLAG_PATH,
            $meta,
            ['is_visible' => $this->getIsVisibleColumn()]
        );
    }

    /**
     * Returns is_visible field UI configuration.
     *
     * @return array
     */
    private function getIsVisibleColumn(){
        $isVisibleField['arguments']['data']['config'] = [
            'label' => __('Is Visible'),
            'formElement' => Form\Element\Select::NAME,
            'componentType' => Form\Field::NAME,
            'dataType' => Form\Element\DataType\Number::NAME,
            'dataScope' => 'is_visible',
            'sortOrder' => 55,
            'options' => $this->isVisibleOptions(),
        ];

        return $isVisibleField;
    }

    /**
     * Returns is_visible parameter options.
     *
     * @return array
     */
    public function isVisibleOptions()
    {
        return [
            ['value' => self::VISIBLE_FLAG_YES, 'label' => __('Yes')],
            ['value' => self::VISIBLE_FLAG_NO, 'label' => __('No')]
        ];
    }
}
