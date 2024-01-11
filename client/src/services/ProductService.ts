import type { Product } from '../types/Product';
import { BaseService } from './BaseService';

export class ProductService extends BaseService {
  constructor() {
    super("api/products");
  }

  getProducts() {
    return this.get<Product[]>(``);
  }

  getProduct(id: number) {
    return this.get<Product>(`${id}`);
  }

  createProduct(data: any) {
    return this.post<Product>(``, data);
  }

  updateProduct(data: any) {
    return this.put<Product>(``, data);
  }

  deleteProduct(id: number) {
    return this.delete<Product>(`${id}`);
  }
}