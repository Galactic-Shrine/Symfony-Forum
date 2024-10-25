<?php

namespace App\Controller;

use App\Form\MessagesFormType;
use App\Entity\MessagingMessages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')] 
#[Route(['/Messaging','/messaging'], name: 'app_messaging_')]
class MessagingController extends AbstractController {

    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(name: 'message')]
    public function Index(): Response {
        return $this->render('Messaging/index.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    #[Route(['/Send','/send'], name: 'send')]
    public function Send(Request $Request): Response {

        $Messenger = new MessagingMessages();
        $Form = $this->createForm(MessagesFormType::class, $Messenger);

        $Form->handleRequest($Request);

        if ($Form->isSubmitted() && $Form->isValid()) {

            $Messenger->setSender($this->getUser());
            
            $this->entityManager->persist($Messenger);
            $this->entityManager->flush();

            $this->addFlash('success', 'Message envoyé');
            return $this->redirectToRoute('app_messaging_messenge');
        }

        return $this->render('Messaging/Send.twig', [
            'Form' => $Form->createView()
        ]);
    }

    #[Route(['/Read/{Id}','/read/{Id}'], name: 'read')]
    public function Read(MessagingMessages $Message): Response {

        $Message->setRead(true);
        $this->entityManager->persist($Message);
        $this->entityManager->flush();
    
        return $this->render('Messaging/Read.twig', [
            compact('Message')
        ]);
    }

    #[Route(['/Delete/{Id}','/delete/{Id}'], name: 'delete')]
    public function Delete(MessagingMessages $Message): Response {

        if ($Message->IsDeletedByRecipient() && $Message->IsDeletedBySender() == true) {

            $this->entityManager->remove($Message);
        }

        $this->entityManager->flush();
    
        return $this->redirectToRoute('app_messaging_messenge');
    }
}
