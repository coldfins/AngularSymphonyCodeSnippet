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

class SearchUpdateCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('search:update')
                ->setDescription('Update Search User and Company Data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');


        // USERS

        $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('validated')->equals(true)
                ->getQuery()
                ->execute();



        foreach ($users as $user) {
            $name = $this->removeAccents($user->getName());
            $lastname = $this->removeAccents($user->getLastname());
            $company = $this->removeAccents($user->getCompany());
            $jobTitle = $this->removeAccents($user->getJobTitle());

//old code commented 2017-03-09 to improve connections search
//            $tempname = explode(" ", $name);
//            $templastname = explode(" ", $lastname);
//            $tempcompany = explode(" ", $company);
//            $tempjobTitle = explode(" ", $jobTitle);
//            $search = array_merge($tempname,$templastname, $tempcompany, $tempjobTitle);
//
//            /*$temp = array(
//                $name." ".$lastname,
//                $name." ".$lastname." ".$company,
//                $name." ".$company,
//                $lastname." ".$company,
//                $lastname." ".$name." ".$company,
//                $lastname." ".$name,
//                $company." ".$name,
//                $company." ".$lastname,
//                $company." ".$name." ".$lastname,
//            );
//
//            $explodeJobTitle = explode(" ", $jobTitle);
//            $search = array_merge($temp, $explodeJobTitle);*/
            //New added code
            $arrSearch = array();

            if (isset($user->name) && !empty($user->name)) {
                $arrSearch[] = $user->name;
            }

            if (isset($user->lastname) && !empty($user->lastname)) {
                $arrSearch[] = $user->lastname;
            }

            if (isset($user->name) && !empty($user->name) && isset($user->lastname) && !empty($user->lastname)) {
                $arrSearch[] = $user->name . ' ' . $user->lastname;
            }

            if (isset($user->city) && !empty($user->city)) {
                $arrSearch[] = $user->city;
            }

            if (isset($user->country) && !empty($user->country)) {
                $arrSearch[] = $user->country;
            }

            if (isset($user->miniResume) && !empty($user->miniResume)) {
                $arrSearch[] = $user->miniResume;
            }

            if (isset($user->jobTitle) && !empty($user->jobTitle)) {
                $arrSearch[] = $user->jobTitle;
            }

            if (isset($user->companyType) && !empty($user->companyType)) {
                $arrSearch[] = $user->companyType;
            }

            if (isset($user->companySubType) && !empty($user->companySubType)) {
                $this->imitateMerge($arrSearch, $user->companySubType);
            }

            if (isset($user->company) && !empty($user->company)) {
                $arrSearch[] = $user->company;
            }

            if (isset($user->currentStatus) && !empty($user->currentStatus)) {
                $arrSearch[] = $user->currentStatus;
            }

            if (isset($user->keywords) && !empty($user->keywords)) {
                $this->imitateMerge($arrSearch, $user->keywords);
            }

            /* Removed caused problem if i search CEO MOBINTOUCH, user who is ceo of other company having email like xyz@mobintouch also came in result 
              if (isset($user->email) && !empty($user->email)) {
              $arrSearch[] = $user->email;
              } */

            if (isset($user->phone) && !empty($user->phone)) {
                $arrSearch[] = $user->phone;
            }

            if (isset($user->imContacts) && !empty($user->imContacts) && array_key_exists(1, $user->imContacts)) {
                $arrSearch[] = $user->imContacts[1];
            }

            if (isset($user->services) && !empty($user->services) && count($user->services) > 0) {
                foreach ($user->services as $service) {
                    $arrSearch[] = $service['service'];
                    $arrSearch[] = $service['experties'];
                    $this->imitateMerge($arrSearch, $service['subServices']);
                }
            }

            if (isset($user->educations) && !empty($user->educations) && count($user->educations) > 0) {
                foreach ($user->educations as $education) {
                    if (isset($education['college'])) {
                        $arrSearch[] = $education['college'];
                    }
                    if (isset($education['degree'])) {
                        $arrSearch[] = $education['degree'];
                    }
                }
            }

            if (isset($user->experiences) && !empty($user->experiences) && count($user->experiences) > 0) {
                foreach ($user->experiences as $experiences) {
                    if (isset($education['description'])) {
                        $arrSearch[] = $experiences['description'];
                    }
                }
            }

            if (isset($user->summary) && !empty($user->summary)) {
                $arrSearch[] = $user->summary;
            }

            if (isset($user->competences) && !empty($user->competences)) {
                $this->imitateMerge($arrSearch, $user->competences);
            }

            $search = array_filter(array_values(array_unique(array_map('strtolower', $arrSearch), SORT_REGULAR)), function($value) {
                return $value !== '';
            });
            //End of new added code section 2017-03-09

            $dm->createQueryBuilder('DataBaseBundle:UserSearch')
                    // Find the Campaign
                    ->update()
                    ->multiple(false)
                    ->field('userID')->equals($user->getId())

                    // Update found Campaign
                    ->field('username')->set($user->getUsername())
                    ->field('name')->set($name)
                    ->field('lastname')->set($lastname)
                    ->field('jobTitle')->set($jobTitle)
                    ->field('company')->set($company)
                    ->field('avatar')->set($user->getAvatar())
                    ->field('responseRate')->set($user->getResponseRate())
                    ->field('totalReceivedEmails')->set($user->getTotalReceivedEmails())
                    ->field('validated')->set($user->getValidated())
                    ->field('search')->set($search)
                    // Options
                    ->upsert(true)
                    ->getQuery()
                    ->execute();

            $dm->createQueryBuilder('DataBaseBundle:User')
                    // Find the Campaign
                    ->update()
                    ->multiple(false)
                    ->field('id')->equals($user->getId())
                    ->field('name')->set($name)
                    ->field('lastname')->set($lastname)
                    ->field('jobTitle')->set($jobTitle)
                    ->field('company')->set($company)
                    ->field('search')->set($search)
                    // Options
                    ->upsert(true)
                    ->getQuery()
                    ->execute();

            $dm->flush();
            $dm->clear(); // Detaches all objects from Doctrine!
        }


        // COMPANIES

        $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->getQuery()
                ->execute();

        foreach ($companies as $company) {

            //Old Searching Code
            /* $name = $this->removeAccents($company->getName());
              //$lastname = $this->removeAccents($user->getLastname());
              //$company = $this->removeAccents($user->getCompany());
              //$jobTitle = $this->removeAccents($user->getJobTitle());

              $search = array($name, $company->getCompanyType()); */

            $arrSearch = array();
            $name = $this->removeAccents($company->getName());
            $username = $this->removeAccents($company->getUsername());
            $arrSearch = array_merge(explode(" ", $name), explode(" ", $username));



            if (isset($company->city) && !empty($company->city)) {
                $arrSearch[] = $company->city;
            }

            if (isset($company->country) && !empty($company->country)) {
                $arrSearch[] = $company->country;
            }

            if (isset($company->basedCountry) && !empty($company->basedCountry)) {
                $arrSearch[] = $company->basedCountry;
            }

            if (isset($company->companyType) && !empty($company->companyType)) {
                $arrSearch[] = $company->companyType;
            }

            if (isset($company->founders) && !empty($company->founders)) {
                $founders = explode(",", $company->founders);
                $this->imitateMerge($arrSearch, $founders);
            }

            $search = array_filter(array_values(array_unique(array_map('strtolower', $arrSearch), SORT_REGULAR)), function($value) {
                return $value !== '';
            });

            $dm->createQueryBuilder('DataBaseBundle:Company')
                    // Find the Campaign
                    ->update()
                    ->multiple(false)
                    ->field('id')->equals($company->getId())
                    ->field('search')->set($search)
                    // Options
                    ->upsert(true)
                    ->getQuery()
                    ->execute();

            $dm->createQueryBuilder('DataBaseBundle:CompanySearch')
                    // Find the Campaign
                    ->update()
                    ->multiple(false)
                    ->field('companyID')->equals($company->getId())

                    // Update found Campaign
                    ->field('username')->set($company->getUsername())
                    ->field('name')->set($name)
                    ->field('size')->set($company->getSize())
                    ->field('companyType')->set($company->getCompanyType())
                    ->field('companySubType')->set($company->getCompanySubType())
                    ->field('avatar')->set($company->getAvatar())
                    ->field('search')->set($search)

                    // Options
                    ->upsert(true)
                    ->getQuery()
                    ->execute();

            $dm->flush();
            $dm->clear(); // Detaches all objects from Doctrine!
        }
    }

    private function imitateMerge(&$array1, &$array2) {
        foreach ($array2 as $i) {
            $array1[] = $i;
        }
    }

    private function removeAccents($str) {

        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');

        return str_replace($a, $b, $str);
    }

}
