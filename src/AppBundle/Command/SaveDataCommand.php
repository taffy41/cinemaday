<?php

namespace AppBundle\Command;

use AppBundle\Classes\Cineworld;
use AppBundle\Classes\CineworldClient;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DoctrineCommandHelper;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SaveDataCommand
 */
class SaveDataCommand extends Command
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var Cineworld
     */
    private $cineworld;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('cineworld:test')
            ->addArgument('apiCall');
    }

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->command = strtolower(trim($input->getArgument('apiCall')));
        $this->cineworld = new Cineworld(new CineworldClient('https://www.cineworld.co.uk/api/quickbook/', 'qUnEyRXt'));
        $this->doctrine = $this->getHelper('doctrine');
        DoctrineCommandHelper::setApplicationEntityManager($this->getApplication());
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
