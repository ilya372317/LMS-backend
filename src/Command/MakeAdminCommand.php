<?php

namespace App\Command;

use App\CLI\CommandLine;
use App\Entity\User;
use App\Enum\User\UserRole;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:make-admin',
    description: 'make default user to admin',
    aliases: ['app:add-admin'],
    hidden: false
)]
class MakeAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp('this command make default user become to admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        /** @var User $user */
        foreach ($users as $user) {
            $output->writeln("[{$user->getId()}] {$user->getUsername()}\n");
        }
        $output->writeln("");
        $output->writeln("select user, to make him admin (type id in brace)");

        $commandLine = new CommandLine();
        $test = $commandLine->getUserInput();

        dd($test);

        $userId = 1;

        $userToUpdate = $userRepository->find((int) $userId);
        $userToUpdate->addRole(UserRole::ADMIN);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }


}