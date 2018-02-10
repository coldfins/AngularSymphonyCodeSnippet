<?php

namespace BackOfficeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ODM\MongoDB\Query\Query;
# Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;     // 404
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;   // 400
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException; // 403
# DOCUMENTS
use DataBaseBundle\Document\Company;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

class userController extends Controller {

    public function usersAction(Request $request) {
        $parameters = array("title" => "Users", "active" => "users");


        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $sorting = $request->query->get('sorting', array());

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $queryBuilder = $dm->createQueryBuilder('DataBaseBundle:User');
        $adapter = new DoctrineODMMongoDBAdapter($queryBuilder);
        $adapter->getQueryBuilder()->select(array('id', 'avatar', 'username', 'name', 'lastname'));

        if (array_key_exists('username', $request->query->all())) {
            //$adapter->getQueryBuilder()->field('username')->equals(new \MongoRegex('/.*' . $request->query->get('username') . '.*/i'));
            $adapter->getQueryBuilder()->addOr(
                    array(
                        'username' => new \MongoRegex('/.*' . $request->query->get('username') . '.*/i')
                    )
            );
            $adapter->getQueryBuilder()->addOr(
                    array(
                        'name' => new \MongoRegex('/.*' . $request->query->get('username') . '.*/i')
                    )
            );
            $adapter->getQueryBuilder()->addOr(
                    array(
                        'lastname' => new \MongoRegex('/.*' . $request->query->get('username') . '.*/i')
                    )
            );
            if ($request->query->get('username') == 'null') {
                $adapter->getQueryBuilder()->addOr(
                        array(
                            'username' => null
                        )
                );
            }
        }
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);

        if (array_key_exists('page', $request->query->all()) && $pagerfanta->getNbPages() >= $request->query->get('page')) {
            $pagerfanta->setCurrentPage($page);
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
            if (isset($queries['username']) && $request->query->get('username') != $queries['username']) {
                $pagerfanta->setCurrentPage(1);
            }
        }

        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);
        $html = $view->render($pagerfanta, function($page) {
            if (isset($_REQUEST['username']))
                return '/users?page=' . $page . "&username=" . $_REQUEST['username'];
            else
                return '/users?page=' . $page;
        }, $options);

        return $this->render('user/users.html.twig', array_merge($parameters, array("result" => $pagerfanta->getCurrentPageResults()->toArray(), "paginator" => $html, "haveToPaginate" => $pagerfanta->haveToPaginate(), "currentPage" => $pagerfanta->getCurrentPage())));
    }

    public function removeUserByUserNameAction(Request $request) {
        /*
          $username = $request->request->get('user_name');
          $dm = $this->get('doctrine_mongodb.odm.document_manager');
          $user = $dm->createQueryBuilder('DataBaseBundle:User')
          ->field('username')->equals($username)
          ->limit(1)
          ->getQuery()
          ->getSingleResult();
         */
        /*
          $qM = $dm->createQueryBuilder('DataBaseBundle:Mail');
          $qM = $qM
          ->field('fromID')->equals($user->getId())
          ->addOr(
          $qM->expr()->field('toID')->equals($user->getId())
          )
          ->getQuery()
          ->getSingleResult();
         */
        /*
          echo "<pre>";
          print_r($mail);
          die;
         * 
         */
        set_time_limit(3600);
        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        if ($request->isMethod('POST') && $request->request->has('user_name')) {
            $username = $request->request->get('user_name') == '' ? null : $request->request->get('user_name');
            //Get user by username
            $user = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('username')->equals($username)
                    ->limit(1)
                    ->getQuery()
                    ->getSingleResult();

            if (!$user) {
                $this->get('session')->getFlashBag()->add(
                        'notice', array(
                    'alert' => 'danger',
                    'title' => 'Error!',
                    'message' => 'User not found!'
                        )
                );
                return $this->redirect($request->headers->get('referer'));
            }


//Get all companies in which user is admin
            $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->field('administrators.username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();

            $sCompanies = '';
            foreach ($companies as $company) {
                if (count($company->getAdministrators()) <= 1) {
                    $sCompanies .= ', ' . $company->getName();
                }
            }

            if (!empty($sCompanies)) {
                $this->get('session')->getFlashBag()->add(
                        'notice', array(
                    'alert' => 'danger',
                    'title' => 'Error!',
                    'message' => 'User is only single admin in ' . substr($sCompanies, 2) . ' company page add another one to remove user!'
                        )
                );
                return $this->redirect($request->headers->get('referer'));
            }




            /*
              if (count($company->getAdministrators()) == 1) {
              $this->get('session')->getFlashBag()->add(
              'notice', array(
              'alert' => 'danger',
              'title' => 'Error!',
              'message' => 'User is only single admin in ' . $company->getName() . ' company page add another one to remove user!'
              )
              );
              return $this->redirect($request->headers->get('referer'));
              } else if (count($company->getAdministrators()) > 1) {
              $dm->createQueryBuilder('DataBaseBundle:Company')
              ->update()
              ->multiple(true)
              ->field('administrators')->pull(array('username' => $user->getUsername()))
              ->field('employees')->pull(array('username' => $user->getUsername()))
              ->field('followers')->pull(array('username' => $user->getUsername()))
              ->getQuery()
              ->execute();
              }
             */


//Update Company remove user form administartos, employees and followers collection
            $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->update()
                    ->multiple(true)
                    ->field('administrators')->pull(array('username' => $user->getUsername()))
                    ->field('employees')->pull(array('username' => $user->getUsername()))
                    ->field('followers')->pull(array('username' => $user->getUsername()))
                    ->getQuery()
                    ->execute();

//Remove all mails where user is sender or receiver
            /*
              $qM = $dm->createQueryBuilder('DataBaseBundle:Mail');
              $qM->remove()
              ->field('fromID')->equals($user->getId())
              ->addOr(
              $qM->expr()->field('toID')->equals($user->getId())
              )
              ->getQuery()
              ->execute();
             */

            $qM = $dm->createQueryBuilder('DataBaseBundle:Mail');
            $qM->remove();
            $qM->addOr($qM->expr()->field('fromID')->equals($user->getId()));
            $qM->addOr($qM->expr()->field('toID')->equals($user->getId()));
            $qM->getQuery()->execute();



            /*
             * Old Code May Take More Time So Created New
              //Remove all offers that was creted by user
              $dm->createQueryBuilder('DataBaseBundle:Offer')
              ->remove()
              ->field('username')->equals($user->getUsername())
              ->getQuery()
              ->execute();

              //Getting all offers to get all offerIDs
              $offers = $dm->createQueryBuilder('DataBaseBundle:Offer')
              ->getQuery()
              ->execute()
              ->toArray();

              //Get all offerIds from offer cursor
              $arrOffers = array();
              foreach ($offers as $offer) {
              $arrOffers[] = $offer->getId();
              }

              //Remove OfferReply where offerReply by user going to be delete or offer not available
              $qOR = $dm->createQueryBuilder('DataBaseBundle:OfferReply');
              $qOR->remove()
              ->addOr(
              $qOR->expr()->field('offerID')->notIn($arrOffers)
              )->addOr(
              $qOR->expr()->field('username')->equals($user->getUsername())
              )
              ->getQuery()
              ->execute();



              foreach ($arrOffers as $value) {

              }
              $qqqq = $dm->createQueryBuilder('DataBaseBundle:OfferReply')
              ->field('offerID')->equals('5746d2460ddfe1400f00003b')
              ->sort('date', -1)
              ->getQuery()
              ->execute()
              ->toArray();

              echo "<pre>";
              print_r(reset($qqqq)->getDate());
              die;
             */


//Get All Offer Of User [Offers Created By User]
            $userOffers = $dm->createQueryBuilder('DataBaseBundle:Offer')
                    ->field('username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();

            $arrUserOfferIds = array();
            foreach ($userOffers as $offer) {
                $arrUserOfferIds[] = $offer->getId();
            }

//Delete Offer Of User
            $dm->createQueryBuilder('DataBaseBundle:Offer')
                    ->remove()
                    ->field('id')->in($arrUserOfferIds)
                    ->getQuery()
                    ->execute();

//Delete Offer Replies On User Offer
            $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                    ->remove()
                    ->field('offerID')->in($arrUserOfferIds)
                    ->getQuery()
                    ->execute();

//Get All User Offer Reply [Offers By Other Users]
            $userOffersReplies = $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                    ->field('username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();

            $arrUserReplyesIds = array();
            foreach ($userOffersReplies as $userOfferReply) {
                $arrUserReplyesIds[] = $userOfferReply->getOfferID();
            }

//Removed All Offer Reply Of User
            $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                    ->remove()
                    ->field('username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();

//Update Offer Replies Count On Which User Replied
            foreach (array_count_values($arrUserReplyesIds) as $offerId => $count) {
                $dm->createQueryBuilder('DataBaseBundle:Offer')
                        ->update()
                        ->field('id')->equals($offerId)
                        ->field('replies')->set($count)
                        ->getQuery()
                        ->execute();
            }

            /*
              //Remove all Updates that was posted by user
              $dm->createQueryBuilder('DataBaseBundle:Update')
              ->remove()
              ->field('username')->equals($user->getUsername())
              ->getQuery()
              ->execute();

              $userLikedComments = $dm->createQueryBuilder('DataBaseBundle:Update');
              $userLikedComments
              ->field('action')->equals('userPost')
              ->addOr(
              $userLikedComments->expr()->field('liked.username')->equals(array('username' => $user->getUsername()))
              )->addOr(
              $userLikedComments->expr()->field('comments.username')->equals(array('username' => $user->getUsername()))
              )
              ->getQuery()
              ->execute();

              $dm->createQueryBuilder('DataBaseBundle:Update')
              ->update()
              ->multiple(true)
              ->field('liked')->pull(array('username' => $user->getUsername()))
              ->field('comments')->pull(array('username' => $user->getUsername()))
              ->getQuery()
              ->execute();

              foreach ($userLikedComments as $userLikedComment) {
              $userLikedComment->setLikesCounter(count($userLikedComment->getLiked()));
              $userLikedComment->setCommentsCounter(count($userLikedComment->getComments()));
              }

             */

//Remove all Updates that was posted by user
            $dm->createQueryBuilder('DataBaseBundle:Update')
                    ->remove()
                    ->field('username')->equals($user->getUsername())
                    ->field('type')->in(array(1, 2, 3, 7))
                    ->getQuery()
                    ->execute();

            $dm->createQueryBuilder('DataBaseBundle:Update')
                    ->remove()
                    ->field('inTouchUsername')->equals($user->getUsername())
                    ->field('type')->in(array(1, 2, 3, 7))
                    ->getQuery()
                    ->execute();


//Get All Upadtes in which user post comment or liked by user
            $qLC = $dm->createQueryBuilder('DataBaseBundle:Update');
            $qLC = $qLC->field('liked.username')->equals($user->getUsername())
                    ->addOr(
                            $qLC->expr()->field('comments.username')->equals($user->getUsername())
                    )
                    ->getQuery()
                    ->execute()
                    ->toArray();

//Remove Likes And Comment
            $dm->createQueryBuilder('DataBaseBundle:Update')
                    ->update()
                    ->multiple(true)
                    ->field('liked')->pull(array('username' => $user->getUsername()))
                    ->field('comments')->pull(array('username' => $user->getUsername()))
                    ->getQuery()
                    ->execute();

//Refresh Persistant Data to update likesAndComment Counter
            $dm->flush();
            $dm->clear();

//Reset Likes And Comments Counter
            foreach (array_keys($qLC) as $updateId) {
                $update = $dm->createQueryBuilder('DataBaseBundle:Update')
                        ->field('id')->equals($updateId)
                        ->getQuery()
                        ->getSingleResult();
                $update->setLikesCounter(count($update->getLiked()));
                $update->setCommentsCounter(count($update->getComments()));
            }

//Remove UserSearch Details
            $dm->createQueryBuilder('DataBaseBundle:UserSearch')
                    ->remove()
                    ->field('username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();

//Remove User Activity Details
            $dm->createQueryBuilder('DataBaseBundle:UsersActivity')
                    ->remove()
                    ->field('username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();



            $qU = $dm->createQueryBuilder('DataBaseBundle:User')
                            ->update()
                            ->multiple(true)
                            ->field('alerts')->pull(array('username' => array('$in' => array_merge(array($user->getUsername(), $user->getId()), $arrUserOfferIds))));

            if ($arrUserOfferIds)
                $qU->field('favoriteOffers')->pull(array('id' => array('$in' => $arrUserOfferIds)));

            $qU->field('followers')->pull(array('username' => $user->getUsername()));
            $qU->field('following')->pull(array('username' => $user->getUsername()));
            $qU->field('iVisited')->pull(array('username' => $user->getUsername()));
            $qU->field('inTouch')->pull(array('username' => $user->getUsername()));

            if ($arrUserOfferIds)
                $qU->field('repliedOffers')->pull(array('offerID' => array('$in' => $arrUserOfferIds)));

            $qU->getQuery()->execute();

            $dm->flush();
            $dm->clear();

            $users = $dm->createQueryBuilder('DataBaseBundle:User')
//->remove()
//->field('id')->equals('559d069462d693023e8b4567')
                    ->getQuery()
                    ->execute();


//echo "<pre>";
//print_r(count($users));
//die;
//$index = 0;
            foreach ($users as $u) {
//$index++;
                $this->delete($u->getWhoVisitedMe(), 'username', $user->getUsername(), $u);


                $countSentMails = $dm->createQueryBuilder('DataBaseBundle:Mail')
                        ->field('fromID')->equals($u->getId())
                        ->getQuery()
                        ->execute()
                        ->count();

                $countRepliedMails = $dm->createQueryBuilder('DataBaseBundle:Mail')
                        ->field('fromID')->notEqual($u->getId())
                        ->field('senderID')->equals($u->getId())
                        ->field('rated')->equals(true)
                        ->getQuery()
                        ->execute()
                        ->count();

                $countReceivedMails = $dm->createQueryBuilder('DataBaseBundle:Mail')
                        ->field('fromID')->notEqual($u->getId())
                        ->field('senderID')->equals($u->getId())
                        ->getQuery()
                        ->execute()
                        ->count();

                $countEmailsNotifications = $dm->createQueryBuilder('DataBaseBundle:Mail')
                        ->field('toCurrentID')->equals($u->getId())
                        ->field('read')->equals(false)
                        ->getQuery()
                        ->execute()
                        ->count();

                $u->setTotalSentEmails($countSentMails);
                $u->setTotalRepliedEmails($countRepliedMails);
                $u->setTotalReceivedEmails($countReceivedMails);
                $u->setEmailsNotifications($countEmailsNotifications);

                $alertCount = 0;
                if ($u->getAlerts()) {
                    foreach ($u->getAlerts() as $alert) {
                        if (!$alert['read'])
                            $alertCount++;
                    }
                }

                $u->setAlertsNotifications($alertCount);

                $u->setInTouchCounter(count($u->getInTouch()));

                $dm->flush();
                $dm->clear();
            }

            $dm->createQueryBuilder('DataBaseBundle:User')
                    ->remove()
                    ->field('username')->equals($user->getUsername())
                    ->getQuery()
                    ->execute();


//echo $index . " Records updated...";
//die;
/////////////////////////////////////////////
//Table Not In Used Dont make code for this 
////////////////////////////////////////////
//To Remove InTouch
            /*
              $inTouch = $dm->createQueryBuilder('DataBaseBundle:inTouch');
              $inTouch = $inTouch->remove()
              ->addOr(
              $inTouch->expr()->field('userID1')->equals($user->getId())
              )->addOr(
              $inTouch->expr()->field('userID2')->equals($user->getId())
              )
              ->getQuery()
              ->execute();
             */
//Get In Touches
            /*
             * 
              $inTouch = $dm->createQueryBuilder('DataBaseBundle:inTouch');
              $inTouch = $inTouch
              ->addOr(
              $inTouch->expr()->field('userID1')->equals($user->getId())
              )->addOr(
              $inTouch->expr()->field('userID2')->equals($user->getId())
              )
              ->getQuery()
              ->execute()
              ->toArray();
             */


//To remove All UsersActivity
            /*
              $dm->createQueryBuilder('DataBaseBundle:UsersActivity')
              ->remove()
              ->field('username')->equals($user->getUsername())
              ->getQuery()
              ->execute();
             */
//To Get All UsersActivity
            /*
              $userActivity = $dm->createQueryBuilder('DataBaseBundle:UsersActivity')
              ->field('username')->equals($user->getUsername())
              ->getQuery()
              ->execute()
              ->toArray();
             */


//To Remove all UserSearch
            /*
              $dm->createQueryBuilder('DataBaseBundle:UserSearch')
              ->remove()
              ->field('userID')->equals($user->getId())
              ->getQuery()
              ->execute();
             */
//to get all UserSearch
            /*
              $userSearch = $dm->createQueryBuilder('DataBaseBundle:UserSearch')
              ->field('userID')->equals($user->getId())
              ->getQuery()
              ->execute()
              ->toArray();
             */



//To Remove Updates
            /*
              $update = $dm->createQueryBuilder('DataBaseBundle:Update')
              ->update()
              ->multiple(true)
              ->field('liked')->pull(array('userID' => $user->getId()))
              ->getQuery()
              ->execute();
             */
//To Get All Updates
            /*
              $update = $dm->createQueryBuilder('DataBaseBundle:Update')
              ->field('liked.userID')->equals($user->getId())
              ->getQuery()
              ->execute()
              ->toArray();
             */




            /*
              $dm->createQueryBuilder('DataBaseBundle:User')
              ->update()
              ->field("inTouchCounter")->inc(-1)
              ->field('inTouch')->pull(array('username' => $user->getUsername()))
              ->field('inTouch.username')->equals($user->getUsername())
              ->getQuery()
              ->execute();
             */


            /*
              $dm->createQueryBuilder('DataBasBundle:User')
              ->remove()
              ->field('id')->equals($user->getId())
              ->getQuery()
              ->execute();

             */
            /*
              $u = $dm->createQueryBuilder('DataBaseBundle:User');
              $u = $u->select('inTouch')
              ->getQuery()
              ->execute()
              ->count();

             * 
             */
            $dm->flush();
            $dm->clear();


            $this->get('session')->getFlashBag()->add(
                    'notice', array(
                'alert' => 'success',
                'title' => 'Success!',
                'message' => 'User ' . $user->getName() . ' ' . $user->getLastname() . ' removed successfully.'
                    )
            );


//echo "<pre>";
//print_r($u);
//die;
        }
        return $this->redirect($request->headers->get('referer'));
    }

    public function checkUserNameAction(Request $request) {
        $username = $request->request->get('user_name');
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $user = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('username')->equals($username)
                ->limit(1)
                ->getQuery()
                ->getSingleResult();
        return new JsonResponse(array('valid' => $user ? true : false));
    }

    public function updateUserSearchAction(Request $request) {

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->getQuery()
                ->execute();
        foreach ($users as $user) {
            $this->updateUserSearch($dm, $user);
        }

        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'User searching details updated successfully.'
                )
        );
        return $request->headers->get('referer') ? $this->redirect($request->headers->get('referer')) : $this->redirect($this->generateUrl('settings'));
    }

    public function updateUserCompanyPageAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('companyPage')->exists(true)
                ->getQuery()
                ->execute();

        foreach ($users as $user) {

            $companyPage = $user->getCompanyPage();


            if ($companyPage && is_array($companyPage) && array_key_exists('administrator', $companyPage) && !is_array($companyPage['administrator'])) {
                $administrators = array(
                    'company' => $user->getCompanyPage()['administrator']
                );
                $companyPage['administrator'] = $administrators;
            } else if ($companyPage && is_array($companyPage) && count($companyPage) > 1 && !array_key_exists('administrator', $companyPage) && !array_key_exists('employee', $companyPage)) {
                if (is_array($companyPage[0]) && array_key_exists('company', $companyPage[0])) {
                    $companyPage['administrator'] = $companyPage[0];
                    unset($companyPage[0]);
                } else if (!is_array($companyPage[0])) {
                    $companyPage['administrator'] = array(
                        'company' => $companyPage[0]
                    );
                    unset($companyPage[0]);
                }

                if (is_array($companyPage[1]) && is_array($companyPage[1]) && array_key_exists('company', $companyPage[1])) {
                    $companyPage['employee'] = $companyPage[1];
                    unset($companyPage[1]);
                } else if (!is_array($companyPage[0])) {
                    $companyPage['employee'] = array(
                        'company' => $companyPage[0]
                    );
                    unset($companyPage[1]);
                }
            } else if ($companyPage && is_array($companyPage) && count($companyPage) == 1 && !array_key_exists('administrator', $companyPage) && !array_key_exists('employee', $companyPage)) {
                $companyPage['employee'] = $companyPage[0];
                unset($companyPage[0]);
            }

            $dm->createQueryBuilder('DataBaseBundle:User')
                    ->update()
                    ->multiple(false)
                    ->field('id')->equals($user->getId())
                    ->field('companyPage')->set($companyPage)
                    ->upsert(false)
                    ->getQuery()
                    ->execute();
            $dm->flush();
            $dm->clear();
        }


        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'Users company page details updated successfully.'
                )
        );

        return $this->redirect($this->generateUrl('settings'));
    }

    public function updateSelectedUserProfilePointsAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $exceptionalUsers = array("dapreyao", "guillaumegabriel");
        if (array_key_exists("users", $request->request->all())) {
            $exceptionalUsers = array_unique(array_merge($exceptionalUsers, $request->request->get('users')), SORT_REGULAR);
        }

        /*

          $dm->createQueryBuilder('DataBaseBundle:User')
          ->update()
          ->multiple(true)
          ->field('username')->in($exceptionalUsers)
          ->field('profilePoints')->set(0)
          ->upsert(false)
          ->getQuery()
          ->execute();
         */

        foreach ($exceptionalUsers as $exceptionalUser) {
            $user = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('username')->equals($exceptionalUser)
                    ->limit(1)
                    ->getQuery()
                    ->getSingleResult();
            if ($user) {
                $user->setOldProfilePoints($user->getProfilePoints() ? $user->getProfilePoints() : 0);
                $user->setProfilePoints(0);
            }
        }

        $dm->flush();
        $dm->clear();

        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'Users profile points updated successfully.'
                )
        );

        return $this->redirect($this->generateUrl('settings'));
    }

    public function updateInBusinessRelationCounterAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->getQuery()
                ->execute();


        foreach ($users as $user) {
            $inBusinessRelationCounter = $dm->createQueryBuilder('DataBaseBundle:Invitation')
                    ->field('isAlreadyExists')->equals(true)
                    ->field('userID')->equals($user->getId())
                    ->getQuery()
                    ->execute()
                    ->count();

            $dm->createQueryBuilder('DataBaseBundle:User')
                    ->update()
                    ->multiple(false)
                    ->field('id')->equals($user->getId())
                    ->field('inBusinessRelationCounter')->set($inBusinessRelationCounter)
                    ->upsert(false)
                    ->getQuery()
                    ->execute();

            $dm->flush();
            $dm->clear();
        }

        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'Users business relation numbers has been updated successfully.'
                )
        );

        return $this->redirect($this->generateUrl('settings'));
    }

    public function delete($array, $key, $value, &$u, $index = 0) {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $ar = $u->getWhoVisitedMe();
                unset($ar[$index]);
                $u->setWhoVisitedMe($ar);
//$u->setWhoVisitedMe(unset($u->getWhoVisitedMe()[$index]));
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->delete($subarray, $key, $value, $u, $index++));
            }
        }
        return $results;
    }

    private function updateUserSearch($dm, $user) {
        set_time_limit(3600);

        $name = $this->removeAccents($user->getName());
        $lastname = $this->removeAccents($user->getLastname());
        $company = $this->removeAccents($user->getCompany());
        $jobTitle = $this->removeAccents($user->getJobTitle());

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

    public function updateUserExperienceCompanyAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->getQuery()
                ->execute();
        foreach ($users as $key => $user) {
            if (isset($user->experiences) && is_array($user->experiences) && count($user->experiences) > 0) {
                foreach ($user->experiences as $index => $experience) {
                    if (isset($experience['company']) && !isset($experience['companyid'])) {
                        $companyName = $experience['company'];
                        $company = $dm->createQueryBuilder('DataBaseBundle:Company')
                                ->field('name')->equals(new \MongoRegex("/{$companyName}/i"))
                                ->limit(1)
                                ->getQuery()
                                ->getSingleResult();

                        if ($company) {
                            $user->experiences[$index]['companyid'] = $company->getId();
                            $user->experiences[$index]['companyUsername'] = $company->getUsername();
                            $user->experiences[$index]['logo'] = $company->getAvatar();
                        }
                    }
                }
                $dm->flush();
                $dm->clear();
            }
        }
        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'User exprience details updated successfully.'
                )
        );
        return $request->headers->get('referer') ? $this->redirect($request->headers->get('referer')) : $this->redirect($this->generateUrl('settings'));
    }

    public function updateUserCustomServicesAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $count = 0;
        $skip = 0;
        $limit = 10;

        $qb = $dm->createQueryBuilder('DataBaseBundle:User');
        $qb->addOr($qb->expr()->field('buyTraffic.valid')->equals(true));
        $qb->addOr($qb->expr()->field('sellTraffic.valid')->equals(true));
        $total = $qb->getQuery()->execute()->count();

        do {

            $qb = $dm->createQueryBuilder('DataBaseBundle:User');
            $qb->addOr($qb->expr()->field('buyTraffic.valid')->equals(true));
            $qb->addOr($qb->expr()->field('sellTraffic.valid')->equals(true));
            $users = $qb
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($users as $key => $user) {
                $count++;
                $searches = $user->getSearch();
                $customServices = array();
                if (isset($user->buyTraffic)) {
                    foreach ($user->buyTraffic as $buyTraffic) {
                        $service = array(
                            'name' => 'offers',
                            'action' => 'buy',
                            'market' => 'Mobile Traffic',
                            'values' => array()
                        );

                        if (isset($buyTraffic['gender']) && $buyTraffic['gender'] != 'both') {
                            $service['values'][] = $buyTraffic['gender'];
                        }

                        foreach ($buyTraffic['interests'] as $key => $value) {
                            $service['values'][] = $value['text'];
                        }

                        foreach ($buyTraffic['countries'] as $key => $country) {
                            $service['values'][] = $this->getCountryName($country);
                        }

                        foreach ($buyTraffic['pricing'] as $key => $value) {
                            foreach ($value as $k => $v) {
                                if ($v) {
                                    $service['values'][] = $k;
                                }
                            }
                        }

                        foreach ($buyTraffic['platform'] as $key => $platforms) {
                            foreach ($platforms as $k => $v) {
                                if ($v) {
                                    $service['values'][] = $k;
                                }
                            }
                        }

                        if (isset($buyTraffic['otherPlatform'])) {
                            $service['values'][] = $buyTraffic['otherPlatform'];
                        }

                        if (isset($buyTraffic['incentivized']) && $buyTraffic['incentivized']) {
                            $service['values'][] = 'Incentivized';
                        }

                        if (isset($buyTraffic['nonincentivized']) && $buyTraffic['nonincentivized']) {
                            $service['values'][] = 'Non Incentivized';
                        }
                        $service['values'] = array_values(array_filter($service['values']));
                        $customServices[] = $service;
                        $searches[] = $service['name'];
                        $searches[] = $service['action'];
                        $searches[] = $service['market'];
                        $searches = array_merge($searches, $service['values']);
                    }
                }
                if (isset($user->sellTraffic)) {
                    foreach ($user->sellTraffic as $sellTraffic) {
                        $service = array(
                            'name' => 'offers',
                            'action' => 'sell',
                            'market' => 'Mobile Traffic',
                            'values' => array()
                        );

                        foreach ($sellTraffic['platform'] as $key => $platforms) {
                            foreach ($platforms as $k => $v) {
                                if ($v) {
                                    $service['values'][] = $k;
                                }
                            }
                        }

                        if (isset($sellTraffic['otherPlatform'])) {
                            $service['values'][] = $sellTraffic['otherPlatform'];
                        }

                        foreach ($sellTraffic['countries'] as $key => $country) {
                            $country = $this->getCountryName($country);
                            if (isset($country) && $country !== NULL) {
                                $service['values'][] = $country;
                            }
                        }

                        foreach ($sellTraffic['pricing'] as $key => $value) {
                            foreach ($value as $k => $v) {
                                if ($v && $k !== NULL) {
                                    $service['values'][] = $k;
                                }
                            }
                        }

                        if (isset($sellTraffic['incentivized']) && $sellTraffic['incentivized']) {
                            $service['values'][] = 'Incentivized';
                        }

                        if (isset($sellTraffic['nonincentivized']) && $sellTraffic['nonincentivized']) {
                            $service['values'][] = 'Non Incentivized';
                        }

                        if (isset($sellTraffic['adformat'])) {
                            foreach ($sellTraffic['adformat'] as $key => $value) {
                                $service['values'][] = $value;
                            }
                        }

                        if (isset($sellTraffic['targeting'])) {
                            foreach ($sellTraffic['targeting'] as $key => $value) {
                                $service['values'][] = $value;
                            }
                        }

                        if (isset($sellTraffic['userType'])) {
                            foreach ($sellTraffic['userType'] as $key => $value) {
                                $service['values'][] = $value;
                            }
                        }

                        $service['values'] = array_values(array_filter($service['values']));
                        $customServices[] = $service;
                        $searches[] = $service['name'];
                        $searches[] = $service['action'];
                        $searches[] = $service['market'];
                        $searches = array_merge($searches, $service['values']);
                    }
                }

                $search = array_filter(array_values(array_unique(array_map('strtolower', $searches), SORT_REGULAR)), function($value) {
                    return $value !== '';
                });

                $user->setCustomServices($customServices);
                $user->setSearch($search);
            }
            $dm->flush();
            $dm->clear();
            $skip++;
        } while ($count < $total);


        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'User custom service details updated successfully.'
                )
        );
        return $request->headers->get('referer') ? $this->redirect($request->headers->get('referer')) : $this->redirect($this->generateUrl('settings'));
    }

    public function addUserDefaultRolesAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web');
        $filePath = $webPath . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Role-list.xlsx';
        echo $filePath;
        $xlsx = new \BackOfficeBundle\Classes\SimpleXLSX($filePath);

        if ($xlsx->success()) {
            foreach ($xlsx->rows() as $row) {
                $q = $dm->createQueryBuilder('DataBaseBundle:User');
                $q->addOr($q->expr()->field("companyType")->equals($row[0]));
                $q->addOr($q->expr()->field("companySubType")->equals($row[0]));
                $noOfTimeUsed = $q->getQuery()
                        ->execute()
                        ->count();

                //echo $row[0] . " no of time used => " . $noOfTimeUsed . "\n<br>";

                $userRoleCount = $dm->createQueryBuilder('DataBaseBundle:UserRoles')
                        ->field("name")->equals($row[0])
                        ->getQuery()
                        ->execute()
                        ->count();
                if ($userRoleCount == 0) {
                    $dm->createQueryBuilder('DataBaseBundle:UserRoles')
                            ->insert()
                            ->field("name")->set($row[0])
                            ->field('noOfTimeUsed')->set($noOfTimeUsed)
                            ->getQuery()
                            ->execute();
                } else {
                    $dm->createQueryBuilder('DataBaseBundle:UserRoles')
                            ->update()
                            ->field("name")->equals($row[0])
                            ->field('noOfTimeUsed')->set($noOfTimeUsed)
                            ->getQuery()
                            ->execute();
                }
                $dm->flush();
                $dm->clear();
            }
        } else {
            echo 'xlsx error: ' . $xlsx->error();
        }

        $this->get('session')->getFlashBag()->add(
                'notice', array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'User roles added successfully.'
                )
        );
        return $request->headers->get('referer') ? $this->redirect($request->headers->get('referer')) : $this->redirect($this->generateUrl('settings'));
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

    private function getCountryName($code) {
        if (!isset($code) || empty($code)) {
            return null;
        }

        $code = strtoupper($code);
        if ($code == 'AF')
            return 'Afghanistan';
        if ($code == 'AX')
            return 'Aland Islands';
        if ($code == 'AL')
            return 'Albania';
        if ($code == 'DZ')
            return 'Algeria';
        if ($code == 'AS')
            return 'American Samoa';
        if ($code == 'AD')
            return 'Andorra';
        if ($code == 'AO')
            return 'Angola';
        if ($code == 'AI')
            return 'Anguilla';
        if ($code == 'AQ')
            return 'Antarctica';
        if ($code == 'AG')
            return 'Antigua and Barbuda';
        if ($code == 'AR')
            return 'Argentina';
        if ($code == 'AM')
            return 'Armenia';
        if ($code == 'AW')
            return 'Aruba';
        if ($code == 'AU')
            return 'Australia';
        if ($code == 'AT')
            return 'Austria';
        if ($code == 'AZ')
            return 'Azerbaijan';
        if ($code == 'BS')
            return 'Bahamas the';
        if ($code == 'BH')
            return 'Bahrain';
        if ($code == 'BD')
            return 'Bangladesh';
        if ($code == 'BB')
            return 'Barbados';
        if ($code == 'BY')
            return 'Belarus';
        if ($code == 'BE')
            return 'Belgium';
        if ($code == 'BZ')
            return 'Belize';
        if ($code == 'BJ')
            return 'Benin';
        if ($code == 'BM')
            return 'Bermuda';
        if ($code == 'BT')
            return 'Bhutan';
        if ($code == 'BO')
            return 'Bolivia';
        if ($code == 'BA')
            return 'Bosnia and Herzegovina';
        if ($code == 'BW')
            return 'Botswana';
        if ($code == 'BV')
            return 'Bouvet Island (Bouvetoya)';
        if ($code == 'BR')
            return 'Brazil';
        if ($code == 'IO')
            return 'British Indian Ocean Territory (Chagos Archipelago)';
        if ($code == 'VG')
            return 'British Virgin Islands';
        if ($code == 'BN')
            return 'Brunei Darussalam';
        if ($code == 'BG')
            return 'Bulgaria';
        if ($code == 'BF')
            return 'Burkina Faso';
        if ($code == 'BI')
            return 'Burundi';
        if ($code == 'KH')
            return 'Cambodia';
        if ($code == 'CM')
            return 'Cameroon';
        if ($code == 'CA')
            return 'Canada';
        if ($code == 'CV')
            return 'Cape Verde';
        if ($code == 'KY')
            return 'Cayman Islands';
        if ($code == 'CF')
            return 'Central African Republic';
        if ($code == 'TD')
            return 'Chad';
        if ($code == 'CL')
            return 'Chile';
        if ($code == 'CN')
            return 'China';
        if ($code == 'CX')
            return 'Christmas Island';
        if ($code == 'CC')
            return 'Cocos (Keeling) Islands';
        if ($code == 'CO')
            return 'Colombia';
        if ($code == 'KM')
            return 'Comoros the';
        if ($code == 'CD')
            return 'Congo';
        if ($code == 'CG')
            return 'Congo the';
        if ($code == 'CK')
            return 'Cook Islands';
        if ($code == 'CR')
            return 'Costa Rica';
        if ($code == 'CI')
            return 'Cote d\'Ivoire';
        if ($code == 'HR')
            return 'Croatia';
        if ($code == 'CU')
            return 'Cuba';
        if ($code == 'CY')
            return 'Cyprus';
        if ($code == 'CZ')
            return 'Czech Republic';
        if ($code == 'DK')
            return 'Denmark';
        if ($code == 'DJ')
            return 'Djibouti';
        if ($code == 'DM')
            return 'Dominica';
        if ($code == 'DO')
            return 'Dominican Republic';
        if ($code == 'EC')
            return 'Ecuador';
        if ($code == 'EG')
            return 'Egypt';
        if ($code == 'SV')
            return 'El Salvador';
        if ($code == 'GQ')
            return 'Equatorial Guinea';
        if ($code == 'ER')
            return 'Eritrea';
        if ($code == 'EE')
            return 'Estonia';
        if ($code == 'ET')
            return 'Ethiopia';
        if ($code == 'FO')
            return 'Faroe Islands';
        if ($code == 'FK')
            return 'Falkland Islands (Malvinas)';
        if ($code == 'FJ')
            return 'Fiji the Fiji Islands';
        if ($code == 'FI')
            return 'Finland';
        if ($code == 'FR')
            return 'France, French Republic';
        if ($code == 'GF')
            return 'French Guiana';
        if ($code == 'PF')
            return 'French Polynesia';
        if ($code == 'TF')
            return 'French Southern Territories';
        if ($code == 'GA')
            return 'Gabon';
        if ($code == 'GM')
            return 'Gambia the';
        if ($code == 'GE')
            return 'Georgia';
        if ($code == 'DE')
            return 'Germany';
        if ($code == 'GH')
            return 'Ghana';
        if ($code == 'GI')
            return 'Gibraltar';
        if ($code == 'GR')
            return 'Greece';
        if ($code == 'GL')
            return 'Greenland';
        if ($code == 'GD')
            return 'Grenada';
        if ($code == 'GP')
            return 'Guadeloupe';
        if ($code == 'GU')
            return 'Guam';
        if ($code == 'GT')
            return 'Guatemala';
        if ($code == 'GG')
            return 'Guernsey';
        if ($code == 'GN')
            return 'Guinea';
        if ($code == 'GW')
            return 'Guinea-Bissau';
        if ($code == 'GY')
            return 'Guyana';
        if ($code == 'HT')
            return 'Haiti';
        if ($code == 'HM')
            return 'Heard Island and McDonald Islands';
        if ($code == 'VA')
            return 'Holy See (Vatican City State)';
        if ($code == 'HN')
            return 'Honduras';
        if ($code == 'HK')
            return 'Hong Kong';
        if ($code == 'HU')
            return 'Hungary';
        if ($code == 'IS')
            return 'Iceland';
        if ($code == 'IN')
            return 'India';
        if ($code == 'ID')
            return 'Indonesia';
        if ($code == 'IR')
            return 'Iran';
        if ($code == 'IQ')
            return 'Iraq';
        if ($code == 'IE')
            return 'Ireland';
        if ($code == 'IM')
            return 'Isle of Man';
        if ($code == 'IL')
            return 'Israel';
        if ($code == 'IT')
            return 'Italy';
        if ($code == 'JM')
            return 'Jamaica';
        if ($code == 'JP')
            return 'Japan';
        if ($code == 'JE')
            return 'Jersey';
        if ($code == 'JO')
            return 'Jordan';
        if ($code == 'KZ')
            return 'Kazakhstan';
        if ($code == 'KE')
            return 'Kenya';
        if ($code == 'KI')
            return 'Kiribati';
        if ($code == 'KP')
            return 'Korea';
        if ($code == 'KR')
            return 'Korea';
        if ($code == 'KW')
            return 'Kuwait';
        if ($code == 'KG')
            return 'Kyrgyz Republic';
        if ($code == 'LA')
            return 'Lao';
        if ($code == 'LV')
            return 'Latvia';
        if ($code == 'LB')
            return 'Lebanon';
        if ($code == 'LS')
            return 'Lesotho';
        if ($code == 'LR')
            return 'Liberia';
        if ($code == 'LY')
            return 'Libyan Arab Jamahiriya';
        if ($code == 'LI')
            return 'Liechtenstein';
        if ($code == 'LT')
            return 'Lithuania';
        if ($code == 'LU')
            return 'Luxembourg';
        if ($code == 'MO')
            return 'Macao';
        if ($code == 'MK')
            return 'Macedonia';
        if ($code == 'MG')
            return 'Madagascar';
        if ($code == 'MW')
            return 'Malawi';
        if ($code == 'MY')
            return 'Malaysia';
        if ($code == 'MV')
            return 'Maldives';
        if ($code == 'ML')
            return 'Mali';
        if ($code == 'MT')
            return 'Malta';
        if ($code == 'MH')
            return 'Marshall Islands';
        if ($code == 'MQ')
            return 'Martinique';
        if ($code == 'MR')
            return 'Mauritania';
        if ($code == 'MU')
            return 'Mauritius';
        if ($code == 'YT')
            return 'Mayotte';
        if ($code == 'MX')
            return 'Mexico';
        if ($code == 'FM')
            return 'Micronesia';
        if ($code == 'MD')
            return 'Moldova';
        if ($code == 'MC')
            return 'Monaco';
        if ($code == 'MN')
            return 'Mongolia';
        if ($code == 'ME')
            return 'Montenegro';
        if ($code == 'MS')
            return 'Montserrat';
        if ($code == 'MA')
            return 'Morocco';
        if ($code == 'MZ')
            return 'Mozambique';
        if ($code == 'MM')
            return 'Myanmar';
        if ($code == 'NA')
            return 'Namibia';
        if ($code == 'NR')
            return 'Nauru';
        if ($code == 'NP')
            return 'Nepal';
        if ($code == 'AN')
            return 'Netherlands Antilles';
        if ($code == 'NL')
            return 'Netherlands the';
        if ($code == 'NC')
            return 'New Caledonia';
        if ($code == 'NZ')
            return 'New Zealand';
        if ($code == 'NI')
            return 'Nicaragua';
        if ($code == 'NE')
            return 'Niger';
        if ($code == 'NG')
            return 'Nigeria';
        if ($code == 'NU')
            return 'Niue';
        if ($code == 'NF')
            return 'Norfolk Island';
        if ($code == 'MP')
            return 'Northern Mariana Islands';
        if ($code == 'NO')
            return 'Norway';
        if ($code == 'OM')
            return 'Oman';
        if ($code == 'PK')
            return 'Pakistan';
        if ($code == 'PW')
            return 'Palau';
        if ($code == 'PS')
            return 'Palestinian Territory';
        if ($code == 'PA')
            return 'Panama';
        if ($code == 'PG')
            return 'Papua New Guinea';
        if ($code == 'PY')
            return 'Paraguay';
        if ($code == 'PE')
            return 'Peru';
        if ($code == 'PH')
            return 'Philippines';
        if ($code == 'PN')
            return 'Pitcairn Islands';
        if ($code == 'PL')
            return 'Poland';
        if ($code == 'PT')
            return 'Portugal, Portuguese Republic';
        if ($code == 'PR')
            return 'Puerto Rico';
        if ($code == 'QA')
            return 'Qatar';
        if ($code == 'RE')
            return 'Reunion';
        if ($code == 'RO')
            return 'Romania';
        if ($code == 'RU')
            return 'Russian Federation';
        if ($code == 'RW')
            return 'Rwanda';
        if ($code == 'BL')
            return 'Saint Barthelemy';
        if ($code == 'SH')
            return 'Saint Helena';
        if ($code == 'KN')
            return 'Saint Kitts and Nevis';
        if ($code == 'LC')
            return 'Saint Lucia';
        if ($code == 'MF')
            return 'Saint Martin';
        if ($code == 'PM')
            return 'Saint Pierre and Miquelon';
        if ($code == 'VC')
            return 'Saint Vincent and the Grenadines';
        if ($code == 'WS')
            return 'Samoa';
        if ($code == 'SM')
            return 'San Marino';
        if ($code == 'ST')
            return 'Sao Tome and Principe';
        if ($code == 'SA')
            return 'Saudi Arabia';
        if ($code == 'SN')
            return 'Senegal';
        if ($code == 'RS')
            return 'Serbia';
        if ($code == 'SC')
            return 'Seychelles';
        if ($code == 'SL')
            return 'Sierra Leone';
        if ($code == 'SG')
            return 'Singapore';
        if ($code == 'SK')
            return 'Slovakia (Slovak Republic)';
        if ($code == 'SI')
            return 'Slovenia';
        if ($code == 'SB')
            return 'Solomon Islands';
        if ($code == 'SO')
            return 'Somalia, Somali Republic';
        if ($code == 'ZA')
            return 'South Africa';
        if ($code == 'GS')
            return 'South Georgia and the South Sandwich Islands';
        if ($code == 'ES')
            return 'Spain';
        if ($code == 'LK')
            return 'Sri Lanka';
        if ($code == 'SD')
            return 'Sudan';
        if ($code == 'SR')
            return 'Suriname';
        if ($code == 'SJ')
            return 'Svalbard & Jan Mayen Islands';
        if ($code == 'SZ')
            return 'Swaziland';
        if ($code == 'SE')
            return 'Sweden';
        if ($code == 'CH')
            return 'Switzerland, Swiss Confederation';
        if ($code == 'SY')
            return 'Syrian Arab Republic';
        if ($code == 'TW')
            return 'Taiwan';
        if ($code == 'TJ')
            return 'Tajikistan';
        if ($code == 'TZ')
            return 'Tanzania';
        if ($code == 'TH')
            return 'Thailand';
        if ($code == 'TL')
            return 'Timor-Leste';
        if ($code == 'TG')
            return 'Togo';
        if ($code == 'TK')
            return 'Tokelau';
        if ($code == 'TO')
            return 'Tonga';
        if ($code == 'TT')
            return 'Trinidad and Tobago';
        if ($code == 'TN')
            return 'Tunisia';
        if ($code == 'TR')
            return 'Turkey';
        if ($code == 'TM')
            return 'Turkmenistan';
        if ($code == 'TC')
            return 'Turks and Caicos Islands';
        if ($code == 'TV')
            return 'Tuvalu';
        if ($code == 'UG')
            return 'Uganda';
        if ($code == 'UA')
            return 'Ukraine';
        if ($code == 'AE')
            return 'United Arab Emirates';
        if ($code == 'GB')
            return 'United Kingdom';
        if ($code == 'US')
            return 'United States of America';
        if ($code == 'UM')
            return 'United States Minor Outlying Islands';
        if ($code == 'VI')
            return 'United States Virgin Islands';
        if ($code == 'UY')
            return 'Uruguay, Eastern Republic of';
        if ($code == 'UZ')
            return 'Uzbekistan';
        if ($code == 'VU')
            return 'Vanuatu';
        if ($code == 'VE')
            return 'Venezuela';
        if ($code == 'VN')
            return 'Vietnam';
        if ($code == 'WF')
            return 'Wallis and Futuna';
        if ($code == 'EH')
            return 'Western Sahara';
        if ($code == 'YE')
            return 'Yemen';
        if ($code == 'ZM')
            return 'Zambia';
        if ($code == 'ZW')
            return 'Zimbabwe';
    }


    

}
