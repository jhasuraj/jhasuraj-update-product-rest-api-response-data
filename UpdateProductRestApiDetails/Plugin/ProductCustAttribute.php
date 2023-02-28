<?php
namespace Suraj\UpdateProductRestApiDetails\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product as ProductModel;

class ProductCustAttribute
{
    public function afterGetList(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Catalog\Api\Data\ProductSearchResultsInterface $searchCriteria
    ) : \Magento\Catalog\Api\Data\ProductSearchResultsInterface
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $products = [];
        $custom_arr = [];

        foreach ($searchCriteria->getItems() as $key => $entity) {

            $imageUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            $attributes_list = $entity->getAttributes();
 
            foreach ($attributes_list as $attribute) {
                
                if ($attribute->getAttributeCode() == "image") {
                    $custom_arr[$attribute->getAttributeCode()] = $imageUrl.'catalog/product'.$entity->getImage();
                }elseif ($attribute->getAttributeCode() == "swatch_image") {
                    $custom_arr[$attribute->getAttributeCode()] = $imageUrl.'catalog/product'.$entity->getSmallImage();
                }elseif ($attribute->getAttributeCode() == "thumbnail") {
                    $custom_arr[$attribute->getAttributeCode()] = $imageUrl.'catalog/product'.$entity->getThumbnail();
                }else{
                    $custom_arr[$attribute->getAttributeCode()] = $entity->getData($attribute->getAttributeCode());
                }
            }

            $products[] = $custom_arr;
        }

        $searchCriteria->setItems($products);
        return $searchCriteria;
    }

}