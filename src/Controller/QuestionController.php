<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @param QuestionRepository $repository
     * @return Response
     */
    public function homepage(QuestionRepository $repository): Response
    {
        $questions = $repository->findAllAskedOrderedByNewest();
        return $this->render('question/homepage.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function show(Question $question, EntityManagerInterface $entityManager): Response
    {
        $answers = [
            'Make sure your cat is sitting purrrfectly still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("/question/new")
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    public function new(EntityManagerInterface $entityManager){
        return new Response('OK');
    }

    /**
     * @param Question $question
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     * @Route("/question/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $direction = $request->request->get('direction');
        if ($direction === 'up'){
            $question->upVote();
        } elseif ($direction === 'down') {
            $question->downVote();
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug()
        ]);
    }
}
