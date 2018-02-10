<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mobntouch\APIBundle\Classes;

use Mobntouch\DataBaseBundle\Document\User;

/**
 * Description of ManageMutualConnections
 *
 * @author ved-pc
 */
class ManageMutualConnections {

    //put your code here
    private $dm;

    function __construct($dm) {
        $this->dm = $dm;
    }

    public function getMutualConnections($firstUser, $secondUser, $checkInvitation = true, $checkConnectionStatus = true) {

        if ($firstUser instanceof User && $checkConnectionStatus) {
            $connections = $firstUser->getInTouch() ? $firstUser->getInTouch() : array();
            $cIds = $rIds = $rqIds = $dConnections = array();
            foreach ($connections as $connection) {
                switch ($connection['status']) {
                    case 3:
                        $cIds[] = $connection['id'];
                        break;
                    case 2:
                        $rIds[] = $connection['id'];
                        break;
                    case 1:
                        $rqIds[] = $connection['id'];
                        break;
                }
            }
        }

        $arrMutualConnection = array();
        $q = $this->dm->createQueryBuilder('DataBaseBundle:User')->hydrate(false)->select('username', 'name', 'lastname', 'email', 'avatar', 'cover', 'company', 'jobTitle', 'city', 'miniResume', 'summary');
        $q->field('inTouch')->elemMatch(array('status' => 3, 'id' => is_object($firstUser) ? $firstUser->id : $firstUser['id']));
        $q->addAnd(
                $q->expr()->field('inTouch')->elemMatch(array('status' => 3, 'id' => is_object($secondUser) ? $secondUser->id : $secondUser['id']))
        );
        $mutualConnections = $q->getQuery()->execute();

        $emails = array();
        foreach ($mutualConnections as $mutualConnection) {
            if (!in_array($mutualConnection['email'], $emails)) {
                $mutualConnection['id'] = $mutualConnection['_id']->{'$id'};
                unset($mutualConnection['_id']);
                if ($firstUser instanceof User && $checkConnectionStatus) {
                    if (in_array($mutualConnection['id'], $cIds)) {
                        $mutualConnection['isConnected'] = true;
                    } else if (in_array($mutualConnection['id'], $rIds) || in_array($mutualConnection['id'], $rqIds)) {
                        $mutualConnection['isRequested'] = true;
                    }
                }
                $arrMutualConnection[] = $mutualConnection;
                $emails[] = $mutualConnection['email'];
            }
        }
        //Getting common email from sync contacts of both users as a mutual connection need to refactor this if wants user avatar in future
        if ($checkInvitation) {
            $q = $this->dm->createQueryBuilder('DataBaseBundle:Invitation')
                            ->field('isInvited')->equals(true)
                            ->field('isAlreadyExists')->equals(true);
            $q->addOr(
                    $q->expr()->field('userID')->equals(is_object($firstUser) ? $firstUser->id : $firstUser['id'])
            );
            $q->addOr(
                    $q->expr()->field('userID')->equals(is_object($secondUser) ? $secondUser->id : $secondUser['id'])
            );
            $inv = $q->getQuery()->execute()->toArray();

            $result = array();
            $arrResult = array();
            foreach ($inv as $i) {
                if (!isset($result[$i->email]))
                    $result[$i->email] = $i;
                else
                    $arrResult[] = $i;
            }


            /* Not working used to get unique 
              $allInvs = array_map(function($v) {
              return $v->email;
              }, $inv);

              $uniqueEmails = array_unique($allInvs);

              $result = array_intersect_key($inv, $uniqueEmails);

              print_r($result);
             * 
             */

            foreach ($arrResult as $i) {

                if (!in_array($i->email, $emails)) {

                    $contact = array(
                        'id' => $i->existingUser['id'],
                        'username' => $i->existingUser['username'],
                        'name' => $i->firstname ? $i->firstname : explode('@', $i->email)[0],
                        'lastname' => $i->lastname ? $i->lastname : '',
                        'company' => $i->company,
                        'jobTitle' => $i->jobTitle,
                        'avatar' => isset($i->existingUser['avatar']) ? $i->existingUser['avatar'] : null
                    );

                    $arrMutualConnection[] = $contact;
                }
            }
        }
        return $arrMutualConnection;
    }

    public function checkIsIntouch($firstUser, $secondUser) {
        $q = $this->dm->createQueryBuilder('DataBaseBundle:User')->hydrate(false)->select('username', 'name', 'lastname', 'email', 'avatar', 'company', 'jobTitle');
        $q->field('inTouch.username')->in(array(is_object($firstUser) ? $firstUser->username : $firstUser['username'], is_object($secondUser) ? $secondUser->username : $secondUser['username']));
        $q->field('username')->in(array(is_object($firstUser) ? $firstUser->username : $firstUser['username'], is_object($secondUser) ? $secondUser->username : $secondUser['username']));
        return $q->getQuery()->execute()->count() == 2 ? true : false;
    }

}
