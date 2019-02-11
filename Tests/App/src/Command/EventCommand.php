<?php

namespace Kna\MQEventBundle\Tests\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventCommand extends Command
{

    use ContainerAwareTrait;

    public function __construct(
        ContainerInterface $container,
        ?string $name = null
    )
    {
        $this->container = $container;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:event')
            ->setDescription('Raises events')
            ->setHelp('Raises events')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}