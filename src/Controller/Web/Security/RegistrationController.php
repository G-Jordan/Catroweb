<?php

namespace App\Controller\Web\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
  protected VerifyEmailHelperInterface $verify_email_helper;
  protected EntityManagerInterface $entity_manager;

  public function __construct(VerifyEmailHelperInterface $verify_email_helper, EntityManagerInterface $entity_manager)
  {
    $this->verify_email_helper = $verify_email_helper;
    $this->entity_manager = $entity_manager;
  }

  /**
   * @Route("/register", name="register", methods={"GET"})
   */
  public function registerAction(): Response
  {
    return $this->render('security/registration/register.html.twig');
  }

  /**
   * Route: registration_confirmation_route -> must be possible from API + WEB.
   */
  public function verifyUserEmail(Request $request): Response
  {
    /** @var User|null $user */
    $user = $this->getUser();

    // Do not get the User's Id or Email Address from the Request object
    try {
      $this->verify_email_helper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
    } catch (VerifyEmailExceptionInterface $e) {
      $this->addFlash('verify_email_error', $e->getReason());

      return $this->redirectToRoute('register');
    }

    // Mark your user as verified. e.g. switch a User::verified property to true
    $user->setEnabled(true);
    $this->entity_manager->persist($user);
    $this->entity_manager->flush();

    $this->addFlash('success', 'Your e-mail address has been verified.');

    return $this->redirectToRoute('index');
  }
}
