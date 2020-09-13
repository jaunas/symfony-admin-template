<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @Template()
     *
     * @return array<string, mixed>
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder): array
    {
        $changePasswordForm = $this->createForm(ChangePasswordType::class);

        $changePasswordForm->handleRequest($request);
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $password = $changePasswordForm->getData()['password'];

            /** @var User $user */
            $user = $this->getUser();
            $this
                ->getDoctrine()
                ->getRepository(User::class)
                ->upgradePassword($user, $passwordEncoder->encodePassword($user, $password));
        }

        return [
            'changePasswordForm' => $changePasswordForm->createView(),
        ];
    }

    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser()) {
            $this->redirectToRoute('dashboard');
        }

        $registerForm = $this->createForm(RegisterType::class);

        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $user = $registerForm->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/register.html.twig', [
            'form' => $registerForm->createView(),
        ]);
    }
}
