<template>
    <!-- Header -->
    <header class="inset-x-0 top-0 z-50">
        <nav class="backdrop-blur mx-auto flex items-center justify-between p-4 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a v-show="route.name != 'index'" @click="urlDiyDrawer" href="javascript:;" class="-m-1.5 p-1.5 text-gray-600">
                    <el-icon class="md:hidden " @click="openWapMenu" size="20">
                        <Operation />
                    </el-icon>
                    <span  @click="$router.push('/')" class="ml-2" title="返回首页"><el-icon size="20">
                            <Back />
                        </el-icon></span>
                        <span></span>
                </a>


            </div>
            <div class="flex lg:hidden">
                <button @click="urlListDrawer" type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5"  :class="{'text-gray-50':route.name == 'index'}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <div class="hidden lg:flex lg:flex-1 lg:justify-end" :class="{'text-gray-50':route.name == 'index'}">
                <a @click="openUserSet" href="javascript:;" class="text-sm font-semibold leading-6 pr-3" :class="route.name == 'index' && bgImgUrl ? 'text-white' : 'text-gray-800'">
                    <el-icon size="20">
                        <UserFilled />
                    </el-icon>
                </a>
                <a @click="urlListDrawer" href="javascript:;" class="text-sm font-semibold leading-6" :class="route.name == 'index' && bgImgUrl ? 'text-white' : 'text-gray-800'">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </a>
            </div>
        </nav>
        <UrlListDrawer ref="UrlListDrawerRef"></UrlListDrawer>
        <Wapmenu ref="WapmenuRef"></Wapmenu>
    </header>
</template>

<script setup>
// 用户中心手机端的侧边栏
import Wapmenu from '@/views/User/components/Wapmenu/index.vue'
import UrlListDrawer from '@/views/UrlListDrawer/index.vue'
// 仓库图片
import { personalization } from '@/stores/personalization.js'
const personalizationPina = personalization()
// 图片在线链接
const bgImgUrl = ref(personalizationPina.bg_img)
import { ref } from 'vue'
import { useRouter,useRoute } from "vue-router";
const UrlListDrawerRef = ref(null)
//获取router实例
const router = useRouter()
const route = useRoute()
// 开启网址抽屉
const urlListDrawer = () => UrlListDrawerRef.value.open()
// 个人中心代码
const openUserSet = () => {
    router.replace({ path: '/user' })
}

// 用户中心，手机端侧边栏
const WapmenuRef = ref(null)
const openWapMenu = () => {
    WapmenuRef.value.open()
}
</script>

<style lang="scss" scoped></style>