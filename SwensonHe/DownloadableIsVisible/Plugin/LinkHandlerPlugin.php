<?php
/**
 * @author      SWENSON HE
 * @copyright   Copyright © SWENSON HE (https://www.swensonhe.com)
 */

namespace SwensonHe\DownloadableIsVisible\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Downloadable\Model\Product\TypeHandler\Link;
use Magento\Framework\App\RequestInterface;

/**
 * Class LinkHandlerPlugin.
 * Used for saving changes on admin product edit page.
 */
class LinkHandlerPlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Adds is_visible parameter to save function.
     *
     * @param Link $subject
     * @param Product $product
     * @param array $data
     * @return array
     */
    public function beforeSave(
        Link $subject,
        Product $product,
        array $data
    ) {
        $requestParams = $this->request->getParams();

        if (isset($data['link']) && isset($requestParams['downloadable']['link'])) {
            $links = $requestParams['downloadable']['link'];

            foreach ($links as $link) {
                if(array_key_exists('link_id',$link)){
                    $visibleLinks[$link['link_id']] = $link['is_visible'];
                }
            }

            if(isset($visibleLinks)){
                foreach ($data['link'] as $key => $itemData) {
                    if (isset($visibleLinks[$itemData['link_id']])) {
                        $data['link'][$key]['is_visible'] = $visibleLinks[$itemData['link_id']];
                    }
                }
            }
        }

        return [$product, $data];
    }
}
