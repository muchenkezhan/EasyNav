import { fileURLToPath, URL } from 'node:url'
// elemnet按需引入
import { defineConfig } from 'vite'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue(),
  AutoImport({
    resolvers: [ElementPlusResolver()],
  }),
  Components({
    resolvers: [ElementPlusResolver()],
  }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
    //  配置代理跨域
    server: {
      proxy: {
        // https://navadmin.aj0.cn  更改为自己的网站
        '/jwt-auth': {
          target: 'https://navadmin.aj0.cn/wp-json/',
          changeOrigin: true,
        },
      },
    },
})
