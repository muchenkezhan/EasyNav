<template>
    <div>
        <el-drawer v-model="drawer" title="I am the title" :with-header="false" :size="drawerSize">
            <div class="flex justify-end">
                <div @click="open_set_Url_List_drawer"
                    class="p-1 hover:bg-gray-300 flex justify-center items-center rounded-lg hover:text-gray-700 cursor-pointer">
                    <el-icon>
                        <Setting />
                    </el-icon>
                </div>
            </div>
            <div class="pb-9" v-for="(item, index) in useUrlDiyData.categoryList">

                <h2 class="text-1xl font-bold tracking-tight text-slate-900 pb-3">{{ item.title }}</h2>
                <div
                    class="grid grid-cols-2 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 justify-items-center gap-x-px">
                    <span v-for="(item2, index) in item.child" @click="openUrl(item2.url)"
                        class="itemUrl inline-flex items-center rounded-md bg-gray-100 px-2 py-2 text-xs font-medium text-gray-600 cursor-pointer hover:bg-stone-300">
                        <!-- <el-icon>
                            <Odometer />
                        </el-icon> -->
                        <!-- <img :src="`https://navadmin.aj0.cn/wp-json/jwt-auth/v1/get?url=${item2.url}`" width="20"> -->
                        <img :src="`/jwt-auth/v1/get?url=${item2.url}`" width="18">
                        <span class="pl-1 truncate">{{ item2.title }}</span></span>
                </div>
            </div>
            <div v-if="isURl">
                您当前未添加网址，请点击 设置 添加！
            </div>
        </el-drawer>
        <!-- diy -->
        <UrlDiyDrawer ref="UrlDiyDrawerRef"></UrlDiyDrawer>
    </div>
</template>

<script setup>
import UrlDiyDrawer from '@/views/UrlDiyDrawer/index.vue'
import { useWindowSize } from '@vueuse/core'
import { ref, computed, onMounted, watch } from 'vue';
const drawer = ref(false)
const open = () => drawer.value = true
const close = () => drawer.value = false
const UrlDiyDrawerRef = ref(null)
// 开启编辑网址抽屉
const openUrlDiyDrawer = () => UrlDiyDrawerRef.value.open()
// pinia
import { useUrlDiyStore } from '@/stores/urlDiyDrawer.js'
import { userStore } from '@/stores/user.js'
const useUrlDiyData = useUrlDiyStore()
const userData = userStore()
const isURl = computed(() => {
      return useUrlDiyData.categoryList.length == 0 && userData.userdata.token
    })

// 列表数据
const urlData = ref(useUrlDiyData.categoryList)

// 打开修改侧边栏diy网址抽屉
const open_set_Url_List_drawer = () => {
    openUrlDiyDrawer()
}
// 点击打开网址
const openUrl = (url) => {
    window.open(url);
}
// 挂载完毕
onMounted(() => {
    // 请求api得到网址分类
    useUrlDiyData.getHomeSidebarWebsites()
})
// 计算侧边栏宽度
const { width, height } = useWindowSize()
let drawerSize = ref('')
watch(width, (newValue, oldValue) => {
    if (newValue >= 1280) {
        drawerSize.value = '500px'
    } else if (newValue >= 1200) {
        drawerSize.value = '430px'
    } else if (newValue >= 992) {
        drawerSize.value = '400px'
    } else if (newValue >= 768) {
        drawerSize.value = '400px'
    }  else if (newValue <= 500) {
        drawerSize.value = '80%'
    }else {
        drawerSize.value = '270px'
    }

}, { immediate: true })


// 侧边栏网址数据
import { sidebarWebsites } from './hooks/sidebarWebsites.js'
const {
}=sidebarWebsites()
defineExpose({
    open,
    close
})

</script>

<style lang="scss" scoped>
.el-drawer__container {
    position: relative;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    height: 100%;
    width: 100%;
}
.itemUrl{
width: 100px;
}
</style>