<?php

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class JobUpdateProfileAvatarCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('Job:UpdateProfileAvatar')
                ->setDescription('Update all document for user profile avatar')
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

        $companyPage = $user->getCompanyPage();
        $username = $user->getUsername();
        $usrId = $user->getId();
        $avatar = $user->getAvatar();

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('administrators.id')->equals($usrId)
                ->field('administrators.$.avatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('employees.id')->equals($usrId)
                ->field('employees.$.avatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();



        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('followers.id')->equals($usrId)
                ->field('followers.$.avatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();


        $dm->createQueryBuilder('DataBaseBundle:UserSearch')
                ->update()
                ->multiple(false)
                ->field('userID')->equals($usrId)
                ->field('avatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Mail')
                ->update()
                ->multiple(true)
                ->field('fromID')->equals($usrId)
                ->field('senderAvatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Mail')
                ->update()
                ->multiple(true)
                ->field('toID')->equals($usrId)
                ->field('receiverAvatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Offer')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($usrId)
                ->field('userAvatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($usrId)
                ->field('userAvatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();


        $dm->createQueryBuilder('DataBaseBundle:Update')
                ->update()
                ->multiple(true)
                ->field('userID')->equals($usrId)
                ->field('userAvatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Update')
                ->update()
                ->multiple(true)
                ->field('inTouchID')->equals($usrId)
                ->field('inTouchAvatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $likes = $dm->createQueryBuilder('DataBaseBundle:Update')
                ->field('liked.userID')->equals($usrId)
                ->eagerCursor(true)
                ->getQuery()
                ->execute();
        foreach ($likes as $u) {
            foreach ($u->liked as $key => $field) {
                if ($field['userID'] == $usrId) {
                    $u->liked[$key]['avatar'] = $user->getAvatar();
                }
            }
        }

        $results = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('alerts.username')->equals($user->getUsername())
                ->eagerCursor(true)
                ->getQuery()
                ->execute();
        foreach ($results as $u) {
            foreach ($u->alerts as $key => $alt) {
                if ($alt['username'] == $user->getUsername()) {
                    $u->alerts[$key]['avatar'] = $user->getAvatar();
                }
            }
        }

        $iVisited = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('iVisited.username')->equals($user->getUsername())
                ->eagerCursor(true)
                ->getQuery()
                ->execute();
        foreach ($iVisited as $u) {
            foreach ($u->iVisited as $key => $field) {
                if ($field['username'] == $user->getUsername()) {
                    $u->iVisited[$key]['avatar'] = $user->getAvatar();
                }
            }
        }


        $whoVisitedMe = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('whoVisitedMe.username')->equals($user->getUsername())
                ->eagerCursor(true)
                ->getQuery()
                ->execute();

        if (isset($whoVisitedMe) && $whoVisitedMe) {
            foreach ($whoVisitedMe as $u) {
                if (isset($u->whoVisitedMe) && $u->whoVisitedMe && is_array($u->whoVisitedMe)) {
                    foreach ($u->whoVisitedMe as $key => $field) {
                        if (is_array($field) && array_key_exists('username', $field) && $field['username'] == $user->getUsername()) {
                            $u->whoVisitedMe[$key]['avatar'] = $user->getAvatar();
                        }
                    }
                }
            }
        }

        $dm->createQueryBuilder('DataBaseBundle:User')
                ->update()
                ->multiple(true)
                ->field('inTouch.id')->equals($usrId)
                ->field('inTouch.$.avatar')->set($avatar)
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->update()
                ->multiple(true)
                ->field('askedBy.id')->equals($user->getId())
                ->field('askedBy.avatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('createdBy.id')->equals($user->getId())
                ->field('createdBy.avatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('appliedBy.id')->equals($user->getId())
                ->field('appliedBy.$.avatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('starredBy.id')->equals($user->getId())
                ->field('starredBy.$.avatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->update()
                ->multiple(true)
                ->field('skippedBy.id')->equals($user->getId())
                ->field('skippedBy.$.avatar')->set($user->getAvatar())
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->flush();
        $dm->clear();
    }

}
