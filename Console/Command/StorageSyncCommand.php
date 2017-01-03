<?php
namespace Google\Cloud\Console\Command;

use Magento\Config\Model\Config\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StorageSyncCommand extends \Symfony\Component\Console\Command\Command
{
    private $configFactory;

    private $state;

    private $helper;

    private $client;

    private $coreFileStorage;

    private $storageHelper;

    public function __construct(
        \Magento\Framework\App\State $state,
        Factory $configFactory,
        \Magento\MediaStorage\Helper\File\Storage\Database $storageHelper,
        \Magento\MediaStorage\Helper\File\Storage $coreFileStorage,
        \Google\Cloud\Helper\Data $helper
    ) {
        $this->state = $state;
        $this->configFactory = $configFactory;
        $this->coreFileStorage = $coreFileStorage;
        $this->helper = $helper;
        $this->storageHelper = $storageHelper;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('gcs:storage:sync')
            ->setDescription('Sync all of your media files over to GCS.')
            ->setDefinition($this->getOptionsList());
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $errors = $this->validate($input);
        if ($errors) {
            $output->writeln('<error>' . implode('</error>' . PHP_EOL .  '<error>', $errors) . '</error>');
            return;
        }

        try {
        	$json_key = $this->helper->getAccessKey();
        	$key_array = json_decode($json_key,true);
        	$project = $key_array['project_id'];
        	$this->client = new \cAc\GcsWrapper\GoogleCloudStorage(
				$project,
        		$json_key,
        		$this->helper->getBucket()
        	);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return;
        }
        if (!$this->client->bucket_exists()) {
            $output->writeln('<error>The GCS credentials you provided did not work. Please review your details and try again. You can do so using our config script.</error>');
            return;
        }
        /*if ($this->coreFileStorage->getCurrentStorageCode() == \Google\Cloud\Model\MediaStorage\File\Storage::STORAGE_MEDIA_GCS) {
            $output->writeln('<error>You are already using GCS as your media file storage backend!</error>');
            return;
        }*/

        $output->writeln(sprintf('Uploading files to use GCS.'));
        if ($this->coreFileStorage->getCurrentStorageCode() == \Google\Cloud\Model\MediaStorage\File\Storage::STORAGE_MEDIA_FILE_SYSTEM) {
        
            try {
            
                $this->client->bucket_upload_directory(
                    $this->storageHelper->getMediaBaseDir(),
                    false,
                    "publicRead"
                );
           
            } 
            catch (\Exception $e) {
            
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            
            }
        
        } 
        else {
        
            $sourceModel = $this->coreFileStorage->getStorageModel();
            $destinationModel = $this->coreFileStorage->getStorageModel(\Google\Cloud\Model\MediaStorage\File\Storage::STORAGE_MEDIA_GCS);
            $offset = 0;
            while (($files = $sourceModel->exportFiles($offset, 1)) !== false) {
            
                foreach ($files as $file) {
                
                    $output->writeln(sprintf('Uploading %s to use GCS.', $file['directory'] . '/' . $file['filename']));
               
                }
                $destinationModel->importFiles($files);
                $offset += count($files);
            
            }
        
        }
        $output->writeln(sprintf('Finished uploading files to use GCS.'));

        if ($input->getOption('enable')) {
            $output->writeln('Updating configuration to use GCS.');
            $this->state->setAreaCode('adminhtml');
            $config = $this->configFactory->create();
            $config->setDataByPath('system/media_storage_configuration/media_storage', \Google\Cloud\Model\MediaStorage\File\Storage::STORAGE_MEDIA_GCS);
            $config->save();
            $output->writeln(sprintf('<info>Magento now uses GCS for its file backend storage.</info>'));
        }
    }

    public function getOptionsList()
    {
        return [
            new InputOption('enable', null, InputOption::VALUE_NONE, 'use GCS as Magento file storage backend'),
        ];
    }

    public function validate(InputInterface $input)
    {
        $errors = [];

        if (is_null($this->helper->getAccessKey())) {
            $errors[] = 'You have not provided an GCS access key ID. You can do so using our config script.';
        }
        if (is_null($this->helper->getProject())) {
             $errors[] = 'You have not provided an GCS Project Number. You can do so using our config script.';
         }
        if (is_null($this->helper->getBucket())) {
            $errors[] = 'You have not provided an GCS bucket. You can do so using our config script.';
        }
        if (is_null($this->helper->getRegion())) {
            $errors[] = 'You have not provided an GCS region. You can do so using our config script.';
        }

        return $errors;
    }
}
