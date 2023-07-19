import './styles/tailwind.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
// pinia持久化存储
const pinia = createPinia()
// element plus
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import App from './App.vue'
import router from './router'

const app = createApp(App)
// 注册持久化插件
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
pinia.use(piniaPluginPersistedstate)

// element plus 图标
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
  }

app.use(createPinia())
app.use(router)
app.use(pinia)
app.use(ElementPlus)
app.mount('#app')
