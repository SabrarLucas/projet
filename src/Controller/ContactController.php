<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact', name: 'contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        SendMailService $mail
        ): Response
    {
        $contact = new Contact();

        if($this->getUser()){
            $contact->setName($this->getUser()->getName())
                ->setLastname($this->getUser()->getLastName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            $mail->send(
                $contact->getEmail(),
                'no-reply@ptitgrain.fr',
                $contact->getSujet(),
                'email',
                [
                    'sujet' => $contact->getSujet(),
                    'message' => $contact->getMessage(),
                ]
            );

            $this->addFlash('success','Le message a bien été envoyer');
                return $this->redirectToRoute('contact_index');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
