<?php

namespace App\Command;

use App\Classes\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserInitCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    protected static $defaultDescription = "Creation d'un utilisateur";
    private UserDTO $user;
    private UserRepository $userRepo;
    private ValidatorInterface $validator;
    private SymfonyStyle $io;
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $em;

    private string $attribute = "";

    /**
     * UserInitCommand constructor.
     */
    public function __construct(UserRepository              $userRepo,
                                ValidatorInterface          $validator,
                                UserPasswordHasherInterface $hasher,
                                EntityManagerInterface      $em
    )
    {
        parent::__construct();
        $this->user = new UserDTO();
        $this->userRepo = $userRepo;
        $this->validator = $validator;
        $this->hasher = $hasher;
        $this->em = $em;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->io = new SymfonyStyle($input, $output);

            $this->getAttribute('Email', 'email');
            $this->getAttribute('Mot de passe', 'password', true);
            $this->getAttribute('Repétez le mot de passe', 'repeatedPassword', true);
            $this->getAttribute('Nom affiché', 'displayName');
            $this->getAttribute('Role', 'roles', false, 'choice', null, [
                'ROLE_USER',
                'ROLE_ADMIN',
                'ROLE_SUPER_ADMIN'
            ]);
            $this->getAttribute('Utilisateur vérifié', 'isVerified', false, 'boolean', 'true');


            try {
                $this->em->persist($this->user->hydrate($this->hasher));
                $this->em->flush();
            } catch (UniqueConstraintViolationException $e) {
                $this->io->error("Cet utilisateur existe déjà.");
            } catch (\Exception $e) {
                $this->io->error($e->getMessage());
                return Command::FAILURE;
            }


            $this->io->success('Utilisateur crée');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->io->error($e->getMessage());
            return Command::FAILURE;
        }


    }


    /**
     * @throws \Exception
     */
    public function getAttribute(string $question, string $attribute, $hidden = false, $type = "string", $default = null, $choiceList = null)
    {

        try {
            $this->attribute = $attribute;

            switch ($type) {
                case "boolean":
                    $question = new ConfirmationQuestion("$question : ", $default);
                    break;
                case "choice":
                    $question = new ChoiceQuestion(
                        "$question : ",
                        $choiceList,
                        $default
                    );
                    $question->setErrorMessage('Le choix %s est invalide.');
                    break;
                case "string":
                default:
                    $question = new Question("$question ", $default);
                    break;

            }

            // patch a hidden and autocompleter bug
            if ($type != 'choice') {
                $question->setHidden($hidden);
            }

            /** TODO: validate role array */
            $question->setValidator(function ($answer) {
                $method = 'set' . \ucfirst($this->attribute);
                $this->user->$method($answer);
                $violations = $this->validator->validateProperty($this->user, $this->attribute);
                if (count($violations) > 0) {
                    throw new \RuntimeException($violations[0]->getMessage());
                }
            });
            $this->io->askQuestion($question);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
