<template>
    <!-- 二级路由出口 -->
    <div class="max-w-6xl mx-auto">
        <el-container>
            <el-aside width="200px" class="max-md:hidden">
                <Aside></Aside>
            </el-aside>
            <!-- <el-aside width="200px" class="md:hidden">
                手机端
            </el-aside> -->
            <el-main class="bg-white">
                <router-view v-slot="{ Component }">
                    <transition name="fade">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </el-main>
        </el-container>
    </div>
</template>
<script setup>
import Aside from './components/Menu.vue'
</script>

<style lang="scss" >
// body{
//     @apply bg-slate-200;
// }
.el-aside {
    transition: all 0.2s;
}

// 进入前
.fade-enter-from {
    opacity: 0;
}

// 进入后
.fade-enter-to {
    opacity: 1;
}

// 离开前
.fade-leave-form {
    opacity: 1;
}

// 离开后
.fade-leave-form {
    opacity: 0;
}

// 动画
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s;
}

// 延迟0.3s离开不然页面混乱
.fade-enter-active {
    transition-delay: 0.3s;
}
</style>