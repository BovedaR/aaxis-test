import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/products',
      name: 'products-list',
      component: () => import('../views/ProductList.vue'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/products/form/:id?',
      name: 'products-form',
      component: () => import('../views/ProductForm.vue'),
      props: true,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginPage.vue'),
      meta: {
        hideNavigation: true
      }
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/products'
    }
  ]
})

router.beforeEach((to, _from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('token')) next('/login');
  next();
});


export default router
