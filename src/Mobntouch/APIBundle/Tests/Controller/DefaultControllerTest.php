<?php

namespace Mobntouch\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testCreateAuthenticatedClient($email = 'josep@email.com', $password = 'josep')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            array(
                'email' => $email,
                'password' => $password,
            )
        );

        $content = $client->getResponse()->getContent();
        $content = json_decode($content);
        print_r($content);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $data = json_decode($client->getResponse()->getContent(), true);
        print_r($data['token']);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        print_r($client);
        print_r("END AUTH CLIENT");

        return $client;
    }

    // EXISTING USER WITHOUT NAME
    public function testUser1()
    {
        $client = static::createClient();

        $username = 'josepmarti';
        $client->request('GET', "/api/user/$username");

        // Assert that the response status code is 2xx
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $json = $client->getResponse()->getContent();
        $json = json_decode($json);
        //print_r($json);
        foreach($json as $key => $value){
            //if($key=='name' and $value==200) $this->assertTrue(true);
            if($key=='name' and $value=='') $this->assertTrue(true);
            if($key=='username' and $value==$username) $this->assertTrue(true);
        }

    }

    // NON EXISTING USER
    public function testUser2()
    {
        $client = static::createClient();

        $username = 'josep';
        $client->request('GET', "/api/user/$username");

        // Assert that the response status code is 2xx
        $this->assertTrue($client->getResponse()->isNotFound());

    }
}
