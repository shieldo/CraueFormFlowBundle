<?php

namespace Craue\FormFlowBundle\Tests;

use Craue\FormFlowBundle\Tests\IntegrationTestCase;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2013 Christian Raue
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Demo1FlowTest extends IntegrationTestCase {

	public function testDemo1_events() {
		$crawler = $this->client->request('GET', $this->url('_FormFlow_demo1'));
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertCurrentFormData('{}', $crawler);
		$this->assertCount(0, $crawler->selectButton('back')); // no back button
		$this->assertEquals(array('onPreBind'), $this->getCalledEvents());

		// reset
		$form = $crawler->selectButton('start over')->form();
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertEquals(array('onPreBind'), $this->getCalledEvents());

		// next
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertCount(1, $crawler->selectButton('back')); // back button
		$this->assertCount(0, $crawler->selectButton('finish')); // no finish button
		$this->assertEquals(array('onPreBind', 'onPostBindRequest', 'onPostValidate'), $this->getCalledEvents());

		// next
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertCount(1, $crawler->selectButton('finish')); // finish button
		$this->assertEquals(array('onPreBind', 'onPostBindSavedData #2', 'onPostBindRequest', 'onPostValidate'), $this->getCalledEvents());

		// go back
		$form = $crawler->selectButton('back')->form();
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertEquals(array('onPreBind', 'onPostBindSavedData #2', 'onPostBindSavedData #3'), $this->getCalledEvents());

		// next
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertEquals(array('onPreBind', 'onPostBindSavedData #2', 'onPostBindSavedData #3', 'onPostBindRequest', 'onPostValidate'), $this->getCalledEvents());

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		$this->client->submit($form);
		$this->assertJsonResponse('{}');

// var_dump($this->client->getResponse()->getContent());
// die;
	}

	protected function getCalledEvents() {
		$container = static::$kernel->getContainer();
		$requestStack = new RequestStack();
		$requestStack->push($this->client->getRequest());
		$container->set('request_stack', $requestStack);

		$flow = $container->get('integrationTestBundle.form.flow.demo1');
		$storage = $container->get('craue.form.flow.storage');

		return $storage->get($flow->getCalledEventsSessionKey());
	}
}
