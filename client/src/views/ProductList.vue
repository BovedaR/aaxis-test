<template>
  <h1 class="title">Products List</h1>
  <div class="product-list">
    <div class="add-button-container">
      <button class="add-product-button" @click="productForm()">Add Product</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Sku</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="item in tableItems" :key="item.id">
          <tr>
            <td>{{ item.name }}</td>
            <td>{{ item.sku }}</td>
            <td>{{ item.description }}</td>
            <td class="actions">
              <button class="edit-button" @click="productForm(item.id)">Edit</button>
              <button class="remove-button" @click="deleteProduct(item.id)">Remove</button>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import { ProductService } from '../services/ProductService';
import Swal from 'sweetalert2';
import type { Product } from '../types/Product';
import type { TableItem } from '../types/TableItems';

const productService = new ProductService();

export default {
  name: "ProductList",
  data() {
    return {
      tableItems: [] as TableItem[],
      items: [] as Product[],
    };
  },
  created() {
    this.productList();
  },
  methods: {
    productForm(id?: number) {
      this.$router.push(`/products/form/${id ? id.toString(): ""}`);
    },
    async productList(){
      productService.getProducts().then((res) => {
        const data = res.data as unknown as Product[];
        if (data) {
          this.loadTable(data); 
        }
      }).catch((e) => {
        console.log(e);
      })
    },
    loadTable(data: Product[]){
      data.forEach((item) => {
        this.tableItems.push({
            id: item.id,
            name: item.name,
            sku: item.sku,
            description: item.description
          });
        });
    },
    async deleteProduct(id: number){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#243540',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          productService.deleteProduct(id).then((res) => {
            if (res.data){
              this.tableItems = [];
              this.productList();
            } 
          }).catch((e) => {
              console.log(e);
          })
        }
      })
    }
  },
};
</script>

<style scoped>
.product-list {
  margin: 20px;
}
.title {
  font-size: 2em;
  text-align: center;
  color: #4a4a4a;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.add-button-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px;
}

.add-product-button {
  padding: 8px 16px;
  background-color: #eea435;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  margin-left: 10px;
  transition: background-color 0.3s ease;
}

.add-product-button:hover {
  background-color: #eea435;
}

.table-container {
  overflow-x: auto;
}

.product-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
  word-wrap: break-word;
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.edit-button, .remove-button {
  padding: 8px 12px;
  background-color: #eea435b2;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.edit-button:hover, .remove-button:hover {
  background-color: #eea435fd;
}

.collection-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.actions {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}
</style>