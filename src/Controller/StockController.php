<?php

namespace App\Controller;

use App\Entity\StockLog;
use App\Service\AlphavantageClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class StockController extends AbstractController
{
    #[Route('/api/stock', name: 'get_stock', methods: ['GET'])]
    public function getStock(
        Request $request,
        EntityManagerInterface $em,
        AlphavantageClient $alphavantageClient,
        MailerInterface $mailer
    ): JsonResponse {
        $symbol = $request->query->get('q');
        $globalQuote = $alphavantageClient->getGlobalQuote($symbol);

        if (!$globalQuote) {
            return new JsonResponse(['message' => 'Invalid symbol or Alphavantage API limit reached'], Response::HTTP_BAD_REQUEST);
        }

        $log = $this->storeStockLog($symbol, $globalQuote, $em);

        $this->sendEmail($symbol, $log, $mailer);

        return new JsonResponse([
            'symbol' => $log->getSymbol(),
            'open' => $log->getOpen(),
            'high' => $log->getHigh(),
            'low' => $log->getLow(),
            'close' => $log->getClose(),
        ]);
    }

    #[Route('/api/stock/history', name: 'get_history', methods: ['GET'])]
    public function getHistory(EntityManagerInterface $em): JsonResponse
    {
        $history = $em->getRepository(StockLog::class)
            ->findAllByUserOrderedByDate($this->getUser());

        return new JsonResponse($history);
    }

    private function storeStockLog(string $symbol, array $globalQuote, EntityManagerInterface $em): StockLog
    {
        $log = (new StockLog())
            ->setUser($this->getUser())
            ->setSymbol($symbol)
            ->setOpen($globalQuote['02. open'])
            ->setHigh($globalQuote['03. high'])
            ->setLow($globalQuote['04. low'])
            ->setClose($globalQuote['08. previous close'])
            ->setDate(new \DateTime());

        $em->persist($log);
        $em->flush();
        return $log;
    }

    private function sendEmail(string $symbol, StockLog $log, MailerInterface $mailer): void
    {
        $email = (new TemplatedEmail())
            ->to($this->getUser()->getEmail())
            ->subject('Your Stock Quote for ' . $symbol)
            ->htmlTemplate('emails/quote.html.twig')
            ->context([
                'quote' => $log,
            ]);

        $mailer->send($email);
    }
}
