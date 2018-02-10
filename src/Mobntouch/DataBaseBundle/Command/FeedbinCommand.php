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
use Symfony\Component\DomCrawler\Crawler;

use Mobntouch\DataBaseBundle\Document\Feed;


class FeedbinCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('feedbin:update')
            ->setDescription('Feedly Update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //$source = "curl -u 'contact@mobintouch.com:RASk19;2A' https://api.feedbin.com/v2/entries.json?starred=true";
        //$source = "https://api.feedbin.com/v2/entries.json?starred=true";

        $entriesSource = "https://api.feedbin.com/v2/entries.json?starred=true&per_page=100";
        $subscriptionsSource = "https://api.feedbin.com/v2/subscriptions.json";


        // GET SUBSCRIPTIONS


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $subscriptionsSource);
        curl_setopt($ch, CURLOPT_USERPWD, 'contact@mobintouch.com:RASk19;2A');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // allow hhtps connections

        $tempSubscription = curl_exec($ch);
        $tempSubscription = json_decode($tempSubscription);
        //print_r($tempSubscription);

        $mySubscription = array();
        foreach($tempSubscription as $subscription){
            $mySubscription[$subscription->feed_id] = $subscription;
        }
        curl_close($ch);


        // GET ENTRIES


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $entriesSource);
        curl_setopt($ch, CURLOPT_USERPWD, 'contact@mobintouch.com:RASk19;2A');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // allow hhtps connections

        $myEntries= curl_exec($ch);
        $myEntries = json_decode($myEntries);

        $year = date('Y');
        $month = date('m');

        $env = $this->getContainer()->getParameter('kernel.environment');

        $feed= array();

        $ch = curl_init();

        foreach($myEntries as $entry){
            //print_r($entry);
            if(isset($mySubscription[$entry->feed_id])){

                $tempFeed = new Feed();

                $title = htmlspecialchars_decode($entry->title);
                $title = str_replace("&#147;", '"', $title);
                $title = str_replace("&#148;", '"', $title);
                $title = str_replace("&#39;", "'", $title);

                $tempFeed->setUrl($entry->url);
                $tempFeed->setTitle($title);
                $tempFeed->setSummary(strip_tags($entry->content));
                $tempFeed->setDate($entry->published);

                $tempFeed->setNewsID($entry->feed_id);
                $tempFeed->setSourceID($mySubscription[$entry->feed_id]->id);
                $tempFeed->setsource($mySubscription[$entry->feed_id]->title);
                $tempFeed->setsourceURL($mySubscription[$entry->feed_id]->site_url);

                // META IMAGE
                $src = null;
                try{
                    $html = file_get_contents($entry->url);
                    $crawler = new Crawler($html);
                    $imageMeta = array('meta[property="og:image"]', 'meta[name="og:image"]', 'meta[property="twitter:image:src"]', 'meta[name="twitter:image:src"]', 'meta[itemprop="image"]');
                    foreach($imageMeta as $meta){
                        $ogCrawler = $crawler->filter($meta);
                        if ($ogCrawler->count() > 0) {
                            // WE MUST INVEST TIME TO REFACTOR THIS!!!!!!

                            $src = $ogCrawler->first()->attr('content');

                            $src = strtok($src, '?');

                            $pos = strpos($src, '.gif');
                            if($pos>0) $src = substr($src, 0, $pos+4);

                            $pos = strpos($src, '.jpeg');
                            if($pos>0) $src = substr($src, 0, $pos+4);

                            $pos = strpos($src, '.jpg');
                            if($pos>0) $src = substr($src, 0, $pos+4);

                            $pos = strpos($src, '.png');
                            if($pos>0) $src = substr($src, 0, $pos+4);

                            if(strpos($src, '/') == 0) $src = $tempFeed->getsourceURL().$src;

                        }
                    }

                    if(!$src) {

                        preg_match( '@src="([^"]+)"@' , $entry->content, $match );

                        $src = array_pop($match);

                        if(!$src) {
                            try{

                                preg_match_all('/<img[^>]+>/i',$html, $result);

                                if(isset($result[0])){

                                    foreach($result[0] as $source){
                                        if (preg_match('/logo/i',$source)) {
                                            // do nothing - logos do not interest us
                                        }else{
                                            preg_match( '@src="([^"]+)"@' , $source, $match );
                                            $src = array_pop($match);
                                            $pos = strpos($src, '.gif');
                                            $break = false;
                                            if($pos>0) {
                                                $src = substr($src, 0, $pos+4);
                                                $break = true;
                                            }
                                            $pos = strpos($src, '.jpeg');
                                            if($pos>0) {
                                                $src = substr($src, 0, $pos+4);
                                                $break = true;
                                            }
                                            $pos = strpos($src, '.jpg');
                                            if($pos>0) {
                                                $src = substr($src, 0, $pos+4);
                                                $break = true;
                                            }
                                            $pos = strpos($src, '.png');
                                            if($pos>0) {
                                                $src = substr($src, 0, $pos+4);
                                                $break = true;
                                            }
                                            if(strpos($src, '/') == 0){
                                                $src = $tempFeed->getsourceURL().$src;
                                            }
                                            if($break) break;

                                        }
                                    }
                                }



                            } catch (\Exception $e) {}

                        }
                    }
                } catch (\Exception $e) {}

                if($src) {


                    $pos1 = strrpos($src,".");
                    $extension =  substr($src, $pos1);

                    $extension = strtok($extension, '?');

                    $pos = strpos($extension, '.gif');
                    if($pos>0) $extension = substr($extension, 0, $pos+4);

                    $pos = strpos($extension, '.jpeg');
                    if($pos>0) $extension = substr($extension, 0, $pos+4);

                    $pos = strpos($extension, '.jpg');
                    if($pos>0) $extension = substr($extension, 0, $pos+4);

                    $pos = strpos($extension, '.png');
                    if($pos>0) $extension = substr($extension, 0, $pos+4);

                    //print($src);
                    $newSource = 'img/feed/'.$year.'-'.$month.'-'.$entry->id.$extension;

                    //$all = array('https://tctechcrunch2011.files.wordpress.com/2015/02/curbside-best-buy.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/all-circulars2x.png?w=680','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Could-Main-Street-Go-Mobile-One-Company-Says-Its-Not-Just-for-the-Big-Boys-Now-300x188.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/More-Blowback-Against-the-Broadband-Bandits-FTC-Asks-Court-To-Allow-Throttling-Lawsuit-to-Proceed-Against-ATT-300x181.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Ad-Tech-Marketing-Bigwigs-Believe-Most-Marketers-Not-Mastering-Basics-of-Digital-Advertising-300x161.png','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/BlackBerry-Vs.-Ryan-Seascrest-Round-2-200x300.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/NASCAR-Start-Your-Engines-for-In-App-Loyalty-Program-300x300.png','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Screen-Shot-2015-02-17-at-18.57.28-520x245.png','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/V12-Group-Revamps-Launchpad-Marketing-Cloud-300x200.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Shoutlet-Secures-a-Spot-in-Facebook-Marketing-Partner-Program-300x169.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/screen-shot-2015-02-18-at-8-29-27-am.png?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/altc-520x245.png','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Neptune-Duo-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/p1030466.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/bevy-lifestyle_hero-shot.jpg?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Glip_Mac_DesktopApp-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Screen-Shot-2015-02-17-at-4.37.32-PM-520x245.png','https://tctechcrunch2011.files.wordpress.com/2015/01/new-droidcast-banner.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/apptimize.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/yik-yak-downvotes.gif?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2014/07/Twitter_hq_5-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/img_8231.jpg?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/PushBullet-520x245.png','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Tweetdeck-teams-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/new-cloud-storage-integration-for-office-1.png?w=513','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Screen-Shot-2015-02-17-at-17.05.02-520x245.png','https://tctechcrunch2011.files.wordpress.com/2015/02/13198824765_4bf9cb6814_h.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/apple-vr-headset.png?w=680','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/SXSW-%E2%80%98Mobile-Saturday%E2%80%99-Set-for-March-14-Expect-Long-Lines-and-a-Wealth-of-Innovative-Ideas-300x163.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Dont-Underestimate-The-Impact-of-an-Excellent-Mobile-Experience-300x194.png','https://tctechcrunch2011.files.wordpress.com/2015/02/galaxy-teaser.jpg?w=680','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Digital-Marketing-Gets-Trendy-for-SMBs-300x200.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/TGI-Fridays-Now-Serving-Expanded-Digital-Marketing-Efforts-300x225.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Apple-Speeding-Toward-50B-in-New-Revenue-300x185.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/InMobi-Has-As-Many-Devices-on-Its-Platform-as-India-Has-Citizens-A-Billion-and-Counting-300x167.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/DSC04857-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2014/09/apple-watch-music.png?w=680','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Personal-Approach-Offered-in-New-Mobile-Marketing-Solution-300x257.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/oyster-app-home-520x245.png','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/SmartEyeglass-Developer-Edition-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Vidhub-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2014/10/iphone-6-plus-on-bookshelf.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/screen-shot-2015-02-16-at-4-40-48-pm.png?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/rise-of-the-micro-tinder1.png?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Screen-Shot-2015-02-16-at-18.17.41-520x245.png','http://cdn1.tnwcdn.com/assets/images/transparent.png','https://tctechcrunch2011.files.wordpress.com/2015/02/iphoneblackplayback.jpg?w=680','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/When-Net-Neutrality-Policy-Comes-Down-FCC-Likely-to-Permit-Sponsored-Data-Programs-300x225.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Screen-Shot-2015-02-16-at-14.45.07-520x245.png','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Is-Google-a-Goner-New-York-Times-Columnist-Suggests-Search-Giant-Could-%E2%80%98End-With-a-Whimper%E2%80%99-300x169.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/HonorFeat-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/DJI-Drone-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/UbuntuPhone-520x245.png','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/DesireEyeMainFeat-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2014/12/0704_drones-520x245.gif','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/XperiaE4-520x245.png','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Sony-wearable-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/LG-Watch-Urbane-520x245.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Buy-This-Zip-Code-CraveLabs-Launches-%E2%80%98DropIn%E2%80%99-Location-Targeting-to-Help-Local-Businesses-Reach-Customers-300x186.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Jellyfish-Reports-Lots-of-Digital-Fish-in-the-Sea-Companys-Billings-Pass-100-Million-300x172.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Former-Mercedes-Benz-RD-Guru-Lands-at-Apple.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/BMO-Analyst-Expetcs-200-Million-iPhone-Sales-This-Year-300x175.png','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/mHealth-News-What-You-Need-to-Know1-300x199.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/5314774452_0922077c61_o.jpg?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2014/09/slack-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/flickr-badges-730x276-520x245-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2015/01/tc-applecast-post.png?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2014/02/snapchat_android_3-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/tgif.png?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/screen-shot-2015-02-13-at-3-06-14-pm.png?w=680','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Screen-Shot-2015-02-13-at-2.40.49-AM-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/0213_timcook-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2014/09/img_4155.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/01/cook.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/galaxy-teaser.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/tumblr_static_weed-leaf-wallpaper_3672.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2015/02/rockbot-anthem-techcrunch-image.jpg?w=680','https://tctechcrunch2011.files.wordpress.com/2014/04/screen-shot-2014-04-15-at-4-41-12-pm.png?w=680','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Jon-Stewart-More-Trusted-Than-Bloomberg-300x200.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/AOL-Enjoys-a-Boom-But-Must-Tackle-Mobile-to-Avoid-a-Bust-300x196.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Here%E2%80%99s-What-Happened-in-Mobile-Marketing-This-Week1-300x212.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Mobile-Apps-Home-is-Where-the-Hazzards-Are-300x199.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/supplierreport-300x296.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Taking-the-Problematic-Out-of-Programmatic-with-Advanced-RTB-Technology-300x225.jpg','http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Native-Ad-Guidelines-Enter-the-Global-Spotlight-300x200.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/Next-Lock-Screen-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/LineAt-520x245.jpg','http://cdn1.tnwcdn.com/wp-content/blogs.dir/1/files/2015/02/feat_darkroom-520x245.jpg','https://tctechcrunch2011.files.wordpress.com/2015/02/the-dating-game-set.jpg?w=680');
                    /*
                    https://tctechcrunch2011.files.wordpress.com/2015/02/curbside-best-buy.jpg?w=680
                    https://tctechcrunch2011.files.wordpress.com/2015/02/all-circulars2x.png?w=680
                    http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/Could-Main-Street-Go-Mobile-One-Company-Says-Its-Not-Just-for-the-Big-Boys-Now-300x188.jpg
                    http://www.mobilemarketingwatch.com/wordpress/wp-content/uploads/2015/02/More-Blowback-Against-the-Broadband-Bandits-FTC-Asks-Court-To-Allow-Throttling-Lawsuit-to-Proceed-Against-ATT-300x181.jpghttp://www.mobilemarketingwatch.com/
                    */
                    if($env=='dev') $image = '../angular/src/cdn/'.$newSource;
                    else $image = '../web/src/cdn/'.$newSource;

                    $tempFeed->setImage($newSource);
                    try{
                        file_put_contents($image, file_get_contents(ltrim($src, '/')));
                        //exec('/usr/bin/convert '.getcwd().'/'.$tempIcon.' -resize 180x180 -quality 85 '.getcwd().'/web/uploads/askovore/icons/'.$app->getAppid().'.jpg');
                    } catch (\Exception $e) {
                        //print_r("Exception");
                    }

                }
                //$tempFeed->setUrl($item->originId);

                //print_r($tempFeed);
                $feed[$entry->published] = $tempFeed;


                // Set up database
                $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

                $exists = $dm->createQueryBuilder('DataBaseBundle:Update')
                    ->field('newsID')->equals($entry->id)
                    ->limit(1)
                    ->getQuery()->execute()->count();

                if($exists==0){

                    $dm->createQueryBuilder('DataBaseBundle:Update')
                        // Find the Campaign
                        ->update()
                        ->multiple(false)
                        ->field('newsID')->equals($entry->id)

                        // Update found Campaign
                        //->field("date")->set(time())
                        ->field("date")->set(strtotime($tempFeed->getDate()))
                        ->field("type")->set(intval(4))
                        ->field("filter")->set('professional')
                        ->field("action")->set('news')
                        ->field("likesCounter")->set(intval(0))
                        ->field("commentsCounter")->set(intval(0))
                        ->field("liked")->set(array())
                        ->field("isLike")->set(false)
                        ->field("newsID")->set($entry->id)
                        ->field("sourceID")->set($tempFeed->getSourceID())
                        ->field("newsTitle")->set($tempFeed->getTitle())
                        ->field("newsSummary")->set($tempFeed->getSummary())
                        ->field("newsImage")->set($tempFeed->getImage())
                        ->field("newsSource")->set($tempFeed->getsource())
                        ->field("newsSourceURL")->set($tempFeed->getsourceURL())
                        ->field("newsURL")->set($tempFeed->getUrl())
                        ->field("newsDate")->set(strtotime($tempFeed->getDate()))

                        // Options
                        ->upsert(true)
                        ->getQuery()
                        ->execute();


                    $dm->flush();
                    $dm->clear(); // Detaches all objects from Doctrine!

                }
            }
        }
        curl_close($ch);

        usort($feed, function($a, $b)
        {
            return $a->getDate() < $b->getDate();
        });
        //print_r($feed);


        $limit = 9;

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
    }

}

