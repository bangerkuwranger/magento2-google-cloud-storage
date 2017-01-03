<?php
namespace Google\Cloud\Model\Config\Source;

class Region implements \Magento\Framework\Option\ArrayInterface
{
    private $helper;

    public function __construct(\Google\Cloud\Helper\Gcs $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Return list of available Google Cloud Storage regions
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->helper->getRegions();
    }
}
