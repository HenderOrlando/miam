<?php

namespace Miam\Tests\Functional;

use Bundle\PHPUnitBundle\Functional;

class scheduleASprintTest extends \WebTestCase
{
    
    public function testState()
    {
        $crawler = $this->client->request('GET', '/sprint/schedule');

        $this->addRequestTester();
        $this->client->assertRequestParameter('_route', 'sprint_schedule');
        $this->client->assertRequestParameter('_controller', 'MiamBundle:Sprint:schedule');

        $this->addResponseTester();
        $this->client->assertResponseSelectCount('.col_left .story', 13);
        $this->client->assertResponseSelectCount('.col_right .story', 30);
        $this->client->assertResponseSelectEquals('#sprint_points', array('_text'), array('210'));
    }

    public function testScheduleDefault()
    {
        $this->login('thib', 'changeme');
        $crawler = $this->client->request('GET', '/sprint/schedule');
        $crawler = $this->client->click($crawler->selectLink('Smoke in the water')->link());
        //$this->client->click($crawler->selectLink('Planifier (à faire)')->link());
        //$this->client->followRedirect();
        
        //$this->addRequestTester();
        //$this->client->assertRequestParameter('_route', 'sprint_schedule');
        //$this->client->assertRequestParameter('_controller', 'MiamBundle:Sprint:schedule');

        //$this->addResponseTester();
        //$this->client->assertResponseSelectCount('.col_left .story', 13);
        //$this->client->assertResponseSelectCount('.col_right .story', 31);
        //$this->client->assertResponseSelectEquals('#sprint_points', array('_text'), array('220'));
        // $this->client->assertResponseSelectEquals('.col_right ol li:first-child .status_todo', array('_text'), array('Smoke in the water'));
    }

    public function testSchedulePending()
    {
        //$this->login('thib', 'changeme');
        //$crawler = $this->client->request('GET', '/sprint/schedule');

        //$form = $crawler->filter('#addStoryPending')->form();
        //$this->client->submit($form, array(
        //));
        //$this->client->followRedirect();
        
        //$this->addRequestTester();
        //$this->client->assertRequestParameter('_route', 'sprint_schedule');
        //$this->client->assertRequestParameter('_controller', 'MiamBundle:Sprint:schedule');

        //$this->addResponseTester();
        //$this->client->assertResponseSelectCount('.col_left .story', 13);
        //$this->client->assertResponseSelectCount('.col_right .story', 31);
        //$this->client->assertResponseSelectEquals('#sprint_points', array('_text'), array('220'));
        //$this->client->assertResponseSelectEquals('.story_planCard .story_name', array('_text'), array('Danse on a volcano'));
        //$this->client->assertResponseSelectEquals('.col_right ol li:first-child .status_pending', array('_text'), array('Smoke in the water'));
    }

}
