<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserStatusService;
use App\Enum\UserStatus as UserStatusEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route(['/Profile', '/profile'], name: 'profile')]
    public function Index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route(['/Edit/Profile', '/Edit/profile'], name: 'profile_edit')]
    public function EditProfile(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route(['/Edit/Status', '/Edit/status'], name: 'status_edit')]
    public function EditStatus(Request $request, UserStatusService $userStatusService)
    {
        $user = $this->getUser(); // assuming you have user authentication setup
        $status = $request->get('status');

        if (in_array($status, UserStatusEnum::cases())) {
            $userStatusService->updateStatus($user, UserStatusEnum::from($status));
            return $this->json(['status' => 'success']);
        }

        return $this->json(['status' => 'error', 'message' => 'Invalid status'], 400);
    }
}
