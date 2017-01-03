<?php
namespace Google\Cloud\Helper;

class Gcs
{
    /**
     * Determines whether an GCS region code is valid.
     *
     * @param string $regionInQuestion
     * @return bool
     */
    public function isValidRegion($regionInQuestion)
    {
        foreach ($this->getRegions() as $currentRegion) {
            if ($currentRegion['value'] == $regionInQuestion) {
                return true;
            }
        }
        return false;
    }

    public function getRegions()
    {
        return [
            [
                'value' => 'asia',
                'label' => 'Asia Pacific (multi-region)'
            ],
            [
                'value' => 'eu',
                'label' => 'European Union (multi-region)'
            ],
            [
                'value' => 'us',
                'label' => 'United States (multi-region)'
            ],
            [
                'value' => 'asia-east1',
                'label' => 'Eastern Asia-Pacific'
            ],
            [
                'value' => 'europe-west1',
                'label' => 'Western Europe'
            ],
            [
                'value' => 'us-central1',
                'label' => 'Central United States'
            ],
            [
                'value' => 'us-east1',
                'label' => 'EasternUnited States'
            ],
            [
                'value' => 'us-west1',
                'label' => 'Western United States'
            ],
            
        ];
    }
}
