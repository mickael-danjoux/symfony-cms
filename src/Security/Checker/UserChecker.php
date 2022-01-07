<?php
	
	namespace App\Security\Checker;
	
	use App\Entity\User;
	use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
	use Symfony\Component\Security\Core\User\UserCheckerInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	class UserChecker implements UserCheckerInterface
	{
		
		public function checkPreAuth(UserInterface $user)
		{
			if (!$user instanceof User) {
				return;
			}
			
			if (!$user->isVerified()) {
				throw new CustomUserMessageAccountStatusException('Veuillez valider votre adresse email avant de vous connecter.');
			}
		}
		
		public function checkPostAuth(UserInterface $user)
		{
			if (!$user instanceof User) {
				return;
			}
			
			if ($user->isBanned()) {
				// the message passed to this exception is meant to be displayed to the user
				throw new CustomUserMessageAccountStatusException('Ce compte ne vous permet pas de vous connecter');
			}
		}
	}