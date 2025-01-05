<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UserManagerInterface
{
    /**
     * Réalise toutes les opérations nécessaires avant l'enregistrement en base d'un nouvel utilisateur, après soumissions du formulaire
     */
    public function processNewUtilisateur(User $user, ?string $plainPassword): void;
    public function setUniqueCode(User $user, ?string $code_user): void;
}