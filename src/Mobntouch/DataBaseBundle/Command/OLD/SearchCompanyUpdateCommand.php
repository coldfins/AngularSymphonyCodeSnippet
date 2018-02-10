<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 26/05/14
 * Time: 10:52
 */

namespace Mobntouch\DataBaseBundle\Command\OLD;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Filesystem\Filesystem;


class SearchCompanyUpdateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('search:company-update')
            ->setDescription('Update Search Company Data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $users = $dm->createQueryBuilder('DataBaseBundle:CompanySearch')
            ->getQuery()
            ->execute();

        $result = array();

        foreach($users as $user){
            $result[] = $user->getCompany();
        }

        print_r($result);

        $fs = new Filesystem();
        $env = $this->getContainer()->getParameter('kernel.environment');
        if($env=='dev') $fs->dumpFile("../angular/src/cdn/json/csearch.json", json_encode($result));
        else $fs->dumpFile("../web/src/cdn/json/csearch.json", json_encode($result));
    }


}

