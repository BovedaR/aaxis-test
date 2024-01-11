<?php

namespace App\Controller;

use App\Entity\Product;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends BaseController
{
    #[Route('/products', name: 'product_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        // get all products from database and return as and array

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $products = array_map(function($product) {
            return $product->toArray();
        }, $products);
        
        return new JsonResponse($products, JsonResponse::HTTP_OK);
    }

    #[Route('/products', name: 'product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];
        $products = [];
        foreach ($data as $dataProduct){
            $sku = $dataProduct['sku'] ?? null;
            $name = $dataProduct['name'] ?? null;
            $description = $dataProduct['description'] ?? null;
            
            if (!$sku) $errors[] = 'Expecting mandatory parameters: sku';
            if (!$name) $errors[] = 'Expecting mandatory parameters: name';

            // check if product with same sku exists
            $productSku = $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => $sku]);
            if (!empty($productSku)) $errors[] = 'Product with sku ' . $dataProduct['sku'] . ' already exists';

            // if any error, skip this product
            if (!empty($errors)) continue;

            $product = new Product();
            $product->setSku($dataProduct['sku']);
            $product->setProductName($dataProduct['name']);
            
            if ($description) $product->setDescription($description);

            $products[] = $product;
        }

        if (!empty($errors)) {
            $this->logger->error('Error creating products: ' . implode(', ', $errors));
            return new JsonResponse(['message' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        foreach ($products as $product) {
            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Products created successfully'], JsonResponse::HTTP_OK);
    }

    #[Route('/products/{id}', name: 'product_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        // check if product exists
        if (empty($product)) {
            $this->logger->error('Error showing product: Product with id ' . $id . ' not found');
            return new JsonResponse(['message' => 'Product with id ' . $id . ' not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        return new JsonResponse($product->toArray(), JsonResponse::HTTP_OK);
    }

    #[Route('/products', name: 'product_update', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];
        foreach ($data as $dataProduct){
            $id = $dataProduct['id'] ?? null;
            $sku = $dataProduct['sku'] ?? null;
            $name = $dataProduct['name'] ?? null;
            $description = $dataProduct['description'] ?? null;

            if (!$id) {
                $errors[] = 'Expecting mandatory parameters: id ' . ($sku ? 'for product ' . $sku : '');
                continue;
            }
            
            $productSku = $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => $sku]);
            if (!empty($productSku) && $productSku->getId() != $id) {
                $errors[] = 'Product with sku ' . $sku . ' already exists';
                continue;
            }

            $product = $this->entityManager->getRepository(Product::class)->find($id);
            
            if (empty($product)) {
                $errors[] = 'Product with id ' . $id . ' not found for sku ' . $sku;
                continue;
            }

            if ($sku && $product->getSku() != $sku) {
                $product->setSku($dataProduct['sku']);
            }

            if ($name && $product->getProductName() != $name) {
                $product->setProductName($name);
            }
            
            if ($description && $product->getDescription() != $description) {
                $product->setDescription($description);
            }
        }

        if (!empty($errors)) {
            $this->logger->error('Error updating products: ' . implode(', ', $errors));
            return new JsonResponse(['message' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Products updated successfully'], JsonResponse::HTTP_OK);
    }

    #[Route('/products/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        // check if product exists
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (empty($product)) {
            $this->logger->error('Error deleting product: Product with id ' . $id . ' not found');
            return new JsonResponse(['message' => 'Product with id ' . $id . ' not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Product deleted'], JsonResponse::HTTP_OK);
    }
}