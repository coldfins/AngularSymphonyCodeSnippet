<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 28/10/14
 * Time: 12:15
 */

namespace Mobntouch\APIBundle\Tests\Document;

use Mobntouch\DataBaseBundle\Document\User;


class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testAdd()
    {
        $user = new User();
        $user->setName('josep');
        $result = $user->getName();

        $this->assertEquals('josep', $result);
    }
} 