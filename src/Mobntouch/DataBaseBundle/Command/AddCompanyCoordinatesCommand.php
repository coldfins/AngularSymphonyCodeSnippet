<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 26/05/14
 * Time: 10:52
 */

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddCompanyCoordinatesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('fix:AddCompanyCoordinates')
            ->setDescription('Add Company Coordinates Command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:Company')
            ->field('city')->exists(true)
            ->field('basedCountry')->exists(true)
            ->field('lat')->exists(false)
            ->field('lng')->exists(false)
            ->getQuery()
            ->execute()->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        do{

            $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->field('city')->exists(true)
                ->field('basedCountry')->exists(true)
                ->field('lat')->exists(false)
                ->field('lng')->exists(false)
                ->limit($limit)
                ->skip($limit*$skip)
                ->getQuery()
                ->execute();

            foreach($companies as $company){
                print_r($company->getUsername());
                print_r("\n");
                $count++;
                print_r("count : ".$count."\n");

                $city = $company->getCity();
                $basedCountry = $company->getBasedCountry();

                $city = str_replace(" ", "+", $city); // replace all the white space with "+" sign to match with google search pattern

                $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$city+$basedCountry";
                $response = file_get_contents($url);
                $json = json_decode($response,TRUE); //generate array object from the response from the web
                if(isset($json['results'][0])){

                    $lat = $json['results'][0]['geometry']['location']['lat'];
                    $lng = $json['results'][0]['geometry']['location']['lng'];

                    $dm->createQueryBuilder('DataBaseBundle:Company')
                        // Find the Campaign
                        ->update()
                        ->multiple(false)
                        ->field('_id')->equals($company->getId())

                        ->field('lat')->set($lat)
                        ->field('lng')->set($lng)
                        ->field('updateDate')->set(time())

                        // Options
                        ->upsert(false)
                        ->getQuery()
                        ->execute();


                    $dm->flush();
                    $dm->clear(); // Detaches all objects from Doctrine!
                }
            }

            $skip++;

        } while($count<$total);

        print_r("\n");
        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

    }


}

