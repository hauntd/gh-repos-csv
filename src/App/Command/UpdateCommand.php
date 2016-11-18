<?php
namespace App\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Herrera\Json\Exception\FileException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Alexander Kononenko <contact@hauntd.me>
 * @package App\Command
 */
class UpdateCommand extends Command
{
    const MANIFEST_FILE = 'http://hauntd.github.io/gh-repos-csv/manifest.json';

    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Updates gh-repos-csv.phar to the latest version')
            ->addOption('major', null, InputOption::VALUE_NONE, 'Allow major version update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Looking for updates...');
        try {
            $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        } catch (FileException $e) {
            $output->writeln('<error>Unable to search for updates</error>');
            return 1;
        }
        $currentVersion = $this->getApplication()->getVersion();
        $allowMajor = $input->getOption('major');
        if ($manager->update($currentVersion, $allowMajor)) {
            $output->writeln('<info>Updated to latest version</info>');
        } else {
            $output->writeln('<comment>Already up-to-date</comment>');
        }
    }
}
