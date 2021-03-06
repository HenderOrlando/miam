<?php

namespace Miam\Tests\Functional;

use Bundle\PHPUnitBundle\Functional;

class createAStoryTest extends \WebTestCase
{
    public function testFormValidation()
    {
        $this->login('thib', 'changeme');
        $crawler = $this->client->request('GET', '/story/new');

        $form = $crawler->selectButton('Valider')->form();
        $this->client->submit($form, array(
            'story[name]' => null,
            'story[body]' => null,
            'story[points]' => null
        ));

        $this->addRequestTester();
        $this->client->assertRequestParameter('_route', 'story_new');
        $this->client->assertRequestParameter('_controller', 'MiamBundle:Story:new');

        $this->addResponseTester();
        $this->client->assertResponseRegExp('/This value should not be null/');
    }

    
    public function testCreateAStoryShowsItOnTheBacklog()
    {
        $this->login('thib', 'changeme');
        $crawler = $this->client->request('GET', '/story/new');

        $this->addRequestTester();
        $this->client->assertRequestParameter('_route', 'story_new');
        $this->client->assertRequestParameter('_controller', 'MiamBundle:Story:new');

        $form = $crawler->selectButton('Valider')->form();
        $this->client->submit($form, array(
            'story[name]' => 'My story morning glory',
            'story[body]' => 'lorem',
            'story[points]' => '12'
        ));
        $this->client->followRedirect();

        $this->addRequestTester();
        $this->client->assertRequestParameter('_route', 'sprint_schedule');

        $this->addResponseTester();
        $this->client->assertResponseRegExp('/My story morning glory<\/a>/');
        if($this->testFlashes) {
            $this->client->assertResponseRegExp('/au backlog/');
        }
    }

}
