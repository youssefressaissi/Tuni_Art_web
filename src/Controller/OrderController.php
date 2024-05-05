<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }


    #[Route('/trieasc', name: 'app_trieasc', methods: ['GET'])]
    public function ascendingAction(OrderRepository $orderRepository)
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAllAscending(),
        ]);
    }
    
    
    #[Route('/triedesc', name: 'app_triedesc', methods: ['GET'])]
    public function descendingAction(OrderRepository $orderRepository)
    {
    
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAllDescending(),
        ]);
    
    }



  #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $order = new Order();
    $order->setStatus(false); // Set initial status to false
    $form = $this->createForm(OrderType::class, $order);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $order->setStatus(true); // Update status to true when form is submitted and valid
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('order/new.html.twig', [
        'order' => $order,
        'form' => $form,
    ]);
}



#[Route('/stats', name: 'app_stat', methods: ['GET'])]
    public function statistics(OrderRepository $orderRepository): Response
    {
        $repository = $this->getDoctrine()->getRepository(Order::class);

        $data = $repository->createQueryBuilder('v')
            ->select('v.status')
            ->addSelect('COUNT(v.orderId) as totalstatus')
            ->addSelect('SUM(CASE WHEN v.status = :BMinus THEN 1 ELSE 0 END) as bCount')
            ->addSelect('SUM(CASE WHEN v.status = :BPlus THEN 1 ELSE 0 END) as bbCount')

            ->setParameter('BMinus', 'Sold')
            ->setParameter('BPlus', 'Not Sold')

            ->groupBy('v.status')
            ->getQuery()
            ->getResult();



        return $this->render('order/chart.html.twig', [
            'data' => $data,
        ]);
    }

    
    #[Route('/neww', name: 'app_order_admin_new', methods: ['GET', 'POST'])]
    public function neww(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/new_admin.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{orderId}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{orderId}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{orderId}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getOrderId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
