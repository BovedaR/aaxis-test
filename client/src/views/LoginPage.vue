<template>
  <div class="login-page">
    <div class="login-container" v-if="!showSignUp">
      <h1>Login</h1>
      <form @submit.prevent="login" class="login-form">
        <label for="username">Username:</label>
        <input type="username" id="username" v-model="loginData.username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="loginData.password" required>
        <br>
        <button type="submit">Sign In</button>
      </form>
      <p>Don't have an account? <a href="#" @click="toggleSignUp">Sign Up</a></p>
    </div>
    <div class="login-container" v-else>
      <h1>Sign Up</h1>
      <form @submit.prevent="signUp" class="signup-form">
        <label for="username">Username:</label>
        <input type="username" id="username" v-model="signUpData.username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="signUpData.email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="signUpData.password" required>
        <br>
        <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="#" @click="toggleSignUp">Sign In</a></p>
    </div>
  </div>
</template>

<script lang="ts">

import { AuthService } from '@/services/AuthService';
import Swal from 'sweetalert2';
import type { User } from '../types/User';

const authService = new AuthService();

export default {
  data() {
    return {
      showSignUp: false,
      loginData: {} as User,
      signUpData: {} as User
    };
  },
  methods: {
    async login() {
      await authService.login(this.loginData)
        .then((res) => {
          const data = res.data as unknown as User;
          if (data.token){
            localStorage.setItem('token', data.token);

            Swal.fire({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 1500,
              icon: 'success',
              title: 'Login Success!',
            })
            this.$router.push('/products');
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async signUp() {
      await authService.register(this.signUpData)
        .then((res) => {
          if (res.status === 200) {
            Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            icon: 'success',
            title: 'Register Success!',
          })

          this.toggleSignUp()

          this.loginData.username = this.signUpData.username;
          this.loginData.password = this.signUpData.password;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    toggleSignUp() {
      this.showSignUp = !this.showSignUp;
    }
  }
};
</script>

<style scoped>
.login-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background: linear-gradient(to left, #ffffff,#ececec,#ffffff);
  margin: 0;
}

h2 {
  margin-bottom: 20px;
}

.login-container{
  background-color: #ffffffbe;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

}

form {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
}

input {
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

button {
  padding: 10px 20px;
  background-color: #eea435;
  color: #fff;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

a {
  color: #eea435;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>