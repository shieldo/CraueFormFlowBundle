<?php

namespace Craue\FormFlowBundle\Storage;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Stores data in the session.
 *
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 * @copyright 2011-2013 Christian Raue
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class SessionStorage implements StorageInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
	 * {@inheritDoc}
	 */
	public function set($key, $value)
    {
        $session = $this->getSession();
        if (!$session) {
            return;
        }
		$session->set($key, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($key, $default = null)
    {
        $session = $this->getSession();
        if (!$session) {
            return null;
        }

		return $session->get($key, $default);
	}

	/**
	 * {@inheritDoc}
	 */
	public function has($key)
    {
        $session = $this->getSession();
        if (!$session) {
            return false;
        }

		return $session->has($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove($key)
    {
        $session = $this->getSession();
        if (!$session) {
            return null;
        }

		return $session->remove($key);
	}

    /**
     * @return SessionInterface|null
     */
    private function getSession()
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return null;
        }

        return $request->getSession();
	}
}
