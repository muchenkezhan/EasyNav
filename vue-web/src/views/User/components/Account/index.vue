<template>
  <div>
    <main class="mx-auto flex flex-1 flex-col overflow-hidden">
      <!-- 未登录提示登录 -->
      <div class="mx-auto mt-5 pb-6" v-if="!userData.userdata.token">
        <h3 class="text-lg text-center font-semibold">当前未登录！</h3>
        <h6 class="mt-4 text-slate-400">所有数据都存储于本地，登录可同步数据</h6>
        <div class="pt-6 text-center">
          <el-button @click="openLogin()">登录</el-button>
          <el-button type="primary" @click="openRegister()">注册</el-button>
        </div>
      </div>
      <!-- 登录之后展示用户详情 -->
      <userInfo v-else></userInfo>
    </main>
    <div>
      <!-- 登录和注册 -->
      <login ref="loginRef"></login>
      <register ref="registerRef"></register>
    </div>

  </div>
</template>

<script setup>
import register from './register.vue'
import login from './login.vue'
import { ref } from 'vue';
import { userStore } from '@/stores/user.js'
import userInfo from './userInfo.vue'

const userData = userStore()
const loginRef = ref(null)
const registerRef = ref(null)
const openLogin = () => {
  loginRef.value.open()
}
const openRegister = () => {
  registerRef.value.open()

}
// 修改用户信息

</script>

<style lang="scss" scoped></style>