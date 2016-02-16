<?php

/**
 * Description of CronJobsCommand
 *
 * @author pinacolada
 */
namespace BackOfficeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\AddressTW;

class TaskLoadLieuxRencontresCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('task:loadLieuxRencontresTW')
            ->setDescription('Tâche initialisant les lieux de rencontres TW')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Running Task...</comment>');
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $csvFile = new \SplFileObject(realpath('app/Resources/Fixtures/Lieu-de-rencontre-TW.csv')); 
        if (($handle = fopen($csvFile->getRealPath(), "r")) != FALSE) {
            while (($row = fgets($handle)) != FALSE) {
                // process the row.                                
                //region;name;street;city;zip_code;
                $arrayRow = explode(";",$row);                
                if(count($arrayRow)>0)
                {
                    $address = new AddressTW();
                    $address->setName($arrayRow[1]);
                    $address->setStreet($arrayRow[2]);
                    $address->setZipCode($arrayRow[4]);
                    $address->setCity($arrayRow[3]);
                    $address->setRegion($arrayRow[0]);
                    $address->setCountry("France");
                    $em->persist($address); 
                }
            }
            fclose($handle);
        }
        $em->flush();
        
        $output->writeln('<comment>Task done!</comment>');
    }
}
