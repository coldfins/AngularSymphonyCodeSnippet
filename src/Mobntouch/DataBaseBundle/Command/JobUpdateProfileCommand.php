<?php

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class JobUpdateProfileCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('Job:UpdateProfile')
                ->setDescription('Update all document for user profile details')
                ->addArgument('userId');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        set_time_limit(0);
        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $userId = $input->getArgument('userId');


        $user = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('id')->equals($userId)
                ->getQuery()
                ->getSingleResult();

        if (!$user) {
            return;
        }

        $usrId = $user->getId();

        $colleges = $dm->createQueryBuilder('DataBaseBundle:College')
                ->field('students.id')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($colleges as $clg) {
            foreach ($clg->students as $key => $field) {
                if ($field['id'] == $usrId) {
                    $clg->students[$key]['username'] = $user->getUsername();
                    $clg->students[$key]['name'] = $user->getName();
                    $clg->students[$key]['lastname'] = $user->getLastname();
                    $clg->students[$key]['jobTitle'] = $user->getJobTitle();
                    $clg->students[$key]['company'] = $user->getCompany();
                    $clg->students[$key]['avatar'] = $user->getAvatar();
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('administrators.id')->equals($user->getId())
                ->field('administrators.$.username')->set($user->getUsername())
                ->field('administrators.$.name')->set($user->getName())
                ->field('administrators.$.lastname')->set($user->getLastname())
                ->field('administrators.$.jobTitle')->set($user->getJobTitle())
                ->field('administrators.$.avatar')->set($user->getAvatar())
                ->field('administrators.$.cover')->set($user->getCover())
                ->field('administrators.$.miniResume')->set($user->getMiniResume())
                ->field('administrators.$.city')->set($user->getCity())
                ->field('administrators.$.basedCountry')->set($user->getBasedCountry())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('employees.id')->equals($user->getId())
                ->field('employees.$.username')->set($user->getUsername())
                ->field('employees.$.name')->set($user->getName())
                ->field('employees.$.lastname')->set($user->getLastname())
                ->field('employees.$.jobTitle')->set($user->getJobTitle())
                ->field('employees.$.avatar')->set($user->getAvatar())
                ->field('employees.$.cover')->set($user->getCover())
                ->field('employees.$.miniResume')->set($user->getMiniResume())
                ->field('employees.$.city')->set($user->getCity())
                ->field('employees.$.basedCountry')->set($user->getBasedCountry())
                ->field('employees.$.responseRate')->set($user->getResponseRate())
                ->field('employees.$.totalReceivedEmails')->set($user->getTotalReceivedEmails())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('recruiters.id')->equals($user->getId())
                ->field('recruiters.$.username')->set($user->getUsername())
                ->field('recruiters.$.name')->set($user->getName())
                ->field('recruiters.$.lastname')->set($user->getLastname())
                ->field('recruiters.$.jobTitle')->set($user->getJobTitle())
                ->field('recruiters.$.avatar')->set($user->getAvatar())
                ->field('recruiters.$.cover')->set($user->getCover())
                ->field('recruiters.$.miniResume')->set($user->getMiniResume())
                ->field('recruiters.$.city')->set($user->getCity())
                ->field('recruiters.$.basedCountry')->set($user->getBasedCountry())
                ->field('recruiters.$.responseRate')->set($user->getResponseRate())
                ->field('recruiters.$.totalReceivedEmails')->set($user->getTotalReceivedEmails())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('followers.id')->equals($user->getId())
                ->field('followers.$.username')->set($user->getUsername())
                ->field('followers.$.name')->set($user->getName())
                ->field('followers.$.lastname')->set($user->getLastname())
                ->field('followers.$.jobTitle')->set($user->getJobTitle())
                ->field('followers.$.company')->set($user->getCompany())
                ->field('followers.$.avatar')->set($user->getAvatar())
                ->field('followers.$.cover')->set($user->getCover())
                ->field('followers.$.miniResume')->set($user->getMiniResume())
                ->field('followers.$.city')->set($user->getCity())
                ->field('followers.$.basedCountry')->set($user->getBasedCountry())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $visitors = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->field('visitors.id')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($visitors as $v) {
            foreach ($v->visitors as $key => $field) {
                if ($field['id'] == $usrId) {
                    $inv->visitors[$key]['username'] = $user->getUsername();
                    $inv->visitors[$key]['name'] = $user->getName();
                    $inv->visitors[$key]['lastname'] = $user->getLastname();
                    $inv->visitors[$key]['jobTitle'] = $user->getJobTitle();
                    $inv->visitors[$key]['company'] = $user->getCompany();
                    $inv->visitors[$key]['avatar'] = $user->getAvatar();
                }
            }
        }

        $invitations = $dm->createQueryBuilder('DataBaseBundle:Invitation')
                ->field('exisitngUser.id')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($invitations as $inv) {
            foreach ($inv->exisitngUser as $key => $field) {
                if ($field['id'] == $usrId) {
                    $inv->exisitngUser[$key]['username'] = $user->getUsername();
                    $inv->exisitngUser[$key]['jobTitle'] = $user->getJobTitle();
                    $inv->exisitngUser[$key]['company'] = $user->getCompany();
                    $inv->exisitngUser[$key]['avatar'] = $user->getAvatar();
                    $inv->exisitngUser[$key]['cover'] = $user->getCover();
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:Mail')
                ->update()
                ->multiple(true)
                ->field('fromID')->equals($user->getId())
                ->field('senderName')->set($user->getName() . ' ' . $user->getLastname())
                ->field('senderAvatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Mail')
                ->update()
                ->multiple(true)
                ->field('toID')->equals($user->getId())
                ->field('receiverName')->set($user->getName() . ' ' . $user->getLastname())
                ->field('receiverAvatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Offer')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($user->getId())
                ->field('username')->set($user->getUsername())
                ->field('userFirstName')->set($user->getName())
                ->field('userLastName')->set($user->getLastname())
                ->field('userCompany')->set($user->getCompany())
                ->field('userAvatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($user->getId())
                ->field('username')->set($user->getUsername())
                ->field('userFirstName')->set($user->getName())
                ->field('userLastName')->set($user->getLastname())
                ->field('userCompany')->set($user->getCompany())
                ->field('userAvatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $suggestions = $dm->createQueryBuilder('DataBaseBundle:ServiceSuggestions')
                ->field('suggestedBy.id')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($suggestions as $sug) {
            foreach ($sug->suggestedBy as $key => $field) {
                if ($field['id'] == $usrId) {
                    $inv->suggestedBy[$key]['username'] = $user->getUsername();
                    $inv->suggestedBy[$key]['name'] = $user->getName();
                    $inv->suggestedBy[$key]['lastname'] = $user->getLastname();
                    $inv->suggestedBy[$key]['jobTitle'] = $user->getJobTitle();
                    $inv->suggestedBy[$key]['company'] = $user->getCompany();
                    $inv->suggestedBy[$key]['avatar'] = $user->getAvatar();
                    $inv->suggestedBy[$key]['cover'] = $user->getCover();
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:Update')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($user->getId())
                ->field('username')->set($user->getUsername())
                ->field('userFullName')->set($user->getName() . ' ' . $user->getLastname())
                ->field('userJobTitle')->set($user->getJobTitle())
                ->field('userAvatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Update')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($user->getId())
                ->field('username')->set($user->getUsername())
                ->field('userFullName')->set($user->getName() . ' ' . $user->getLastname())
                ->field('userJobTitle')->set($user->getJobTitle())
                ->field('userAvatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();


        $likes = $dm->createQueryBuilder('DataBaseBundle:Update')
                ->field('liked.userID')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($likes as $like) {
            foreach ($like->liked as $key => $field) {
                if ($field['userID'] == $usrId) {
                    $like->liked[$key]['username'] = $user->getUsername();
                    $like->liked[$key]['name'] = $user->getName();
                    $like->liked[$key]['lastname'] = $user->getLastname();
                    $like->liked[$key]['jobTitle'] = $user->getJobTitle();
                    $like->liked[$key]['company'] = $user->getCompany();
                    $like->liked[$key]['avatar'] = $user->getAvatar();
                    $like->liked[$key]['cover'] = $user->getCover();
                }
            }
        }

        $comments = $dm->createQueryBuilder('DataBaseBundle:Update')
                ->field('comments.userID')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($comments as $cmnt) {
            foreach ($cmnt->comments as $key => $field) {
                if ($field['userID'] == $usrId) {
                    $cmnt->comments[$key]['username'] = $user->getUsername();
                    $cmnt->comments[$key]['name'] = $user->getName();
                    $cmnt->comments[$key]['lastname'] = $user->getLastname();
                    $cmnt->comments[$key]['jobTitle'] = $user->getJobTitle();
                    $cmnt->comments[$key]['company'] = $user->getCompany();
                    $cmnt->comments[$key]['avatar'] = $user->getAvatar();
                    $cmnt->comments[$key]['cover'] = $user->getCover();
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->update()
                ->multiple(true)
                ->field('askedBy.id')->equals($user->getId())
                ->field('askedBy.username')->set($user->getUsername())
                ->field('askedBy.name')->set($user->getName())
                ->field('askedBy.lastname')->set($user->getLastname())
                ->field('askedBy.company')->set($user->getCompany())
                ->field('askedBy.jobTitle')->set($user->getJobTitle())
                ->field('askedBy.avatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();
        
        $answeredBy = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->field('answeredBy.id')->equals($usrId)
                ->getQuery()
                ->execute();
        foreach ($answeredBy as $a) {
            foreach ($a->answeredBy as $key => $field) {
                if ($field['id'] == $userId) {
                    $a->answeredBy[$key]['username'] = $user->getUsername();
                    $a->answeredBy[$key]['name'] = $user->getName();
                    $a->answeredBy[$key]['lastname'] = $user->getLastname();
                    $a->answeredBy[$key]['jobTitle'] = $user->getJobTitle();
                    $a->answeredBy[$key]['company'] = $user->getCompany();
                    $a->answeredBy[$key]['avatar'] = $user->getAvatar();
                    $a->answeredBy[$key]['cover'] = $user->getCover();
                }
            }
        }
        
        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('appliedBy.id')->equals($user->getId())
                ->field('appliedBy.$.username')->set($user->getUsername())
                ->field('appliedBy.$.name')->set($user->getName())
                ->field('appliedBy.$.lastname')->set($user->getLastname())
                ->field('appliedBy.$.company')->set($user->getCompany())
                ->field('appliedBy.$.jobTitle')->set($user->getJobTitle())
                ->field('appliedBy.$.avatar')->set($user->getAvatar())
                ->field('appliedBy.$.cover')->set($user->getCover())
                ->field('appliedBy.$.city')->set($user->getCity())
                ->upsert(false)
                ->getQuery()
                ->execute();
        
        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('skippedBy.id')->equals($user->getId())
                ->field('skippedBy.$.username')->set($user->getUsername())
                ->field('skippedBy.$.name')->set($user->getName())
                ->field('skippedBy.$.lastname')->set($user->getLastname())
                ->field('skippedBy.$.company')->set($user->getCompany())
                ->field('skippedBy.$.jobTitle')->set($user->getJobTitle())
                ->field('skippedBy.$.avatar')->set($user->getAvatar())
                ->field('skippedBy.$.cover')->set($user->getCover())
                ->field('skippedBy.$.city')->set($user->getCity())
                ->upsert(false)
                ->getQuery()
                ->execute();
        
        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('starredBy.id')->equals($user->getId())
                ->field('starredBy.$.username')->set($user->getUsername())
                ->field('starredBy.$.name')->set($user->getName())
                ->field('starredBy.$.lastname')->set($user->getLastname())
                ->field('starredBy.$.company')->set($user->getCompany())
                ->field('starredBy.$.jobTitle')->set($user->getJobTitle())
                ->field('starredBy.$.avatar')->set($user->getAvatar())
                ->field('starredBy.$.cover')->set($user->getCover())
                ->field('starredBy.$.city')->set($user->getCity())
                ->upsert(false)
                ->getQuery()
                ->execute();


        $iVisited = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('iVisited.username')->equals($user->getUsername())
                ->getQuery()
                ->execute();
        foreach ($iVisited as $iV) {
            foreach ($iV->iVisited as $key => $field) {
                if ($field['username'] == $user->getUsername()) {
                    $iV->iVisited[$key]['username'] = $user->getUsername();
                    $iV->iVisited[$key]['name'] = $user->getName();
                    $iV->iVisited[$key]['lastname'] = $user->getLastname();
                    $iV->iVisited[$key]['jobTitle'] = $user->getJobTitle();
                    $iV->iVisited[$key]['company'] = $user->getCompany();
                    $iV->iVisited[$key]['avatar'] = $user->getAvatar();
                    $iV->iVisited[$key]['cover'] = $user->getCover();
                }
            }
        }

        $whoVisitedMe = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('whoVisitedMe.username')->equals($user->getUsername())
                ->getQuery()
                ->execute();
        if (isset($whoVisitedMe) && $whoVisitedMe) {
            foreach ($whoVisitedMe as $wV) {
                if (isset($wV->whoVisitedMe) && $wV->whoVisitedMe && is_array($wV->whoVisitedMe)) {
                    foreach ($wV->whoVisitedMe as $key => $field) {
                        if (array_key_exists('username', $field) && $field['username'] == $user->getUsername()) {
                            $wV->whoVisitedMe[$key]['username'] = $user->getUsername();
                            $wV->whoVisitedMe[$key]['name'] = $user->getName();
                            $wV->whoVisitedMe[$key]['lastname'] = $user->getLastname();
                            $wV->whoVisitedMe[$key]['jobTitle'] = $user->getJobTitle();
                            $wV->whoVisitedMe[$key]['company'] = $user->getCompany();
                            $wV->whoVisitedMe[$key]['avatar'] = $user->getAvatar();
                            $wV->whoVisitedMe[$key]['cover'] = $user->getCover();
                        }
                    }
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:User')
                ->update()
                ->multiple(true)
                ->field('inTouch.id')->equals($usrId)
                ->field('inTouch.$.username')->set($user->getUsername())
                ->field('inTouch.$.name')->set($user->getName())
                ->field('inTouch.$.lastname')->set($user->getLastname())
                ->field('inTouch.$.avatar')->set($user->getAvatar())
                ->field('inTouch.$.cover')->set($user->getCover())
                ->field('inTouch.$.miniResume')->set($user->getMiniResume())
                ->field('inTouch.$.jobTitle')->set($user->getJobTitle())
                ->field('inTouch.$.company')->set($user->getCompany())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $alerts = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('alerts.username')->equals($user->getUsername())
                ->getQuery()
                ->execute();
        foreach ($alerts as $alt) {
            foreach ($alt->alerts as $key => $field) {
                if ($field['username'] == $user->getUsername()) {
                    $alt->alerts[$key]['username'] = $user->getUsername();
                    $alt->alerts[$key]['name'] = $user->getName();
                    $alt->alerts[$key]['lastname'] = $user->getLastname();
                    $alt->alerts[$key]['jobTitle'] = $user->getJobTitle();
                    $alt->alerts[$key]['company'] = $user->getCompany();
                    $alt->alerts[$key]['avatar'] = $user->getAvatar();
                    $alt->alerts[$key]['cover'] = $user->getCover();
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('createdBy.id')->equals($usrId)
                ->field('createdBy.name')->set($user->getName())
                ->field('createdBy.lastname')->set($user->getLastname())
                ->field('createdBy.avatar')->set($user->getAvatar())
                ->field('createdBy.cover')->set($user->getCover())
                ->field('createdBy.jobTitle')->set($user->getJobTitle())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('appliedBy.id')->equals($usrId)
                ->field('appliedBy.$.email')->set($user->getEmail())
                ->field('appliedBy.$.name')->set($user->getName())
                ->field('appliedBy.$.lastname')->set($user->getLastname())
                ->field('appliedBy.$.avatar')->set($user->getAvatar())
                ->field('appliedBy.$.cover')->set($user->getCover())
                ->field('appliedBy.$.jobTitle')->set($user->getJobTitle())
                ->field('appliedBy.$.city')->set($user->getCity())
                ->field('appliedBy.$.company')->set($user->getCompany())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('starredBy.id')->equals($user->getId())
                ->field('starredBy.$.email')->set($user->getEmail())
                ->field('starredBy.$.name')->set($user->getName())
                ->field('starredBy.$.lastname')->set($user->getLastname())
                ->field('starredBy.$.avatar')->set($user->getAvatar())
                ->field('starredBy.$.cover')->set($user->getCover())
                ->field('starredBy.$.jobTitle')->set($user->getJobTitle())
                ->field('starredBy.$.city')->set($user->getCity())
                ->field('starredBy.$.company')->set($user->getCompany())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('skippedBy.id')->equals($user->getId())
                ->field('skippedBy.$.email')->set($user->getEmail())
                ->field('skippedBy.$.name')->set($user->getName())
                ->field('skippedBy.$.lastname')->set($user->getLastname())
                ->field('skippedBy.$.avatar')->set($user->getAvatar())
                ->field('skippedBy.$.cover')->set($user->getCover())
                ->field('skippedBy.$.jobTitle')->set($user->getJobTitle())
                ->field('skippedBy.$.city')->set($user->getCity())
                ->field('skippedBy.$.company')->set($user->getCompany())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->flush();
        $dm->clear();
    }

}
