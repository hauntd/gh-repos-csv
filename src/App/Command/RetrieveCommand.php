<?php
namespace App\Command;

use App\Components\GitHubAPI;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use League\Csv\Writer;

/**
 * @author Alexander Kononenko <contact@hauntd.me>
 * @package App\Command
 */
class RetrieveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('retrieve')
            ->setDescription('Retrieves GitHub user\'s repositories and saves to CSV file')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'GitHub username')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = new GitHubAPI();
        $data = [];

        foreach ($api->getRepositoriesFor($input->getOption('username')) as $repository) {
            $data[] = [$repository['full_name'], $repository['stargazers_count'], $repository['watchers_count']];
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Repository name', 'Stars', 'Watchers']);
        $csv->insertAll($data);

        $output->write($csv->output());

        return 0;
    }
}
