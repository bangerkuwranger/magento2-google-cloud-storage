<?php
namespace Google\Cloud\Model\MediaStorage\Config\Source\Storage\Media\Storage;

class Plugin
{
    public function afterToOptionArray($subject, $result)
    {
        $result[] = [
            'value' => \Google\Cloud\Model\MediaStorage\File\Storage::STORAGE_MEDIA_GCS,
            'label' => __('Google Cloud Storage')
        ];
        return $result;
    }
}
