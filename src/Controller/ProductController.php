<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_details')]
    public function show(Product $product, HttpClientInterface $client): Response
    {
        $responseDollar = $client->request(
            'GET',
            'https://v6.exchangerate-api.com/v6/' 
                . $_ENV['CURRENCY_KEY'] 
                . '/pair/EUR/USD/' 
                . $product->getPrice()
        );
        $contentDollar = $responseDollar->toArray();
        $dollarPrice = $contentDollar['conversion_result'];
    
        $responseYen = $client->request(
            'GET',
            'https://v6.exchangerate-api.com/v6/' 
                . $_ENV['CURRENCY_KEY'] 
                . '/pair/EUR/JPY/' 
                . $product->getPrice()
        );
        $contentYen = $responseYen->toArray();
        $yenPrice = $contentYen['conversion_result'];
    
        return $this->render('product/details.html.twig', [
            'product' => $product,
            'dollar_price' => $dollarPrice,
            'yen_price' => $yenPrice,
        ]);
    }
    
    
}
