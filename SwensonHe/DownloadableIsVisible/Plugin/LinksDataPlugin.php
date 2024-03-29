<?php
/**
 * @author      SWENSON HE
 * @copyright   Copyright © SWENSON HE (https://www.swensonhe.com)
 */

namespace SwensonHe\DownloadableIsVisible\Plugin;

use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\Product\Type;
use Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\Data\Links;

/**
 * Class LinkDataPlugin
 * Used for retrieving is_visible flag value with other data on admin product edit page.
 */
class LinksDataPlugin extends Links
{
    /**
     * Adds is_visible flag value to links data for rendering on admin.
     *
     * @param Links $subject
     * @return array
     */
    public function aroundGetLinksData(Links $subject)
    {
        $linksData = [];
        if ($this->locator->getProduct()->getTypeId() !== Type::TYPE_DOWNLOADABLE) {
            return $linksData;
        }

        $links = $this->locator->getProduct()->getTypeInstance()->getLinks($this->locator->getProduct());
        /** @var LinkInterface $link */
        foreach ($links as $link) {
            $linkData = [];
            $linkData['link_id'] = $link->getId();
            $linkData['title'] = $this->escaper->escapeHtml($link->getTitle());
            $linkData['price'] = $this->getPriceValue($link->getPrice());
            $linkData['number_of_downloads'] = $link->getNumberOfDownloads();
            $linkData['is_shareable'] = $link->getIsShareable();
            $linkData['is_visible'] = $link->getIsVisible();
            $linkData['link_url'] = $link->getLinkUrl();
            $linkData['type'] = $link->getLinkType();
            $linkData['sample']['url'] = $link->getSampleUrl();
            $linkData['sample']['type'] = $link->getSampleType();
            $linkData['sort_order'] = $link->getSortOrder();
            $linkData['is_unlimited'] = $linkData['number_of_downloads'] ? '0' : '1';

            if ($this->locator->getProduct()->getStoreId()) {
                $linkData['use_default_price'] = $link->getWebsitePrice() ? '0' : '1';
                $linkData['use_default_title'] = $link->getStoreTitle() ? '0' : '1';
            }

            $linkData = $this->addLinkFile($linkData, $link);
            $linkData = $this->addSampleFile($linkData, $link);

            $linksData[] = $linkData;
        }

        return $linksData;
    }
}
