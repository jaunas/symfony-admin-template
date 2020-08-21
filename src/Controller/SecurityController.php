<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(LoginType::class, [
            'email' => $authenticationUtils->getLastUsername()
        ]);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            $form->addError(new FormError($error->getMessageKey()));
        }

        return $this->render(
            'security/login.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // This method can be blank - it will be intercepted by the logout key on a firewall
        return null;
    }
}
