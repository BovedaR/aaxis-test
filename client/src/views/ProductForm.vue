<template>
  <div class="product-form">
    <h2 class="title">{{ formTitle }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="product.name" required>
      </div>
      <div class="form-group">
        <label for="sku">SKU:</label>
        <input type="text" id="sku" v-model="product.sku" required>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <input type="text" id="description" v-model="product.description" required>
      </div>
      <div class="button-group">
        <button type="reset" @click="cancel">Cancel</button>
        <button type="submit">{{ submitButtonLabel }}</button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import { ProductService } from '../services/ProductService';
import type { Product } from '../types/Product';
import Swal from 'sweetalert2';

const productService = new ProductService();

export default {
  name: "ProductForm",
  data() {
    return {
      product: {} as Product,
    };
  },
  props: ['id'],
  created() {
    // Fetch the Product data if the form is in edit mode
    if (this.$route.params.id) {
      const id = Number(this.$route.params.id);
      this.getProduct(id)
    }
  },
  computed: {
    formTitle() {
      return this.$route.params.id ? 'Edit Product' : 'Add Product';
    },
    submitButtonLabel() {
      return this.$route.params.id ? 'Update' : 'Submit';
    }
  },
  methods: {
    async getProduct(id: number){
      await productService.getProduct(id).then((res) => {
        if(res.data){
          this.product = res.data as unknown as Product;
        } 
        else this.$router.push('/products');
      }).catch(() => {
        this.$router.push('/products');
      }) 
    },
    async submit(){
      if (this.$route.params.id) {
        await productService.updateProduct([this.product]).then((res) => {
          if(res?.data){
            Swal.fire({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 1500,
              icon: 'success',
              title: 'Success!',
            })
            this.$router.push('/products');
          }
        }).catch((e) => {
          console.log(e);
        }) 
      } 
      else {
        await productService.createProduct([this.product]).then((res) => {
          if (res?.data){
            Swal.fire({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 1500,
              icon: 'success',
              title: 'Success!',
            })
            this.$router.push('/products');
          }
        }).catch((e) => {
          console.log(e);
        }) 
      }
    },
    cancel() {
      this.$router.go(-1);
    },
  }
};
</script>

<style scoped>
.product-form {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
  border-radius: 10px;
  background-color: #f7f7f796;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.title {
  font-size: 2em;
  text-align: center;
  color: #4a4a4a;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
  color: #555;
}

input[type="text"],
select {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

button[type="submit"] {
  display: block;
  width: 100%;
  padding: 14px;
  background-color: #eea435;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #eea435b0;
}
button[type="reset"] {
  display: block;
  width: 100%;
  padding: 14px;
  background-color: #222222;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  transition: background-color 0.3s ease;
}

button[type="reset"]:hover {
  background-color: #222222b7;
}

.button-group {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}
</style>