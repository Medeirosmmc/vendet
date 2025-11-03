<?php
namespace Game\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Game\Service\QueueService;

class ProcessQueueCommand extends Command
{
    protected static $defaultName = 'game:process-queue';
    private $constructionQueueService;
    private $trainingQueueService;

    public function __construct(QueueService $constructionQueueService, QueueService $trainingQueueService)
    {
        parent::__construct();
        $this->constructionQueueService = $constructionQueueService;
        $this->trainingQueueService = $trainingQueueService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Processing construction queue...');
        $this->constructionQueueService->processQueue();
        $output->writeln('Construction queue processed.');

        $output->writeln('Processing training queue...');
        $this->trainingQueueService->processQueue();
        $output->writeln('Training queue processed.');

        return Command::SUCCESS;
    }
}
