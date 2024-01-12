import type { BaseEntity } from "./BaseEntity";

export type Product = {
  name: string;
  sku: string;
  description: string;
} & BaseEntity;