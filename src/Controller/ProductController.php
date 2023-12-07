<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CurrencyService;
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
    public function show(Product $product, CurrencyService $currencyService): Response
    {
        $dollarPrice = $currencyService->convertEurToDollar($product->getPrice());
        $yenPrice = $currencyService->convertEurToYen($product->getPrice());
    
        return $this->render('product/details.html.twig', [
            'product' => $product,
            'dollar_price' => $dollarPrice,
            'yen_price' => $yenPrice,
        ]);
    }
    
}
