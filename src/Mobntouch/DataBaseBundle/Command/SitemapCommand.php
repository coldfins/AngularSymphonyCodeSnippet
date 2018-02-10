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
use Symfony\Component\Filesystem\Filesystem;
use Mobntouch\DataBaseBundle\Document\Feed;

class SitemapCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('generate:sitemap')
                ->setDescription('Generate Sitemap')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $limit = 1000;
        $fs = new Filesystem();
        $env = $this->getContainer()->getParameter('kernel.environment');
        //$env = $this->get('kernel')->getEnvironment();
        $baseUrl = '';
        $siteUrl = 'https://www.mobintouch.com';
        if ($env == 'dev')
            $baseUrl = '../mobntouch/src/';
        else
            $baseUrl = '../web/src/';
       
$formatedTime = \DateTime::createFromFormat('U', time());
        //Testing part
        $sitemaps = array();
        $sitemapHead = "<?xml version='1.0' encoding='UTF-8'?>
            <?xml-stylesheet type='text/xsl' href='main-sitemap.xsl'?>
            <urlset xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' 
                    xmlns:image='http://www.google.com/schemas/sitemap-image/1.1' 
                    xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd' 
                    xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
        $sitemapbottom = "</urlset>";
        $generalSitemap = $sitemapHead . "
                <url>
                    <loc>{$siteUrl}</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1.0</priority>
                </url>
                <url>
                    <loc>{$siteUrl}/signin</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.9</priority>
                </url>
                <url>
                    <loc>{$siteUrl}/signup</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.9</priority>
                </url>
                <url>
                    <loc>{$siteUrl}/about</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>" . $sitemapbottom;
        $url = 'sitemap/landing.xml';
        //print_r($generalSitemap);
        $fs->dumpFile($baseUrl . $url, $generalSitemap);
       $sitemaps[] = array('url' => $siteUrl . '/' . $url, 'lastmod' => \DateTime::createFromFormat('U', time()));

        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        //Users sitemap
        $total = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('validated')->equals(true)
                ->getQuery()
                ->execute()
                ->count();

        $count = 0;
        $skip = 0;

        do {
            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('validated')->equals(true)
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();
            $uSitemap = $sitemapHead;
            foreach ($users as $user) {
                print(($count + 1) . " = " . $user->getUsername() . " \n");
                $lastmod = $user->getUpdateDate();
                if (!$lastmod)
                    $lastmod = intval(substr($user->getId(), 0, 8), 16);

                $formatedTime = \DateTime::createFromFormat('U', $lastmod);
                $uSitemap .= "<url>
                    <loc>{$siteUrl}/profile/{$user->getUsername()}</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1.0</priority>
                </url>";
                $count++;
            }
            $uSitemap .= $sitemapbottom;
            $url = 'sitemap/people-sitemap' . ($skip + 1) . '.xml';
            $fs->dumpFile($baseUrl . $url, $uSitemap);
            $sitemaps[] = array('url' => $siteUrl . '/' . $url, 'lastmod' => \DateTime::createFromFormat('U', time()));
            $skip++;
        } while ($count < $total);


        //Comapnies sitemap
        $total = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->getQuery()
                ->execute()
                ->count();

        $count = 0;
        $skip = 0;

        do {
            $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();
            $cSitemap = $sitemapHead;
            foreach ($companies as $company) {
                print(($count + 1) . " = " . $company->getUsername() . " \n");
                $lastmod = $company->getUpdateDate();
                if (!$lastmod)
                    $lastmod = intval(substr($company->getId(), 0, 8), 16);

                $formatedTime = \DateTime::createFromFormat('U', $lastmod);
                $cSitemap .= "<url>
                    <loc>{$siteUrl}/company/{$company->getUsername()}</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1.0</priority>
                </url>";
                $count++;
            }
            $cSitemap .= $sitemapbottom;
            $url = 'sitemap/companies-sitemap' . ($skip + 1) . '.xml';
            $fs->dumpFile($baseUrl . $url, $cSitemap);
            $sitemaps[] = array('url' => $siteUrl . '/' . $url, 'lastmod' => \DateTime::createFromFormat('U', time()));
            $skip++;
        } while ($count < $total);

        //Tags sitemap
        $tags = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->distinct('tags')
                ->getQuery()
                ->execute()
                ->toArray();

        $totalTags = count($tags);
        $count = 0;
        $skip = 1;
        do {
            $arrTags = array_slice($tags, $count, $limit);
            $tSitemap = $sitemapHead;
            foreach ($arrTags as $t) {
                print("{$t}- on sitemap -{$skip}\n");
                $formatedTime = \DateTime::createFromFormat('U', time());
                $t = strtolower($t);
                $tSitemap .= "<url>
                    <loc>{$siteUrl}/qa/tag/{$t}</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1.0</priority>
                </url>";
            }
            $tSitemap .= $sitemapbottom;
            $url = 'sitemap/questions-tag-sitemap' . $skip . '.xml';
            $fs->dumpFile($baseUrl . $url, $tSitemap);
            $sitemaps[] = array('url' => $siteUrl . '/' . $url, 'lastmod' => \DateTime::createFromFormat('U', time()));
            $count += $limit;
            $skip++;
        } while ($count < $totalTags);


        //Sitemap index
        $sitemapIndex = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemapIndex .= '<?xml-stylesheet type="text/xsl" href="sitemap/main-sitemap.xsl"?>';
        $sitemapIndex .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($sitemaps as $sm) {
            $formatedTime = $sm['lastmod'];
            $sitemapIndex .= "<sitemap>
                    <loc>{$sm['url']}</loc>
                    <lastmod>{$formatedTime->format('c')}</lastmod>
                </sitemap>";
        }

        $sitemapIndex .= '</sitemapindex>';
        $url = 'sitemap.xml';
     //   print_r($generalSitemap);
        $fs->dumpFile($baseUrl . $url, $sitemapIndex);
    }

}
