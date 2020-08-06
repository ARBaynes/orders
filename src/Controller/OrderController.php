<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private OrderRepository $orderRepository;
    private SerializerInterface $serializer;

    /**
     * OrderController constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @Route("/order/{orderId}", name="order_show")
     * @param int $orderId
     * @return JsonResponse
     */
    public function order(int $orderId): Response {
        $order = $this->orderRepository->find($orderId);
        if ($order instanceof Order) {
            return new Response($this->serializer->serialize($order, 'json'));
        }
        return new JsonResponse(["error" => "Order {$orderId} not found."]);
    }

    /**
     * @Route("/orders/", name="orders_show_all")
     * @return JsonResponse
     */
    public function orders(): Response {
        $orderSummaries = [];
        $orders = $this->orderRepository->findAll();
        foreach ($orders as $order) {
            $orderSummaries[] = [
                'order_id' => $order->getId(),
                'order_total_price' => $order->getTotalOrderPrice(),
                'order_item_quantities' => $order->getOrderItemQuantities()
            ];
        }
        return new Response($this->serializer->serialize($orderSummaries, 'json'));
    }
}
