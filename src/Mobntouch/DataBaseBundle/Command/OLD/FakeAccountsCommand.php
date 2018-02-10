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

use Mobntouch\DataBaseBundle\Document\User;


class FakeAccountsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('fake:accounts')
            ->setDescription('FAKE ACCOUNTS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');


        $dm->createQueryBuilder('DataBaseBundle:User')
            // Delete Fake Users
            ->remove()
            ->field('email')->exists(false)
            ->getQuery()
            ->execute();


        $companyTypes = array('Advertiser', 'TrafficSupplier', 'AppSolutions');
        $companySubTypes = array(
            'Advertiser' => array('Games', 'Apps', 'Other'),
            'TrafficSupplier' => array(
                'MobileAdvertisingAgency',
                'MobileAdvertisingNetwork',
                'AppsNetwork',
                'MobileMediaBuying',
                'SocialAdvertising',
                'IncentivizedOfferwallPublisher',
                'AppStore',
                'CrossPromotion',
                'AppDiscoveryPublisher',
                'AppVideosPublisher',
                'Other'
            ),
            'AppSolutions' => array(
                'MobileAppTracking',
                'MobileRetargeting',
                'AppEngagement',
                'AppAnalytics',
                'AppMarketingAutomation',
                'AppPressRelease',
                'AppReviews',
                'AppStoreData',
                'AppStoreOptimization',
                'PushNotifications',
                'Other'
            )
        );

        $pricing = array("cpa",
            "cps",
            "cpc",
            "cpv",
            "cpd",
            "cpi",
            "cpl",
            "cpm",
            "dclick",
            "c2call",
            "ppcall");

        $typeT = array("incentivized",
            "nonincentivized");

        $platforms = array(
            "ios",
            "blackberry",
            "unity",
            "android",
            "windows",
            "web",
            "bada"
        );

        $kind = array(
            "DisplayInterstitial",
            "DisplayBanners",
            "Video",
            "Offerwall",
            "NativeAds",
            "RichMedia",
            "Social",
            "PushNotifications",
            "MessagingSMS",
            "Popups",
            "VirtualCurrency",
            "AppWalls",
            "AppIcon",
            "Audio",
            "Billboard",
            "ContentLock",
            "pre-roll",
            "Redirects",
            "Search",
            "TextAds",
            "Other"
        );

        $trafficType = array("Apps",
            "Games",
            "Gambling",
            "Adult",
            "Casino",
            "Dating",
            "Entertainment",
            "Search",
            "Social",
            "Travel",
            "Video",
            "VOD",
            "WebMobile"
        );

        $countries = array("US","GB","Africa","APAC","ASIA","Europe","EasternEurope","LATAM","MiddleEast","AF","AL","DZ","AS","AD","AG","AI","AG","AR","AA","AW","AU","AT","AZ","BS","BH","BD","BB","BY","BE","BZ","BJ","BM","BT","BO","BL","BA","BW","BR","BC","BN","BG","BF","BI","KH","CM","CA","IC","CV","KY","CF","TD","CD","CL","CN","CI","CS","CO","CC","CG","CK","CR","CT","HR","CU","CB","CY","CZ","DK","DJ","DM","DO","TM","EC","EG","SV","GQ","ER","EE","ET","FA","FO","FJ","FI","FR","GF","PF","FS","GA","GM","GE","DE","GH","GI","GR","GL","GD","GP","GU","GT","GN","GY","HT","HW","HN","HK","HU","IS","IN","ID","IA","IQ","IR","IM","IL","IT","JM","JP","JO","KZ","KE","KI","NK","KS","KW","KG","LA","LV","LB","LS","LR","LY","LI","LT","LU","MO","MK","MG","MY","MW","MV","ML","MT","MH","MQ","MR","MU","ME","MX","MI","MD","MC","MN","MS","MA","MZ","MM","NA","NU","NP","AN","NL","NV","NC","NZ","NI","NE","NG","NW","NF","NO","OM","PK","PW","PS","PA","PG","PY","PE","PH","PO","PL","PT","PR","QA","ME","RS","RE","RO","RU","RW","NT","EU","HE","KN","LC","MB","PM","VC","SP","SO","AS","SM","ST","SA","SN","RS","SC","SL","SG","SK","SI","SB","OI","ZA","ES","LK","SD","SR","SZ","SE","CH","SY","TA","TW","TJ","TZ","TH","TG","TK","TO","TT","TN","TR","TU","TC","TV","UG","UA","AE","UY","UZ","VU","VS","VE","VN","VB","VA","WK","WF","YE","ZR","ZM","ZW");

        $targeting = array(
            "Platform",
            "Device",
            "OSVersion",
            "Age",
            "Sexe",
            "Language",
            "Carrier",
            "Country",
            "Location",
            "Audience",
            "Behavioral",
            "Browser",
            "Category"
        );

        $trading = array(
            "SelfManaged",
            "FullyManaged",
            "SelfService",
            "Direct",
            "Blind",
            "Commission",
            "CrossPromotion",
            "Performance Based",
            "Premium",
            "Mediation",
            "PrivateExchange",
            "Programmatic",
            "RealTimeBidding"
        );

        $iosApps =
  array (
      0 =>
          array (
              'name' => 'Plague Inc.',
              'appid' => 525818839,
              'icon' => 'http://a1606.phobos.apple.com/us/r30/Purple4/v4/a3/59/2d/a3592d2d-378d-d1bc-3d29-ce70f51b1aed/plague_icon_57.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Strategy',
                      2 => 'Simulation',
                  ),
          ),
      1 =>
          array (
              'name' => 'Worms3',
              'appid' => 596677177,
              'icon' => 'http://a649.phobos.apple.com/us/r30/Purple1/v4/ba/77/73/ba7773cb-89f4-df3f-2900-d61252e48288/AppIcon57x57.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Action',
                      3 => 'Strategy',
                  ),
          ),
      2 =>
          array (
              'name' => 'Geometry Dash',
              'appid' => 625334537,
              'icon' => 'http://a1272.phobos.apple.com/us/r30/Purple1/v4/41/70/04/41700454-dda3-21c9-fc48-ac66f4084782/AppIcon57x57.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Arcade',
                      3 => 'Music',
                  ),
          ),
      3 =>
          array (
              'name' => '7 Minute Workout Challenge',
              'appid' => 680170305,
              'icon' => 'http://a432.phobos.apple.com/us/r30/Purple/v4/dd/11/36/dd113655-f7fc-4f13-0e32-cf7b3a9321b1/Icon57.png',
              'categories' =>
                  array (
                      0 => 'Health & Fitness',
                      1 => 'Lifestyle',
                  ),
          ),
      4 =>
          array (
              'name' => 'Game of Thrones - A Telltale Games Series',
              'appid' => 906862658,
              'icon' => 'http://a478.phobos.apple.com/us/r30/Purple1/v4/9b/16/96/9b16961f-74af-3182-1343-98dff96ae076/Icon.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Adventure',
                  ),
          ),
      5 =>
          array (
              'name' => 'Runtastic PRO GPS Running, Walking, Jogging, Marathon & Fitness Tracker',
              'appid' => 366626332,
              'icon' => 'http://a1862.phobos.apple.com/us/r30/Purple3/v4/d5/aa/eb/d5aaebd3-31d9-a3e4-b33e-c842b7d7871a/AppIcon60x60_U00402x.png',
              'categories' =>
                  array (
                      0 => 'Health & Fitness',
                      1 => 'Sports',
                  ),
          ),
      6 =>
          array (
              'name' => 'Tomb Raider I',
              'appid' => 663820495,
              'icon' => 'http://a13.phobos.apple.com/us/r30/Purple3/v4/a5/f7/69/a5f7694a-4338-54c8-05c5-8b62b511a3ac/AppIcon57x57.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Adventure',
                      2 => 'Action',
                  ),
          ),
      7 =>
          array (
              'name' => 'Cut the Rope 2',
              'appid' => 681814050,
              'icon' => 'http://a1839.phobos.apple.com/us/r30/Purple3/v4/52/53/17/52531728-1e4a-4c1b-8134-21f38f1f239e/AppIcon57x57.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Arcade',
                      3 => 'Puzzle',
                  ),
          ),
      8 =>
          array (
              'name' => 'Grand Theft Auto: San Andreas',
              'appid' => 763692274,
              'icon' => 'http://a1453.phobos.apple.com/us/r30/Purple3/v4/e0/4e/44/e04e44c4-9417-b0a7-a36b-18657e86eb09/AppIcon-257x57.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Action',
                      3 => 'Adventure',
                  ),
          ),
      9 =>
          array (
              'name' => 'Clash of Clans',
              'appid' => 529479190,
              'icon' => 'http://a1008.phobos.apple.com/us/r30/Purple3/v4/f0/37/b4/f037b44c-c05e-9623-3caa-9c459edc5466/Icon.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Action',
                      3 => 'Strategy',
                  ),
          ),
      10 =>
          array (
              'name' => 'Candy Crush Saga',
              'appid' => 553834731,
              'icon' => 'http://a1909.phobos.apple.com/us/r30/Purple5/v4/56/d8/bc/56d8bcaf-7cb0-1e61-003b-1e9a9507db93/Icon.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Entertainment',
                      2 => 'Arcade',
                      3 => 'Puzzle',
                  ),
          ),
      11 =>
          array (
              'name' => 'Boom Beach',
              'appid' => 672150402,
              'icon' => 'http://a663.phobos.apple.com/us/r30/Purple5/v4/6b/59/c1/6b59c16e-1071-84e5-70bc-a32d37c45ea0/Icon.png',
              'categories' =>
                  array (
                      0 => 'Games',
                      1 => 'Action',
                      2 => 'Strategy',
                  ),
          ),
  );


        $paymentMethods = array("Alipay","BankTransfer","Cheque","CreditCard","EPESE","Payoneer","Paypal","WebMoney");

        $paymentTerms = array("prePaid" => false, "prepayment" => false, "daily" => false, "weekly" => false, "biWeekly" => false, "monthly" => true, "netXYChecked" => false, "netXYvalue" => false);

        $interests = array("Travel", "Gamers", "Readers", "Footballers", "Singers", "Designers", "Fashion");

        $gender = array('all', 'males', 'females');

        //$countries = array("US","GB","Africa","APAC","ASIA","Europe","EasternEurope","LATAM","MiddleEast","AF","AL","DZ","AS","AD","AG","AI","AG","AR","AA","AW","AU","AT","AZ","BS","BH","BD","BB","BY","BE","BZ","BJ","BM","BT","BO","BL","BA","BW","BR","BC","BN","BG","BF","BI","KH","CM","CA","IC","CV","KY","CF","TD","CD","CL","CN","CI","CS","CO","CC","CG","CK","CR","CT","HR","CU","CB","CY","CZ","DK","DJ","DM","DO","TM","EC","EG","SV","GQ","ER","EE","ET","FA","FO","FJ","FI","FR","GF","PF","FS","GA","GM","GE","DE","GH","GI","GR","GL","GD","GP","GU","GT","GN","GY","HT","HW","HN","HK","HU","IS","IN","ID","IA","IQ","IR","IM","IL","IT","JM","JP","JO","KZ","KE","KI","NK","KS","KW","KG","LA","LV","LB","LS","LR","LY","LI","LT","LU","MO","MK","MG","MY","MW","MV","ML","MT","MH","MQ","MR","MU","ME","MX","MI","MD","MC","MN","MS","MA","MZ","MM","NA","NU","NP","AN","NL","NV","NC","NZ","NI","NE","NG","NW","NF","NO","OM","PK","PW","PS","PA","PG","PY","PE","PH","PO","PL","PT","PR","QA","ME","RS","RE","RO","RU","RW","NT","EU","HE","KN","LC","MB","PM","VC","SP","SO","AS","SM","ST","SA","SN","RS","SC","SL","SG","SK","SI","SB","OI","ZA","ES","LK","SD","SR","SZ","SE","CH","SY","TA","TW","TJ","TZ","TH","TG","TK","TO","TT","TN","TR","TU","TC","TV","UG","UA","AE","UY","UZ","VU","VS","VE","VN","VB","VA","WK","WF","YE","ZR","ZM","ZW");

        $index = 1;
        $total = 1;

        foreach($companyTypes as $type){
            $subtypes = $companySubTypes[$type];
            foreach($subtypes as $subtype){

                $i = 0;
                while($i<50){

                    $user = array();

                    if($index==0) $index = 1;

                    $user['companyType'] = $type;
                    $user['companySubType'] = $subtype;

                    $names = array("Annabell", "Myriam", "Mistie", "Lorena", "Beaulah", "Danae", "Soo", "Johnie", "Shaunte", "Augustus", "Sherryl", "Fawn", "Delisa", "Becky", "Cassaundra", "Alvera", "Elza", "Jamie", "Zora", "Hanna", "Matha", "Ashley", "Maybelle", "Wenona", "Kelvin", "Vickey", "Jacquiline", "Malia", "Zoila", "Bo", "Henry", "Shantelle", "Lilia", "Quiana", "Lesia", "Diann", "Ricki", "Marilou", "Virgen", "Ray", "Towanda", "Benita", "Altagracia", "Kam", "Rickie", "Lacresha", "Tiera", "Malvina", "Alex", "Bennett", "Bebe", "Delfina", "Bea", "Milford", "Ayanna", "Kyle", "Lekisha", "Lupita", "Willa", "Rozanne", "Contessa", "Shawana", "Cayla", "Denyse", "Jesusita", "Frankie", "Alia", "Jimmy", "Sherlyn", "Brigid", "Aleisha", "Silvana", "Deedra",
                        "Leola","Nina","Carolyn","Barry","Alvaro","Shaunna","Marcelino","Ernie","Kenna","Preston","Kristin","Ashleigh","Tyesha","Zola","Lynna","Don","Velda","Fran","Lakenya","Lashunda","Elmo","Kanisha","Jon","Erik","Shay","Francine",
                        "Jess","Rosie","Marx","Dewitt","Dena","Nigel","Melodie","Domenic","Becki","Sid","Bee","Drema","Lyn","Renee","Casimira","Alvina","Mabel","Desmond","Nathaniel","Shanita","Jesus","Regena","Siobhan","Vita","Brady","Vito", "Emelda","Soraya","Wallace","Hayley","Zachariah","Brande","Elva","Adah");
                    //$user['name'] = $names[array_rand($names, 1)];
                    $user['name'] = $names[mt_rand(0,count($names)-1)];

                    $lastnames = array("Kemble", "Balog", "Pierro", "Werley", "Marker", "Mangus", "Foree", "Doughtie", "Sardina", "Segundo", "Eanes", "Hockman", "Shiflett", "Gottschalk", "Propp", "Fogg", "Benn", "Brunton", "Crose", "Buskey", "Whitted", "Gerson", "Mossey", "Hennessy", "Stringfield", "Demyan", "Cervantes", "Murrah", "Mccain", "Varden", "Downey", "Godfrey", "Kay", "Brinks", "Youngman", "Cavazos", "Long", "Wolter", "Whitcomb", "Armistead", "Schofield", "Keith", "Andresen", "Mckittrick", "Gilligan", "Oriol", "Parcell", "Ackles", "Mcmillion", "Trudeau", "Weary", "Rhames", "Matthes", "Done", "Plewa", "Ramey", "Grimmett", "Derrico", "Blackmer", "Eck", "Mash", "Stiff", "Kane", "Gammons", "Creighton", "Coday", "Culberson", "Moncrief", "Sarver", "Rotter", "Davila", "Ocheltree", "Rotenberry",
                        "Philson","Eugene","Mroz","Shankles","Petroski","Byun","Audet","Gallman","Tackett","Cirilo","Mcmillen","Smoak","Burnley","Olmos","Caraway","Hagar","Everest","Muench","Tharp","Schlueter","Heard","Vanvliet","Callahan","Bohner","Kerschner","Charlie","Carreira","Geier","Seidell","Holzworth","Mckeighan","Beard","Goding","Baran","Callison","Granillo","Bodkin","Kohl","Loya","Rainey","Reitzel","Warlick","Flax","Bailes","Stobaugh","Poirrier","Emily","Zambrana","Musselman","Sikora","Sammons","Mountain","Filler","Lapointe","Gettinger","Vanwagenen","Mccann","Nester","Quashie","Montijo");
                    //$user['lastname'] = $lastnames[array_rand($lastnames, 1)];
                    $user['lastname'] = $lastnames[mt_rand(0,count($lastnames)-1)];


                    $user['username'] = strtolower($user['name'].$user['lastname']);
                    $user['index'] = $index;

                    $user['company'] = $type." Company ".$total;

                    $explodeName = explode(" ", $user['name']);
                    $explodeLastName = explode(" ", $user['lastname']);
                    $explodeCompany= explode(" ", $user['company']);
                    $search = array_merge($explodeName, $explodeLastName, $explodeCompany);
                    $user['search'] = $search;

                    $user['jobTitle'] = 'Manager #'.$index;

                    $competences = array("RTB", "Sales", "Management", "Mobile", "Windows", "Android", "iOS", "Web", "Marketing");
                    $temp = array_rand($competences, 2);
                    foreach($temp as $t) $user['competences'][] = $competences[$t];

                    $languages = array("Mandarin", "Hindi", "Spanish", "English", "Arabic", "Portuguese", "Bengali", "Russian", "Japanese", "Punjabi", "German");
                    $temp = array_rand($languages, 2);
                    foreach($temp as $t) $user['languages'][] = $languages[$t];

                    if($type=='Advertiser'){
                        $user['buyTraffic'] = array();
                        foreach($pricing as $key => $value){
                            $user['buyTraffic'][$value] = mt_rand(0,1) == 1;
                        }
                        $user['buyTraffic']['interests'] = $interests[array_rand($interests, 1 )];
                        $user['buyTraffic']['gender'] = $gender[array_rand($gender, 1 )];
                        $fromage = mt_rand(16,99);
                        $toage = $fromage + rand(1,20);
                        $days = mt_rand(5,30);
                        $fromperiod = (time()+ $days*24*60*60)*1000;
                        $days = mt_rand(31,90);
                        $toperiod = (time()+ $days*24*60*60)*1000;
                        $user['buyTraffic']['fromage'] = $fromage;
                        $user['buyTraffic']['toage'] =  $toage;
                        $user['buyTraffic']['fromperiod'] = $fromperiod;
                        $user['buyTraffic']['toperiod'] = $toperiod;

                    }
                    if($type=='TrafficSupplier'){
                        $user['sellTraffic'] = array();

                        foreach($pricing as $key => $value){
                            $user['sellTraffic'][$value] = mt_rand(0,1) == 1;
                        }

                        foreach($typeT as $key => $value){
                            $user['sellTraffic'][$value] = mt_rand(0,1) == 1;
                        }

                        foreach($platforms as $key => $value){
                            $user['sellTraffic'][$value] = mt_rand(0,1) == 1;
                        }

                        $temp = array_rand($countries, rand(1,5));
                        if(is_array($temp)) foreach($temp as $t) $user['sellTraffic']['country'][] = $countries[$t];
                        else $user['sellTraffic']['country'][] = $countries[$temp];

                        $r = mt_rand(0,3);
                        if($r>0){
                            $temp = array_rand($kind, $r);
                            if(is_array($temp)) foreach($temp as $t) $user['sellTraffic']['kind'] = $kind[$t];
                            else $user['sellTraffic']['kind'] = $kind[$temp];
                        }

                        $r = mt_rand(0,3);
                        if($r>0) {
                            $temp = array_rand($trafficType, $r);
                            if(is_array($temp)) foreach($temp as $t) $user['sellTraffic']['trafficType'] = $trafficType[$t];
                            else $user['sellTraffic']['trafficType'] = $trafficType[$temp];
                        }

                        $r = mt_rand(0,4);
                        if($r>0) {
                            $temp = array_rand($targeting, $r);
                            if(is_array($temp)) foreach($temp as $t) $user['sellTraffic']['targeting'] = $targeting[$t];
                            else $user['sellTraffic']['targeting'] = $targeting[$temp];
                        }

                        $r = mt_rand(0,2);
                        if($r>0) {
                            $temp = array_rand($trading, $r);
                            if(is_array($temp)) foreach($temp as $t) $user['sellTraffic']['trading'] = $trading[$t];
                            else $user['sellTraffic']['trading'] = $trading[$temp];
                        }

                    }

                    foreach($paymentMethods as $key => $payType){
                        $user['paymentMethods'][$payType] = mt_rand(0,1) == 1;
                    }

                    $user['iosApps'] = array($iosApps[array_rand($iosApps, 1 )]);
                    $user['paymentTerms'] = $paymentTerms;
                    $services = array('AdXTracking', 'adjust', 'AppAnnie', 'AppsFlyer', 'MixPanel', 'MobileAppTracking');
                    $user['services'] = array('name' => array($services[array_rand($services, 1 )]) , 'description' => 'Please contact me for more information :)');

                    /*$references = array(
                        array('avatar' => "/uploads/profile/avatars/a".mt_rand(0,9).'.jpg?'.time(), 'name' => )
                    );*/



                    //print_r($user);

                    $this->addAccount($user);

                    $total++;
                    $index++;
                    $index = $index%10;


                    $i++;
                }
            }
        }

    }

    private function addAccount($user){

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');


        $q = $dm->createQueryBuilder('DataBaseBundle:User')
            // Find
            ->update()
            ->multiple(false)
            ->field('name')->equals($user['name'])
            ->field('lastname')->equals($user['lastname'])
            ->field('companyType')->equals($user['companyType'])
            ->field('companySubType')->equals(array($user['companySubType']))

            // Update
            ->field('validated')->set(true)
            ->field('search')->set($user['search'])
            ->field('name')->set($user['name'])
            ->field('lastname')->set($user['lastname'])
            ->field('username')->set($user['username'])
            ->field('companyType')->set($user['companyType'])
            ->field('companySubType')->set(array($user['companySubType']))
            ->field("avatar")->set("/uploads/profile/avatars/a".$user['index'].'.jpg?'.time())
            ->field("company")->set($user['company'])
            ->field("jobTitle")->set($user['jobTitle']);
        if($user['companyType']=='Advertiser'){
            $q->field('buyTraffic')->set(array( $user['buyTraffic'] ))
                ->field('iosApps')->set($user['iosApps'])
                ->field('androidApps')->set($user['iosApps'])
            ;
        }
        if($user['companyType']=='TrafficSupplier'){
            $q->field('sellTraffic')->set(array( $user['sellTraffic'] ));
        }

        $q
            ->field('paymentMethods')->set($user['paymentMethods'])
            ->field('paymentTerms')->set($user['paymentTerms'])
            ->field('competences')->set($user['competences'])
            ->field('languages')->set($user['languages'])
            ->field('services')->set(array($user['services']))

            // Options
            ->upsert(true)
            ->getQuery()
            ->execute();
    }


}

