<?php
namespace Google\Cloud\Console\Command;

use Magento\Config\Model\Config\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigSetCommand extends \Symfony\Component\Console\Command\Command
{
    private $configFactory;

    private $state;

    private $helper;

    public function __construct(
        \Magento\Framework\App\State $state,
        Factory $configFactory,
        \Google\Cloud\Helper\Gcs $helper
    ) {
        $this->state = $state;
        $this->helper = $helper;
        $this->configFactory = $configFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('gcs:config:set')
            ->setDescription('Allows you to set your GCS configuration via the CLI.')
            ->setDefinition($this->getOptionsList());
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('region') && !$input->getOption('bucket') && !$input->getOption('project') && !$input->getOption('access_key')) {
            $output->writeln($this->getSynopsis());
            return;
        }

        $errors = $this->validate($input);
        if ($errors) {
            $output->writeln('<error>' . implode('</error>' . PHP_EOL .  '<error>', $errors) . '</error>');
            return;
        }

        $this->state->setAreaCode('adminhtml');
        $config = $this->configFactory->create();

        if (!empty($input->getOption('access_key'))) {
            $config->setDataByPath('google_cloud/general/access_key', $input->getOption('access_key'));
            $config->save();
        }

        if (!empty($input->getOption('project'))) {
            $config->setDataByPath('google_cloud/general/project', $input->getOption('project'));
            $config->save();
        }

        if (!empty($input->getOption('bucket'))) {
            $config->setDataByPath('google_cloud/general/bucket', $input->getOption('bucket'));
            $config->save();
        }

        if (!empty($input->getOption('region'))) {
            $config->setDataByPath('google_cloud/general/region', $input->getOption('region'));
            $config->save();
        }

        $output->writeln('<info>You have successfully updated your GCS credentials.</info>');
    }

    public function getOptionsList()
    {
        return [
            new InputOption('access_key', null, InputOption::VALUE_OPTIONAL, 'a valid GCS access JSON object'),
            new InputOption('project', null, InputOption::VALUE_OPTIONAL, 'a valid GCS project ID'),
            new InputOption('bucket', null, InputOption::VALUE_OPTIONAL, 'an GCS bucket name'),
            new InputOption('region', null, InputOption::VALUE_OPTIONAL, 'an GCD region, e.g. us-east-b')
        ];
    }

    public function validate(InputInterface $input)
    {
        $errors = [];
        if ($input->getOption('region')) {
            if (!$this->helper->isValidRegion($input->getOption('region'))) {
                $errors[] = sprintf('The region "%s" is invalid.', $input->getOption('region'));
            }
        }
        return $errors;
    }
}
