<?php

namespace AppBundle\Command;

use AppBundle\Classes\Cineworld;
use AppBundle\Classes\CineworldClient;
use AppBundle\Entity\Cinema;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DoctrineCommandHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TestCommand.
 *
 */
class TestCommand extends Command
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var CineworldClient
     */
    private $client;

    /**
     * @var Cineworld
     */
    private $cineworld;

    /**
     * @var OutputInterface
     */
    private $output;

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
        parent::initialize($input, $output);

        $this->command = strtolower(trim($input->getArgument('apiCall')));
        $this->cineworld = $this->getApplication()->getKernel()->getContainer()->get('cineworld');
        DoctrineCommandHelper::setApplicationEntityManager($this->getApplication(), null);
        $this->output = $output;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($this->command) {
            case 'cinemas':
                $this->cineworld->populateAllCinemas();
                break;

            case 'films':
                $this->cineworld->populateAllFilms();
                break;

            case 'dates':
                $this->cineworld->populateAllDates();
                break;

            case 'performances':
                $this->cineworld->populateAllPerformances();
                break;

            case 'all':
                $this->getAll();
                break;

            case 'bookEvents':
                $this->bookEvents();
                break;

            default:
                throw new \Exception('Unknown Command');
        }

        return 0;
    }

    /**
     * Populate All data
     */
    public function getAll()
    {
        $this->cineworld->populateAllCinemas();
        $this->cineworld->populateAllFilms();
        $this->cineworld->populateAllDates();
        $this->cineworld->populateAllPerformances();
    }

    private function bookEvents()
    {
        $date = new \DateTime('2017-03-25');

        $this->cineworld->bookEvents($date);
    }
}
