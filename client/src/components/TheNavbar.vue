<template>
    <div class="nav">
        <h2 class="nav__top" @click="products">
            <img src="/aaxis-logo.webp" alt="logo" />
        </h2>
        <h2 class="nav__mid"></h2>
        <button class="nav__bot" @click="logout">Logout</button>
    </div>
</template>

<script lang="ts">

export default {
    name: 'TheNavbar',
    methods: {
        logout(){
            localStorage.removeItem('token');
            this.$router.push('/login');
        },
        products(){
            this.$router.push('/products');
        },
    }
}
</script>
<style scoped>
.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1em 2em;
  background-color: rgba(0, 0, 0, 1);
  margin-bottom: 2em;
  color: #fff;
}


.nav__bot {
  padding: 0.5em 1em;
  background-color: rgba(0, 0, 0, 1);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.nav__bot:hover {
  background-color: #eea435;
  color: #000;
}

</style>