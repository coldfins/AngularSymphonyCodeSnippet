<?php

/*
 * This class is generated by jignesh
 * Class to make things easy to manage 
 * Othre stuff that helps to manage perform operations
 */

namespace Mobntouch\APIBundle\Classes;

use Mobntouch\DataBaseBundle\Document\Company;
use Symfony\Component\DependencyInjection\Container;
use Mobntouch\APIBundle\Classes\ManageUser;
use Symfony\Component\Process\Process;

/**
 * Manage other stuffs likes string related operations, change formats etc
 *
 * @author Ved Solution
 */
class ManageCompany {

    private $dm;
    private $container;
    private $util;

    function __construct($dm, Container $container) {
        $this->dm = $dm;
        $this->container = $container;
        $this->util = new Utility();
    }

    public function updateUserCompany($user, $company = null, $companyid = null) {
        //get user old company
        if (is_array($user->getCompanyPage()) && array_key_exists('employee', $user->getCompanyPage())) {
            $oldCompanyUserName = $user->getCompanyPage()['employee']['company'];
        } else {
            $oldCompany = $this->dm->createQueryBuilder('DataBaseBundle:Company')
                    ->field('employees.id')->equals($user->getId())
                    ->getQuery()
                    ->getSingleResult();
        }

        if ((!isset($oldCompany) || !$oldCompany) && $oldCompanyUserName) {
            $oldCompany = $this->dm->getRepository('DataBaseBundle:Company')->findOneBy(array('username' => $oldCompanyUserName));
        }
        //end of get user old company
        //
        //get user company
        if ($companyid) {
            $company = $this->dm->getRepository('DataBaseBundle:Company')->findOneBy(array('id' => $companyid));
        } else if ($company) {
            $companyUserName = trim($company);
            $company = $this->dm->createQueryBuilder('DataBaseBundle:Company')
                    ->field('name')->equals(new \MongoRegex("/{$companyUserName}/i"))
                    ->limit(1)
                    ->getQuery()
                    ->getSingleResult();
        }
        //end of get user company
    }

    public function updateAllDocumentsCompanyDetails(Company $company) {
        if (!$company) {
            return;
        }

        $mU = new ManageUser($this->dm, $this->container);

        $this->dm->createQueryBuilder('DataBaseBundle:User')
                ->update()
                ->multiple(true)
                ->field('companyPage.employee.company')->equals($company->getUsername())
                ->field('company')->set($company->getName())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $employees = $company->getEmployees() ? $company->getEmployees() : array();
        foreach ($employees as $employee) {
            $user = $this->dm->getRepository('DataBaseBundle:User')->findOneBy(array('username' => $employee['username']));
            $env = $this->container->get('kernel')->getEnvironment();
            $localPath = $this->container->getParameter('local_console_path');
            $serverPath = $this->container->getParameter('server_console_path');
            if ($env == 'dev') {
                $process = new Process("{$localPath} Job:UpdateProfile {$user->getId()}");
            } else {
                $process = new Process("{$serverPath} Job:UpdateProfile {$user->getId()} --env={$env}");
            }
            $process->disableOutput();
            $process->start();
            //$mU->updateAllDocumentsUserDetails($user);
        }
    }

    public function countCompanyPercentage(Company $company) {
        $percentage = 0;
        $points = 0;
        $total = 0;
        $followerTotal = 0;

        if ($company) {
            $date = time();
            if (date('l', $date) == "Sunday") {
                $date = $date - 86400;
            }
            $start_day_week = strtotime("-30 days", $date);
            $end_day_week = strtotime("-1 days", $date);
            if ($company->companyType == "Advertiser") {
                if ($company->username) {
                    $q1 = $this->dm->createQueryBuilder('DataBaseBundle:Update')
                            ->field('companyUsername')->equals($company->username)
                            ->field('type')->equals(5)
                            ->field('isLike')->equals(false)
                            ->field('date')->range($start_day_week, $end_day_week)
                            ->sort('_id', -1)
                            ->hydrate(false)
                            ->getQuery()
                            ->execute();
                    $post = array();
                    foreach ($q1 as $f) {
                        $post[] = $f;
                    }
                    $total = count($post);
                    if ($total > 0) {
                        $percentage+=$this->container->getParameter('company_ad_posts');
                    }
                }
                if ($company->companyType) {
                    $percentage+=$this->container->getParameter('company_ad_type');
                }if ($company->avatar) {
                    $percentage+=$this->container->getParameter('comapny_ad_avatar');
                }if ($company->size) {
                    $percentage+=$this->container->getParameter('company_ad_size');
                }if ($company->foundedin) {
                    $percentage+=$this->container->getParameter('company_ad_foundedin');
                }if ($company->founders) {
                    $percentage+=$this->container->getParameter('company_ad_founders');
                }if ($company->description) {
                    $percentage+=$this->container->getParameter('company_ad_description');
                }if ($company->website) {
                    $percentage+=$this->container->getParameter('company_ad_website');
                }if ($company->twitter) {
                    $percentage+=$this->container->getParameter('company_ad_twitter');
                }if ($company->linkedIn) {
                    $percentage+=$this->container->getParameter('company_ad_linkedin');
                }if ($company->instagram) {
                    $percentage+=$this->container->getParameter('company_ad_instagram');
                }
            } else {
                if ($company->username) {
                    $q1 = $this->dm->createQueryBuilder('DataBaseBundle:Update')
                            ->field('companyUsername')->equals($company->username)
                            ->field('type')->equals(5)
                            ->field('isLike')->equals(false)
                            ->field('date')->range($start_day_week, $end_day_week)
                            ->sort('_id', -1)
                            ->hydrate(false)
                            ->getQuery()
                            ->execute();
                    $post = array();
                    foreach ($q1 as $f) {
                        $post[] = $f;
                    }
                    $total = count($post);
                    if ($total > 0) {
                        $percentage+=$this->container->getParameter('company_posts');
                    }
                }
                if ($company->companyType) {
                    $percentage+=$this->container->getParameter('company_type');
                }if ($company->companySubType) {
                    $percentage+=$this->container->getParameter('company_subtype');
                }if ($company->avatar) {
                    $percentage+=$this->container->getParameter('comapny_avatar');
                }if ($company->size) {
                    $percentage+=$this->container->getParameter('company_size');
                }if ($company->foundedin) {
                    $percentage+=$this->container->getParameter('company_foundedin');
                }if ($company->founders) {
                    $percentage+=$this->container->getParameter('company_founders');
                }if ($company->description) {
                    $percentage+=$this->container->getParameter('company_description');
                }if ($company->website) {
                    $percentage+=$this->container->getParameter('company_website');
                }if ($company->twitter) {
                    $percentage+=$this->container->getParameter('company_twitter');
                }if ($company->linkedIn) {
                    $percentage+=$this->container->getParameter('company_linkedin');
                }if ($company->instagram) {
                    $percentage+=$this->container->getParameter('company_instagram');
                }
            }
        }

        if ($company) {
            $points = 0.6 * $percentage;
            $points = $points + ($total * 10);
            if ($company->followers) {
                $points = $points + (2 * count($company->followers));
            }
            $companypercentages = $this->dm->createQueryBuilder('DataBaseBundle:Company')
                    ->update()
                    ->multiple(false)
                    ->field('username')->equals($company->username)
                    ->field('companyPercentage')->set($percentage)
                    ->field('companyPoints')->set($points)
                    ->upsert(true)
                    ->getQuery()
                    ->execute();
            $this->dm->persist($company);
            $this->dm->flush();
            $this->dm->clear();
        }

        return ["percentage" => $percentage, "points" => $points];
    }

    private function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }

}
