<?php
namespace Google\Cloud\Model\MediaStorage\File\Storage\Directory\Database;

class Plugin
{
    private $helper;

    private $storageModel;

    public function __construct(
        \Google\Cloud\Helper\Data $helper,
        \Google\Cloud\Model\MediaStorage\File\Storage\Gcs $storageModel
    ) {
        $this->helper = $helper;
        $this->storageModel = $storageModel;
    }

    public function aroundCreateRecursive($subject, $proceed, $path)
    {
        if ($this->helper->checkGCSUsage()) {
            return $this;
        }
        return $proceed($path);
    }

    public function aroundGetSubdirectories($subject, $proceed, $directory)
    {
        if ($this->helper->checkGCSUsage()) {
            return $this->storageModel->getSubdirectories($directory);
        } else {
            return $proceed($directory);
        }
    }

    public function aroundDeleteDirectory($subject, $proceed, $path)
    {
        if ($this->helper->checkGCSUsage()) {
            return $this->storageModel->deleteDirectory($path);
        } else {
            return $proceed($path);
        }
    }
}
