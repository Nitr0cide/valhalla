<?php

namespace App\Controller;

use App\Entity\AccountType;
use App\Entity\UserDocuments;
use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\DocumentsRepository;
use App\Repository\UsersRepository;
use ContainerENym6bk\getAccountTypeRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;


class UsersController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    /**
     * @Route("/users/profile/{userName}", name="users_index", methods={"GET"}, priority=10)
     */
    public function index(UsersRepository $usersRepository, DocumentsRepository $docRepo, string $userName): Response
    {
        $user = $this->doesUserExist($usersRepository, $userName);

        if (!$user)
        {
            return $this->render('security/404.html.twig')
                ->setStatusCode(404);
        }

        $currentUser = $this->getUser() ? $this->getUser()->getUserLogin() : null;

        if ($userName === $currentUser) {
            $user = $usersRepository->findOneBy(["login" => "$userName"]);



            return $this->render('users/profile.html.twig', [
                'users' => $user,
                'entreprises' => $user->getCompanies(),
                'documents' => $user->getUserDocuments(),
            ]);
        }


        return $this->render('users/userPage.html.twig', [
           'users' => $usersRepository->findOneBy(["login" => "$userName"]),
        ]);

    }


    /**
     * @Route("/register", name="users_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('users_index', ['userName' => $this->getUser()->getUserLogin()])
                ->setStatusCode('301');
        }

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);

            $user->setPassword($encoded);
            $user->setRoles(["ROLE_USER"]);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users_index', ["userName" => $user->getUserLogin()]);
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/{id}", name="users_show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="users_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(password_hash($request->request->get('password'), PASSWORD_DEFAULT));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/{id}", name="users_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users_index', ['userName' => $this->getUser()->getUserLogin()]);
    }

    /**
     * @Route("/users/verifier-identite", name="users_verif", methods={"GET", "POST"}, priority=100)
     */
    public function uploadID(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
        ->add('account_ID_path', FileType::class)
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_index', ['userName' => $this->getUser()->getUserLogin()]);
        }

        return $this->render('users/'.__FUNCTION__.'.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
