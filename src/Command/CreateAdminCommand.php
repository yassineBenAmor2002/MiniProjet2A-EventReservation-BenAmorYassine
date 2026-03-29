<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:admin:create',
    description: 'Créer un compte administrateur (username + password hashé).'
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username admin')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = (string) $input->getArgument('username');
        $plainPassword = (string) $input->getArgument('password');

        $repo = $this->em->getRepository(Admin::class);
        $existing = $repo->findOneBy(['username' => $username]);
        if ($existing) {
            $io->error('Un admin avec ce username existe déjà.');
            return Command::FAILURE;
        }

        $admin = new Admin();
        $admin->setUsername($username);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $plainPassword));

        $this->em->persist($admin);
        $this->em->flush();

        $io->success('Admin créé. Tu peux te connecter via /admin/login');
        return Command::SUCCESS;
    }
}
