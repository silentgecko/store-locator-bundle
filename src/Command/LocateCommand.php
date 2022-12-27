<?php

namespace Silentgecko\StoreLocatorBundle\Command;

use Silentgecko\StoreLocator\Model\LocatedStore;
use Silentgecko\StoreLocator\StoreLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LocateCommand extends Command
{
    protected static $defaultName = 'mablae:store_locator:locate';
    protected static $defaultDescription = 'Runs the location lookup and distance calculcation';
    private StoreLocator $storeLocator;

    public function __construct(StoreLocator $storeLocator, string $name = null)
    {
        parent::__construct($name);
        $this->storeLocator = $storeLocator;
    }

    protected function configure()
    {
        $this->addArgument('searchTerm', InputArgument::REQUIRED, 'Either a physical Location, Zipcode or IP');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $searchTerm = $input->getArgument('searchTerm');


        $locatedStoreList = $this->storeLocator->locateBySearchTerm($searchTerm);
        $output->writeln('Command result');

        $io = new SymfonyStyle($input, $output);
        /** @var $locatedStore LocatedStore */
        $io->table(
            ['Distance', 'Title'],
            $locatedStoreList->getStoreList()->map(
                fn(LocatedStore $locatedStore) => [$locatedStore->getDistanceToPoint(), $locatedStore->getLocatedItem()->getTitle()]
            )->toArray()
        );
        return Command::SUCCESS;

    }

}
