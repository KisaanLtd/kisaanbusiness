<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\UserBundle\Security;

use Kisaan\UserBundle\Model\UserManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * OAuthUserProvider
 */
class OAuthUserProvider extends BaseClass
{

    protected $kisaanUserManager;

    /**
     * Constructor.
     *
     * @param UserManager $kisaanUserManager
     */
    public function __construct(UserManager $kisaanUserManager)
    {
        parent::__construct($kisaanUserManager, array());
        $this->kisaanUserManager = $kisaanUserManager;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->kisaanUserManager->findUserBy(array('username' => $username));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->kisaanUserManager->checkAndCreateOrUpdateUserByOAuth($response);

        if (!$user instanceof UserInterface) {
            //loadUserByOAuthUserResponse() must return a UserInterface.
            throw new AuthenticationServiceException('Error while logging.');
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Kisaan\\UserBundle\\Entity\\User';
    }
}
