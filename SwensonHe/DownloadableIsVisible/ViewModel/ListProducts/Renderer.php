<?php
/**
 * @author      SWENSON HE
 * @copyright   Copyright © SWENSON HE (https://www.swensonhe.com)
 */

namespace SwensonHe\DownloadableIsVisible\ViewModel\ListProducts;

use Magento\Downloadable\Model\ResourceModel\Link\CollectionFactory;
use Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/** Class Renderer
 *  ViewModel used for adding is_visible parameter to links on frontend.
 */
class Renderer extends DataObject implements ArgumentInterface
{
    /**
     * @var CollectionFactory
     */
    private $linkCollectionFactory;

    /**
     * @param CollectionFactory $linkCollectionFactory
     */
    public function __construct(
        CollectionFactory $linkCollectionFactory
    )
    {
        $this->linkCollectionFactory = $linkCollectionFactory;
        parent::__construct();
    }

    /**
     * Adds visibility parameter to each row in products collection.
     *
     * @param Collection $collection
     * @return Collection
     */
    public function addIsVisible(Collection $collection)
    {
        $ids = array_map(function ($item){
            return $item->getLinkId();
        }, $collection->getItems());
        $linkCollection = $this->linkCollectionFactory->create();
        $linkCollection->addFieldToSelect(['is_visible']);
        $linkCollection->addFieldToFilter('link_id', $ids);
        foreach ($collection->getItems() as &$item){
            $item->setData("is_visible", (int) $linkCollection->getItemById($item->getLinkId())->getIsVisible());
        }
        return $collection;
    }
}
