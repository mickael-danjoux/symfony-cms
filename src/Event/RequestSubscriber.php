<?php

namespace App\Event;

use App\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class RequestSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private readonly RouterInterface $router,
        private readonly Security $security
	)
	{
	}

	public static function getSubscribedEvents(): array
	{
		return [
			RequestEvent::class => 'onKernelRequest',
		];
	}

	public function onKernelRequest(RequestEvent $event)
	{
		if ($this->supports()) {
			/** @var User $user */
			$user = $this->security->getUser();
			if ($user->isBanned()) {
				$redirectTo = $this->router->generate('app_logout');
				$event->setResponse(new RedirectResponse($redirectTo));
			}
		}
	}

	private function supports(): bool
	{
		return $this->security->isGranted('ROLE_USER');
	}
}