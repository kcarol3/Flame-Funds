<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function index(ValidatorInterface $validator,ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;
        $username = $decoded->username;
        $plaintextPassword = $decoded->password;

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setIsDeleted(false);

        $errors = $validator->validate($user);
        if(count($errors) > 0){
            $errorsString = (string) $errors;
            return new Response($errorsString, 422);
        } else {
//            $em->persist($user);
//            $em->flush();
        }

        return $this->json(['message' => 'Registered Successfully']);
    }
}
