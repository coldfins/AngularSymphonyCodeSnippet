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

use Mobntouch\DataBaseBundle\Document\Feed;


class FeedlyCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('feedly:update')
            ->setDescription('Feedly Update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $headers = array('Authorization: OAuth Aj3htQZ7ImEiOiJGZWVkbHkgRGV2ZWxvcGVyIiwiZSI6MTQzMTI2MjMxMTAyMCwiaSI6ImZkMGJjMjdhLTg2ZTktNDllNi1hYzUwLTg3ZjQzZWNjODMwZSIsInAiOjQsInQiOjEsInYiOiJwcm9kdWN0aW9uIiwidyI6IjIwMTUuNyIsIngiOiJzdGFuZGFyZCJ9:feedlydev');

        $feedlySource = "https://cloud.feedly.com/v3/";
        $feedlySubscriptions = $feedlySource."subscriptions";
        //$feedlyStreams= $feedlySource."streams/ids?streamId=";
        $feedlyMixes= $feedlySource."mixes/contents?count=10&streamId=";

        $chSubscriptions = curl_init($feedlySubscriptions);


        curl_setopt($chSubscriptions, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($chSubscriptions, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chSubscriptions, CURLOPT_SSL_VERIFYPEER, false); // allow hhtps connections

        $mySubscriptions= curl_exec($chSubscriptions);
        $mySubscriptions = json_decode($mySubscriptions);


        $feed= array();

        $ch = curl_init();

        foreach($mySubscriptions as $subscriptions){

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $feedlyMixes.$subscriptions->id);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // allow hhtps connections

            $responseStream = curl_exec($ch);
            $responseStream = json_decode($responseStream);
            $items = $responseStream->items;

            foreach($items as $item){
                //print_r($item);

                if(isset($item->visual) and isset($item->visual->url)){

                    $tempFeed = new Feed();
                    $tempFeed->setsource($item->origin->title);
                    $tempFeed->setsourceURL($item->origin->htmlUrl);
                    $tempFeed->setTitle($item->title);
                    $tempFeed->setSummary(strip_tags($item->summary->content));
                    $tempFeed->setDate($item->published);
                    if($item->visual->url!='none') $tempFeed->setImage($item->visual->url);
                    //$tempFeed->setUrl($item->originId);
                    $tempFeed->setUrl($item->alternate[0]->href);
                    //print_r($tempFeed);
                    $feed[$item->published] = $tempFeed;

                }
            }
        }
        curl_close($ch);

        curl_close($chSubscriptions);

       /* print_r("#### KR sort ####");
        krsort($feed);
        print_r($feed);
        */

        usort($feed, function($a, $b)
        {
            return $a->getDate() < $b->getDate();
        });
        //print_r($feed);


        $limit = 8;
        $total = count($feed);

        print_r("TOTAL = ".$total);

        $index = 0;
        $k = 0;
        $paginatedFeed = array();

        foreach($feed as $j => $news){
            $k += 1;
            $paginatedFeed[] = $news;

            if($k==$limit or $j==($total-1)){
                $fs = new Filesystem();
                $env = $this->getContainer()->getParameter('kernel.environment');
                if($env=='dev') $fs->dumpFile("../angular/src/cdn/json/news-".$index.".json", json_encode($paginatedFeed));
                else $fs->dumpFile("../web/src/cdn/json/news-".$index.".json", json_encode($paginatedFeed));

                $index += 1;
                $k = 0;
                $paginatedFeed = array();
            }

        }

        if($feed){

            $fs = new Filesystem();
            $env = $this->getContainer()->getParameter('kernel.environment');
            if($env=='dev') $fs->dumpFile("../angular/src/cdn/json/news.json", json_encode($feed));
            else $fs->dumpFile("../web/src/cdn/json/news.json", json_encode($feed));

        }


        /*$param = array(
            'apiKey'=> 'klzyd4st8j69XlexFrXIycVAmXuEgk18s1LdEEaF',
            'idfa'=>$idfa,
            'country'=>$country,
            'device'=> $model,
            'publisher'=> 'app4phone',
            'appVersion'=>$appVersion,
            'token'=>$push==1?$token:null,
            'ios'=>$iosVersion,
            'push'=>$push,
            'ip'=>$ip,
        );*/

        //$postStringContest = http_build_query($param, '', '&');

        //curl_setopt($chSubscriptions, CURLOPT_POST, 1);
        //curl_setopt($chContest, CURLOPT_POSTFIELDS, $postStringContest);


        //print_r($responseContest);

        //curl_close($chContest);

        // Set up database
        /*$dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $users = $dm->createQueryBuilder('DataBaseBundle:UserSearch')
            ->getQuery()
            ->execute();

        $result = array();

        foreach($users as $user){
            $result[] = $user->getUser();
        }

        print_r($result);

        $fs = new Filesystem();
        $env = $this->getContainer()->getParameter('kernel.environment');
        if($env=='dev') $fs->dumpFile("../angular/src/cdn/json/usearch.json", json_encode($result));
        else $fs->dumpFile("../web/src/cdn/json/usearch.json", json_encode($result));*/
    }


}

