<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FlashMessageHelperInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserManagerInterface $userManager,
        private EntityManagerInterface $entityManager,
        private FlashMessageHelperInterface $flashMessageHelper
    ) {}

    #[Route('/', name: 'annuaire', methods: ["GET"])]
    public function annuaire(
        UserRepository $userRepository,
        Request $request,
    ): Response
    {
        $page = $request->query->getInt('page', 0);
        $isAdmin = $this->isGranted('PERM_VIEW', $this->getUser());
        $users = $userRepository->findUserPaginated($page, $isAdmin);
        return $this->render("informations/annuaire.html.twig", ["users" => $users]);
    }

    #[Route('/signUp', name: 'signUp', methods: ["GET", "POST"])]
    public function signUp(Request $request) : Response
    {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('annuaire');
        }

        $user = new User();
        $formSignUp = $this->createForm(UserType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('signUp'),
        ]);

        //Traitement du formulaire
        $formSignUp->handleRequest($request);
        $this->flashMessageHelper->addFormErrorsAsFlash($formSignUp);
        if($formSignUp->isSubmitted() && $formSignUp->isValid()) {
            // À ce stade, le formulaire et ses données sont valides
            $plainPassword = $formSignUp["plainPassword"]->getData();
            $code = $formSignUp["code"]->getData();

            $this->userManager->processNewUtilisateur($user, $plainPassword);
            $this->userManager->setUniqueCode($user, $code);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('annuaire');
        }

        return $this->render('user/signUp.html.twig', [
            "formSignUp" => $formSignUp,
        ]);
    }

    #[Route('/signIn', name: 'signIn', methods : ["GET", "POST"])]
    public function connexion(AuthenticationUtils $authenticationUtils) : Response {
        if($this->isGranted('ROLE_USER')) return $this->redirectToRoute('annuaire');
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/signIn.html.twig', ['lastUsername' => $lastUsername]);
    }

    #[Route('/update/{code}', name: 'user_update', methods: ["GET", "POST"])]
    #[IsGranted(new Expression("is_granted('ROLE_USER')"))]
    public function update(Request $request, ?User $userupdate) : Response
    {
        if($userupdate == null ){
            $this->addFlash('error', 'Utilisateur inexistant');
            return $this->redirectToRoute('annuaire');
        }

        if($this->getUser()->getLogin() != $userupdate->getLogin()) {
            $this->addFlash('error', 'Ce n\'est pas votre profil !');
            return $this->redirectToRoute('annuaire');
        }

        $formUpdate = $this->createForm(ProfilType::class, $userupdate, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_update', ["code"=>$userupdate->getCode()]),
        ]);

        //Traitement du formulaire
        $formUpdate->handleRequest($request);
        $this->flashMessageHelper->addFormErrorsAsFlash($formUpdate);
        if($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $code_user = $formUpdate["code"]->getData();

            $this->userManager->setUniqueCode($userupdate,$code_user);
            $this->entityManager->persist($userupdate);
            $this->entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('annuaire');
        }

        return $this->render('user/update.html.twig', [
            "formUpdate" => $formUpdate,
            "codeUser" => $userupdate->getCode(),
        ]);
    }

    #[Route('/profil/{code}', name: 'Ownpage', methods: ["GET"])]
    #[IsGranted('ROLE_USER')]
    public function pagePerso($code): Response
    {
        $user = $this->userRepository->findByCode($code);
        if ($user == null) {
            $this->addFlash('error', 'Utilisateur inexistant');
            return $this->redirectToRoute('annuaire');
        }
        return $this->render('user/ownPage.html.twig',[
            'user' => $user,
        ]);
    }

    #[Route('/delete/{code}', name: 'delete', methods: ["GET"])]
    #[IsGranted('ROLE_USER')]
    public function deleted(?User $userdeleted, EntityManagerInterface $entityManager) : Response
    {
        if(is_null($userdeleted)) {
            $this->addFlash('error', 'Utilisateur inexistant');
            return $this->redirectToRoute('annuaire');
        }
        if($this->getUser()->getCode() != $userdeleted->getCode() && !$this->isGranted('PERM_DELETED', $userdeleted)){
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce profil');
            return $this->redirectToRoute('Ownpage', ['code' => $userdeleted->getCode()]);
        }
        if($this->getUser()->getCode() == $userdeleted->getCode()) {
            $this->container->get('security.token_storage')->setToken(null);
        }
        $entityManager->remove($userdeleted);
        $entityManager->flush();

        return $this->redirectToRoute('annuaire');

    }
}
