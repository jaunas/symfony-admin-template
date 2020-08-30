<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            $user->setPassword($passwordEncoder->encodePassword($user, $password));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
        }

        return [
            'changePasswordForm' => $changePasswordForm->createView(),
        ];
    }
}
