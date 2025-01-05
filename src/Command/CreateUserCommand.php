<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crée un utilisateur',
)]
class CreateUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager,private UserRepository $userRepository,private UserManagerInterface $userManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crée un nouvel utilisateur.')
            ->addArgument('login',InputArgument::REQUIRED, 'Login de l\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l\'utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur')
            ->addArgument('visibility', InputArgument::OPTIONAL, 'Visibilité de l\'utilisateur', true)
            ->addArgument('code', InputArgument::OPTIONAL, 'Code de l\'utilisateur')
            ->addOption('country','co', InputOption::VALUE_OPTIONAL, 'Définir le pays de l\'utilisateur')
            ->addOption('department','dep', InputOption::VALUE_OPTIONAL, 'Définir le département de l\'utilisateur')
            ->addOption('postal_adresse','pa', InputOption::VALUE_OPTIONAL, 'Définir l\'adresse postal de l\'utilisateur')
            ->addOption('last_name','ln', InputOption::VALUE_OPTIONAL, 'Définir le nom de l\'utilisateur')
            ->addOption('first_name','fn', InputOption::VALUE_OPTIONAL, 'Définir le prénom de l\'utilisateur')
            ->addOption('function','f', InputOption::VALUE_OPTIONAL, 'Définir le métier de l\'utilisateur')
            ->addOption('roles', 'r', InputOption::VALUE_OPTIONAL, 'Définir les rôles de l\'utilisateur')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        if (!$input->getArgument('login')) {
            $question = $io->ask('Quel est le login de l\'utilisateur ? ');
            $input->setArgument('login', $question);
        }
        if (!$input->getArgument('password')) {
            $question = $io->askHidden('Quel est le mot de passe de l\'utilisateur ?');
            $input->setArgument('password', $question);
        }
        if (!$input->getArgument('email')) {
            $question = $io->ask('Quel est l\'email de l\'utilisateur ?');
            $input->setArgument('email', $question);
        }
        if (!$input->getArgument('visibility')) {
            $visibility = $io->confirm('Souhaitez-vous que l\'utilisateur soit visible ?');
            $input->setArgument('visibility', $visibility);
        }
        if (!$input->getArgument('code')) {
            $question = $io->ask('Quel est le code de l\'utilisateur ? ');
            $input->setArgument('code', $question);
        }
        if (!$input->getOption('country')) {
            $question = $io->ask('Quel est le pays de l\'utilisateur ? ');
            $input->setOption('country', $question);
        }
        if (!$input->getOption('department')) {
            $question = $io->ask('Quel est le departement de l\'utilisateur ?');
            $input->setOption('department', $question);
        }
        if (!$input->getOption('postal_adresse')) {
            $question = $io->ask('Quel est l\'adresse postale de l\'utilisateur ? ');
            $input->setOption('postal_adresse', $question);
        }
        if (!$input->getOption('last_name')) {
            $question = $io->ask('Quel est le nom de l\'utilisateur ? ');
            $input->setOption('last_name', $question);
        }
        if (!$input->getOption('first_name')) {
            $question = $io->ask('Quel est le prénom de l\'utilisateur ? ');
            $input->setOption('first_name', $question);
        }
        if (!$input->getOption('function')) {
            $question = $io->ask('Quel est le metier de l\'utilisateur ?');
            $input->setOption('function', $question);
        }
        if (!$input->getOption('roles')){
            $list = ['ROLE_USER' , 'ROLE_ADMIN'];
            $role =$io->choice('Choisissez le role de l\'utilisateur (0 ou 1)', $list,'ROLE_USER', false);
            if ($role == 'ROLE_USER') {
                $role = '';
            }
            $input->setOption('roles', $role);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symphony = new SymfonyStyle($input, $output);

        $login = $input->getArgument('login');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $code = $input->getArgument('code');
        $country = $input->getOption('country');
        $department = $input->getOption('department');
        $postal_adresse = $input->getOption('postal_adresse');
        $last_name = $input->getOption('last_name');
        $first_name = $input->getOption('first_name');
        $function = $input->getOption('function');
        $role = $input->getOption('roles');

        $user = new User();
        if ($login) {
            $symphony->note(sprintf('Login: %s', $login));
            $user->setLogin($login);
        }
        if ($password) {
            $this->userManager->processNewUtilisateur($user, $password);
        }
        if ($email) {
            $symphony->note(sprintf('Email: %s', $email));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $symphony->error('Email pas au bon format :' . $email);
                return Command::INVALID;
            }
            $user->setEmail($email);
        }
        if ($code){
            $symphony->note(sprintf('Code de l\'utilisateur: %s', $code));
            if (!is_null($this->userRepository->findByCode($code))){
                $symphony->error(sprintf('Code %s déjà pris',$code));
                return Command::INVALID;
            }
            $user->setCode($code);
        }
        else{
            $this->userManager->setUniqueCode($user,$code);
            $symphony->note(sprintf('Code de l\'utilisateur: %s', $user->getCode()));
        }
        if ($input->getArgument('visibility')) {
            $symphony->note('L\'utilisateur est visible');
            $user->setVisibility(true);
        }
        if ($country){
            $symphony->note(sprintf('Le pays de l\'utilisateur: %s', $country));
            $user->setCountry($country);
        }
        if ($department){
            $symphony->note(sprintf('Le département de l\'utilisateur: %s', $department));
            $user->setDepartment($department);
        }
        if ($postal_adresse){
            $symphony->note(sprintf('L\'adresse postal de l\'utilisateur: %s', $postal_adresse));
            $user->setPostalAdresse($postal_adresse);
        }
        if ($last_name){
            $symphony->note(sprintf('Le nom de l\'utilisateur: %s', $last_name));
            $user->setLastName($last_name);
        }
        if ($first_name){
            $symphony->note(sprintf('Le prénom de l\'utilisateur: %s', $first_name));
            $user->setFirstName($first_name);
        }
        if ($function){
            $symphony->note(sprintf('Le metier de l\'utilisateur: %s', $function));
            $user->setFunction($function);
        }
        if($role){
            $symphony->note(sprintf('Le role de l\'utilisateur: %s', $role));
            $user->addRole($role);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $symphony->success('Utilisateur crée');

        return Command::SUCCESS;
    }
}
