<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    #[Route(path: '/dashboard', name: 'app_dashboard')]
    public function dashboard(Security $security, UserRepository $userRepository): Response
    {
        $user = $security->getUser();

        // Retrieve all users
        $users = $userRepository->findAll();

        // Initialize variables to count male and female users
        $maleCount = 0;
        $femaleCount = 0;

        // Count male and female users
        foreach ($users as $user) {
            // Assuming getGender() returns true for female and false for male
            if ($user->getGender()) {
                $femaleCount++;
            } else {
                $maleCount++;
            }
        }

        // Prepare data for the pie chart
        $genderData = [
            ['label' => 'Male', 'value' => $maleCount],
            ['label' => 'Female', 'value' => $femaleCount],
        ];

        return $this->render('admin/home.html.twig', [
            'user' => $user,
            'genderData' => $genderData,
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route(path: '/dashboard/users', name: 'app_dashboard_users')]
    public function userList(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();
        $users = $userRepository->findAll();

        return $this->render('admin/userList.html.twig', [
            'users' => $users,
            'user' => $user,
        ]);
    }

    #[Route(path: '/ban/{uid}', name: 'app_dashboard_ban')]
    public function ban(User $user, EntityManagerInterface $entityManager): Response
    {

        $user->setStatus(0);
        $user->setDeactivate('PERM');
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard_users');
    }

    #[Route(path: '/unban/{uid}', name: 'app_dashboard_unban')]
    public function unban(User $user, EntityManagerInterface $entityManager): Response
    {

        $user->setStatus(1);
        $user->setDeactivate(NULL);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard_users');
    }
}
