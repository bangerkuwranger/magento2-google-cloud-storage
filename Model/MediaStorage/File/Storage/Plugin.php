<?php
namespace Google\Cloud\Model\MediaStorage\File\Storage;

class Plugin
{
    private $coreFileStorage;
    private $gcsFactory;

    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage $coreFileStorage,
        GcsFactory $gcsFactory
    ) {
        $this->coreFileStorage = $coreFileStorage;
        $this->gcsFactory = $gcsFactory;
    }

    public function aroundGetStorageModel($subject, $proceed, $storage = null, array $params = [])
    {
        $storageModel = $proceed($storage, $params);
        if ($storageModel === false) {
            if (is_null($storage)) {
                $storage = $this->coreFileStorage->getCurrentStorageCode();
            }
            switch ($storage) {
                case \Google\Cloud\Model\MediaStorage\File\Storage::STORAGE_MEDIA_GCS:
                    $storageModel = $this->gcsFactory->create();
                    break;
                default:
                    return false;
            }

            if (isset($params['init']) && $params['init']) {
                $storageModel->init();
            }
        }

        return $storageModel;
    }
}
