<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(Question $question, EntityManagerInterface $entityManager)
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
        $question = new Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-'.rand(0, 1000))
            ->setQuestion('Twice is the best');
        if (rand(1, 10) > 2) {
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }
        $question->setVote(rand(-20,50));
        $entityManager->persist($question);
        $entityManager->flush();
        return new Response('Time for some Doctrine magic!');
    }
}
