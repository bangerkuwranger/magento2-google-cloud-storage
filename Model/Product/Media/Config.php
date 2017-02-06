<?php
namespace Google\Cloud\Model\Product\Media;
class Config extends \Magento\Catalog\Model\Product\Media\Config
{
    public function getBaseTmpMediaUrl()
    {
		$url = $this->storeManager->getStore()->getBaseUrl().\Magento\Framework\UrlInterface::URL_TYPE_MEDIA;
		return $url. '/tmp/' . $this->getBaseMediaUrlAddition();
		
    }

}
