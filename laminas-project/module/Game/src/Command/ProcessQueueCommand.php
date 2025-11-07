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
    private $missionQueueService;

    public function __construct(
        QueueService $constructionQueueService,
        QueueService $trainingQueueService,
        QueueService $missionQueueService
    ) {
        parent::__construct();
        $this->constructionQueueService = $constructionQueueService;
        $this->trainingQueueService = $trainingQueueService;
        $this->missionQueueService = $missionQueueService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Processing construction queue...');
        $this->constructionQueueService->processQueue();
        $output->writeln('Construction queue processed.');

        $output->writeln('Processing training queue...');
        $this->trainingQueueService->processQueue();
        $output->writeln('Training queue processed.');

        $output->writeln('Processing mission queue...');
        $this->missionQueueService->processQueue();
        $output->writeln('Mission queue processed.');

        return Command::SUCCESS;
    }
}
