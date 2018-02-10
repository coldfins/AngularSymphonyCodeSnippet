<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 31/10/14
 * Time: 13:36
 */

namespace Mobntouch\DataBaseBundle\Security;

use Doctrine\ODM\MongoDB\DocumentManager;
use Mobntouch\DataBaseBundle\Document\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


class UserProvider implements UserProviderInterface
{
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        // Set up database
        $this->dm = $dm;
    }

    public function loadUserByUsername($username)
    {
        // make a call to your webservice here
        //$userData = ...
        // pretend it returns an array on success, false if there is no user

        // Get the User
        $user = $this->dm->getRepository('DataBaseBundle:User')->findOneBy( array('username'=> $username));

        //print_r($username);
        if ($user) {
            // ...
            $password = $user->getPassword();
            $salt = $user->getSalt();
            $roles = $user->getRoles();

            return new User($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Mobntouch\DataBaseBundle\Document\User';
    }
}
