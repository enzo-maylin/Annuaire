<?php

namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager implements UserManagerInterface
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ){}

    /**
    * Chiffre le mot de passe puis l'affecte au champ correspondant dans la classe de l'utilisateur
    */
    private function hashPassword(User $user, ?string $plainPassword) : void {
        $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashed);
    }
    /**
     * crée un code unique pour l'utilisateur
     * Si le code
     */
    public function setUniqueCode(User $user,?string $code_user) : void {
        if($code_user=="" || strlen($code_user)==0 ) {
            $code = uniqid($user->getId(), true);
            $alphaNumerique = preg_replace("/[^a-zA-Z0-9]/", 0, $code);
            $codeLenght32 = substr($alphaNumerique, 0, 32);
            $user->setCode($codeLenght32);
        }
    }

    /**
    * Réalise toutes les opérations nécessaires avant l'enregistrement en base d'un nouvel utilisateur, après soumissions du formulaire (hachage du mot de passe, sauvegarde de la photo de profil...)
    */
    public function processNewUtilisateur(User $user, ?string $plainPassword) : void {
        $this->hashPassword($user, $plainPassword);
    }
}