<?php

namespace Claroline\CoreBundle\Library\Manager;

use Claroline\CoreBundle\Library\Testing\FunctionalTestCase;
use Claroline\CoreBundle\Tests\DataFixtures\LoadResourceTypeData;

class TextManagerTest extends FunctionalTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->loadUserFixture();
        $this->loadFixture(new LoadResourceTypeData());
        $this->client->followRedirects();
    }
    
    public function testAdd()
    {
        $this->logUser($this->getFixtureReference('user/admin'));
        $this->addText('HELLO WORLD');
        $crawler = $this->client->request('GET', '/resource/directory');
        
        $this->assertEquals(1, $crawler->filter('.row_resource')->count());
    }
    
    private function addText($txt)
    {
        $crawler = $this->client->request('GET', '/resource/directory/null');
        $form = $crawler->filter('input[type=submit]')->form(); 
        $fileTypeId = $this->getFixtureReference('resource_type/text')->getId();
        $crawler = $this->client->submit($form, array('select_resource_form[type]' => $fileTypeId));
        $form = $crawler->filter('input[type=submit]')->form();
        $crawler = $this->client->submit($form, array('text_form[text]' => $txt));
        $id = $crawler->filter(".row_resource")->last()->attr('data-resource_id');
        
        return $id;
    }
}