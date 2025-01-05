<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class APIUserControleurController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {}
    #[Route('api/checkCode', name: 'check_code',options: ["expose" => true], methods: ['POST'])]
    public function checkCode(Request $request): JsonResponse
    {
        $code = $request->get('code');
        $user=$this->userRepository->findByCode($code);

        if (!is_null($user) && $this->getUser()->getUserIdentifier() != $user->getLogin()) {
            return new JsonResponse([$code,$user],Response::HTTP_CONFLICT);
        }
        return new JsonResponse([$code,$user],Response::HTTP_OK);
    }

    #[Route('api/userJSON', name: 'userJSON',options: ["expose" => true], methods: ['GET'])]
    public function userJSON(Request $request): JsonResponse
    {
        $code = $request->get('code');
        $user=$this->userRepository->findByCode($code);
        if (is_null($user)) {
            return $this->json($user, Response::HTTP_NOT_FOUND);
        }
        $userDataPublic = [
            'login' => $user->getLogin(),
            'email' => $user->getEmail(),
            'mobileNumber' => $user->getMobileNumber(),
            'landlineNumber' => $user->getLandlineNumber(),
            'updatedAt' => $user->getUpdatedAt()?->format('Y-m-d\TH:i:sP'),
            'country' => $user->getCountry(),
            'department' => $user->getDepartment(),
            'function' => $user->getFunction(),
            'postalAdresse' => $user->getPostalAdresse(),
            'lastName' => $user->getLastName(),
            'firstName' => $user->getFirstName(),
            'lastSignin' => $user->getLastSignin()?->format('Y-m-d\TH:i:sP'),
        ];
        return $this->json($userDataPublic,  Response::HTTP_OK);
    }

}
