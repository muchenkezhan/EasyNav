import { createRouter, createWebHashHistory } from 'vue-router';
import Layout from '../layout/index.vue';
import Index from '@/views/Index/index.vue';
import User from '@/views/User/index.vue';
import Account from '@/views/User/components/Account/index.vue';
import Personalise from '@/views/User/components/Personalise/index.vue';
import Preferences from '@/views/User/components/Preferences/index.vue';
import Server from '@/views/User/components/Server/index.vue';

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '',
      component: Layout,
      children: [
        {
          path: '',
          name: 'index',
          component: Index
        },
        {
          path: 'user',
          name: 'user',
          component: User,
          children: [
            {
              path: '',
              name: 'account',
              component: Account,
            },
            {
              path: 'personalise',
              name: 'personalise',
              component: Personalise
            },
            {
              path: 'preferences',
              name: 'preferences',
              component: Preferences
            },
            {
              path: 'server',
              name: 'server',
              component: Server
            },
          ]
        },
      ]
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.

    }
  ],
});

export default router;
