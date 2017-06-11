<?php

namespace Mablae\StoreLocatorBundle\Command;

use Mablae\StoreLocator\Model\LocatedStore;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LocateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mablae:store_locator:locate')
            ->setDescription('Runs the location lookup and distance calculcation')
            ->addArgument('searchTerm', InputArgument::REQUIRED, 'Either a physical Location, Zipcode or IP');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searchTerm = $input->getArgument('searchTerm');


        $locatedStoreList = $this->getContainer()->get('mablae.store_locator')->locateBySearchTerm($searchTerm);
        $output->writeln('Command result');

        $io = new SymfonyStyle($input, $output);
        /** @var $locatedStore LocatedStore */
        $io->table(
            ['Distance', 'Title'],
            $locatedStoreList->getStoreList()->map(
                function (LocatedStore $locatedStore) {
                    return [$locatedStore->getDistanceToPoint(), $locatedStore->getLocatedItem()->getTitle()];
                }
            )->toArray()
        );

    }

}
