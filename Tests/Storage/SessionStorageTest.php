<?php

namespace Craue\FormFlowBundle\Tests\Storage;

use Craue\FormFlowBundle\Storage\SessionStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2013 Christian Raue
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class SessionStorageTest extends AbstractStorageTest {

	/**
	 * {@inheritDoc}
	 */
	protected function getStorageImplementation() {
	    $session = new Session(new MockArraySessionStorage());
	    $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();
	    $request
            ->expects($this->any())
            ->method('getSession')
            ->will($this->returnValue($session));
        $requestStack = new RequestStack();
        $requestStack->push($request);

		return new SessionStorage($requestStack);
	}

}
