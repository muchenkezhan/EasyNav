<template>
    <div>
        <el-drawer v-model="drawerswitch" direction="ltr" title="I66666le" :with-header="false" size="250px">
            <div class="">
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">

                                <li v-for="(item, index) in menuList">
                                    <a :class="{'active':activeIndex==index}" @click="to_path(item.route,index)" href="javascript:;"
                                        class="hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700">
                                        {{ item.title }}
                                    </a>
                                </li>

                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </el-drawer>
    </div>
</template>

<script setup>
import { ref } from 'vue'
const drawerswitch = ref(false)
const open = () => drawerswitch.value = true
const close = () => drawerswitch.value = true
import { useRouter,useRoute } from "vue-router";
const router = useRouter()

const menuList = ref([
    {
        title: '账户设置',
        route: '/user'
    },
    {
        title: '个性设置',
        route: '/user/personalise'
    },
    {
        title: '偏好设置',
        route: '/user/preferences'
    },
    {
        title: '服务器列表',
        route: '/user/server'
    },
])

const activeIndex=ref(0)

const to_path = (to,index) => {
    activeIndex.value = index
    router.push({
        path: to,
    })
    drawerswitch.value = false
}

defineExpose({
    open,
    close
})
</script>

<style lang="scss" scoped>
.active{
    @apply bg-gray-200;
}
</style>