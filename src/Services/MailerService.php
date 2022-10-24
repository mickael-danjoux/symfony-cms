<?php

namespace App\Services;

use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{


    public function __construct(private readonly ParameterManagerInterface $parameterManager)
    {
    }

    public function replaceContactsWithParam(Email $email, string $param): void
    {
        // si un email est configuré dans les paramètres du site alors on surcharge la configuration du mailer
        $channelEmailTo = explode(',', $this->parameterManager->get($param));
        if (count($channelEmailTo) > 0) {
            foreach ($channelEmailTo as $emailTo) {
                $email->addTo($emailTo);
            }
        }
    }
}
