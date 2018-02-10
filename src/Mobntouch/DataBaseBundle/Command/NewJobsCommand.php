<?php

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Mobntouch\APIBundle\Classes\Utility;

class NewJobsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('newjobs:list')->setDescription('New jobs');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $time = time();
        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('validated')->equals(true)
                ->field('jobFilters.alerts')->equals(true)
                ->field('settings.notifications.0.email_jobs')->equals(true)
                ->getQuery()
                ->execute()
                ->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        do {

            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('validated')->equals(true)
                    ->field('jobFilters.alerts')->equals(true)
                    ->field('settings.notifications.0.email_jobs')->equals(true)
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($users as $user) {
                $filters = $user->getJobFilters();
                foreach ($filters as $filter) {
                    if ($filter['alerts']) {
                        $this->getMatchedJobes($dm, $user, $filter);
                    }
                }
                $count++;
            }

            $skip++;
        } while ($count < $total);
    }

    private function getMatchedJobes(&$dm, $user, $filter) {
        $search = $filter['filter']['query'];
        if (!isset($search) || count($search) <= 0) {
            return;
        }

        $arrQueries = array();
        $stopKeywords = array("able", "about", "above", "abroad", "according", "accordingly", "across", "actually", "adj", "after", "afterwards", "again", "against", "ago", "ahead", "ain\'t", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "aything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren\'t", "around", "as", "a\'s", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "back", "backward", "backwards", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "behind", "being", "believe", "belw", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "came", "can", "cannot", "cant", "can\'t", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c\'mon", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn\'t", "course", "c\'s", "currently", "dare", "daren\'t", "definitely", "described", "despite", "did", "didn\'t", "different", "directly", "do", "does", "doesn\'t", "doing", "done", "do\'t", "down", "downwards", "during", "each", "edu", "eg", "eight", "eighty", "either", "else", "elsewhere", "end", "ending", "enough", "entirely", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "fairly", "far", "farther", "few", "fewer", "fifth", "first", "five", "folloed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forward", "found", "four", "from", "further", "furthermore", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "had", "hadn\'t", "half", "happens", "hardly", "has", "hasn\'t", "have", "haven\'t", "having", "he", "he\'d", "he\'ll", "hello", "help", "hece", "her", "here", "hereafter", "hereby", "herein", "here\'s", "hereupon", "hers", "herself", "he\'s", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "hunred", "i\'d", "ie", "if", "ignored", "i\'ll", "i\'m", "immediate", "in", "inasmuch", "inc", "inc.", "indeed", "indicate", "indicated", "indicates", "inner", "inside", "insofar", "instead", "ino", "inward", "is", "isn\'t", "it", "it\'d", "it\'ll", "its", "it\'s", "itself", "i\'ve", "just", "k", "keep", "keeps", "kept", "know", "known", "knows", "last", "lately", "later", "latter", "lattely", "least", "less", "lest", "let", "let\'s", "like", "liked", "likely", "likewise", "little", "look", "looking", "looks", "low", "lower", "ltd", "made", "mainly", "make", "makes", "many", "my", "maybe", "mayn\'t", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn\'t", "mine", "minus", "miss", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "must\'t", "my", "myself", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needn\'t", "needs", "neither", "never", "neverf", "neverless", "nevertheless", "new", "next", "nine", "niety", "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "one\'s", "only", "onto", "opposite", "or", "other", "others", "otherwise", "ought", "oughtn\'t", "our", "ours", "ourselves", "out", "outide", "over", "overall", "own", "particular", "particularly", "past", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provided", "provides", "que", "qite", "qv", "rather", "rd", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "respectively", "right", "round", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "severa", "shall", "shan\'t", "she", "she\'d", "she\'ll", "she\'s", "should", "shouldn\'t", "since", "six", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometims", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that\'ll", "thats", "that\'s", "that\'ve", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there\'d", "therefoe", "therein", "there\'ll", "there\'re", "theres", "there\'s", "thereupon", "there\'ve", "these", "they", "they\'d", "they\'ll", "they\'re", "they\'ve", "thing", "things", "think", "third", "thiry", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "till", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "tuly", "try", "trying", "t\'s", "twice", "two", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "v", "value", "various", "versus", "very", "via", "viz", "vs", "want", "wants", "was", "wasn\'t", "way", "we", "we\'d", "welcome", "well", "we\'ll", "went", "were", "we\'re", "weren\'t", "we\'ve", "what", "whatever", "what\'ll", "what\'s", "what\'ve", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whee\'s", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who\'d", "whoever", "whole", "who\'ll", "whom", "whomever", "who\'s", "whose", "why", "wil", "willing", "wish", "with", "within", "without", "wonder", "won\'t", "would", "wouldn\'t", "yes", "yet", "you", "you\'d", "you\'ll", "your", "you\'re", "yours", "yourself", "yourselves", "you\'ve", "zero");
        foreach ($search as $query) {

            if (isset($query['type'])) {
                switch ($query['type']) {
                    case 'type':
                        if ($query['text'] == 'Full Time') {
                            $jobType = 'fullType';
                        } else if ($query['text'] == 'Contract') {
                            $jobType = 'contract';
                        } else if ($query['text'] == 'Co-Founder') {
                            $jobType = 'coFounder';
                        } else if ($query['text'] == 'Intership') {
                            $jobType = 'intern';
                        }
                        break;
                    case 'remote':
                        $isRemoteOk = true;
                        break;
                    default:
                        $texts = explode(' ', $query['text']);
                        $textsCount = count($texts);
                        if ($textsCount > 1) {
                            foreach ($texts as $text)
                                if ($text && !in_array($text, $stopKeywords))
                                    $arrQueries[] = new \MongoRegex("/{$text}/ix");
                        }else {
                            $arrQueries[] = new \MongoRegex("/{$texts[0]}/ix");
                        }
                        break;
                }
            } else {
                $texts = explode(' ', $query['text']);
                $textsCount = count($texts);
                if ($textsCount > 1) {
                    foreach ($texts as $text)
                        if ($text && !in_array($text, $stopKeywords))
                            $arrQueries[] = new \MongoRegex("/{$text}/ix");
                }else {
                    $arrQueries[] = new \MongoRegex("/{$texts[0]}/ix");
                }
            }
        }

        if (!isset($arrQueries) || count($arrQueries) <= 0) {
            return;
        }

        $today = strtotime("00:00:00 UTC"); // UTC
        $lastWeek = strtotime("-1 week", $today);

        $jobs = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->field('publishStatus')->equals('published')
                ->field('search')->all($arrQueries)
                ->field('createDate')->gte($lastWeek)
                ->field('appliedBy.id')->notEqual($user->getId())
                ->field('skippedBy.id')->notEqual($user->getId())
                ->field('createdBy.id')->notEqual($user->getId())
                ->getQuery()
                ->execute()
                ->toArray();

        $totalJobs = count($jobs);
        $jobs = array_slice($jobs, 0, 3);

        $this->sendEmail($dm, $user, $filter, $jobs, $totalJobs);
    }

    private function sendEmail(&$dm, &$user, $filter, $jobs, $totalJobs) {

        switch ($this->getContainer()->getParameter('kernel.environment')) {
            case 'adhoc':
                $baseLink = 'https://www-dev.mobintouch.com';
                $baseURL = 'https://cdn-dev.mobintouch.com';
                break;
            case 'prod':
                $baseLink = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                break;
            default:
                $baseLink = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                break;
        }
        $list = '';
        $firstJob = null;
        foreach ($jobs as $key => $job) {
            if (!$firstJob) {
                $firstJob = $job;
            }
            $company = $dm->getRepository('DataBaseBundle:Company')->findOneBy(array('id' => $job->company['id']));
            if (!$company) {
                continue;
            }

            $showcaseLink = $baseLink . '/showcase/' . $company->getUsername() . '/jobs';
            $jobShowcaseLink = $baseLink . 'showcase/' . $company->getUsername() . '/jobs/' . $job->slug;
            $pictureUrl = $company->getAvatar() && !empty($company->getAvatar()) ? $baseURL . $company->getAvatar() : $baseURL . '/img/mit-default-company-avatar.png';

            $salaryRange = isset($job->minSalary) && isset($job->maxSalary) ? $job->currencySymbol . $job->minSalary . 'K-' . $job->currencySymbol . $job->maxSalary . 'K/yr' : '';
            $equityRange = '';
            if (isset($job->equityMin) && $job->equityMin && isset($job->equityMax) && $job->equityMax) {
                $equityRange = $job->equityMin . '-' . $job->equityMax;
            }

            $list .= '<tr>
                        <td align="left" style="width:100px;vertical-align:top;">
                            <span><a href="' . $showcaseLink . '"><img alt="' . $company->getName() . '" src="' . $pictureUrl . '" style="margin-top:5px;width: 90px; height: 90px;" width="90" height="90"/></a></span>
                        </td>
                        <td align="left" style="font-family: \'Roboto\', Helvetica, Arial, sans-serif; font-size:16px; line-height:20px; font-weight:400; color:#000">
                            <p align="left" style="color:#7e8890;font-size:15px; margin-top:0px;"><a href="' . $showcaseLink . '" style="text-decoration:none;border-style:none;color:#1181d2;font-weight:700;">' . $company->getName() . '</a> · ' . $company->getCountry() . ', ' . $company->getCity() . '</p>';
            if ($company->getDescription()) {
                $truncatedAbout = substr(strip_tags($company->getDescription()), 0, 200);
                $list .= '<p align="left" style="font-size:15px !important; margin-top:0px;">' . $truncatedAbout . ' ...</p>';
            }

            $list .= '<p align="left" style="font-size:15px; margin-top:0px;"><a href="' . $jobShowcaseLink . '" style="text-decoration:none;border-style:none;color:#1181d2;">' . $job->jobTitle . '</a> · ' . isset($job->location['city']) ? $job->location['city'] : '' . ' · ' . $job->jobType . ' · ' . $salaryRange . ' ' . $equityRange . '</p>
                        </td>
                        </tr>
                        <tr height="20">
                            <td></td>
                        </tr>';
        }
        $list = trim(preg_replace('/\s+/', ' ', $list));

        $subject = '';
        if ($totalJobs > 1) {
            $subject = 'New job: ' . $firstJob->jobTitle . ', and ' . ($totalJobs - 1) . ' matches / ' . date('d-m-Y');
        } else {
            $subject = 'New job match: ' . $firstJob->jobTitle . ' / ' . date('d-m-Y');
        }

        $params = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array('email' => $user->getEmail())
                    ),
                    'substitutions' => array(
                        ':logo_link' => $baseLink,
                        ':logo_name_link' => $baseLink,
                        ':saved_filter_name' => $filter['name'],
                        ':job_title' => $job->getJobTitle(),
                        ':jobs_link' => $baseLink . '/jobs',
                        ':loop' => $list
                    )
                ),
            ),
            'from' => array(
                'email' => "noreply@mobintouch.com",
                'name' => "Mobintouch"
            ),
            'subject' => $subject,
            'content' => array(array(
                    'type' => 'text/html',
                    'value' => ' '
                )
            ),
            'template_id' => $this->getContainer()->getParameter('template_new_jobs_list_id'),
            'asm' => array(
                'group_id' => $this->getContainer()->getParameter('group_jobs')
            )
        );
        Utility::sendgrid_mail(json_encode($params));
    }

}
