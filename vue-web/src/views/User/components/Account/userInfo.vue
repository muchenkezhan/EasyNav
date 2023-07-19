<template>
    <main class="">
        <div class="mx-auto mt-5">
            <h3 class="text-lg text-center font-semibold">帐户设置</h3>
            <h6 class="mt-4 text-slate-400 text-center">管理与您的帐户关联的电子邮件地址和密码</h6>
        </div>
        <div class=" mt-12">
            <form action="/login" class="mx-auto max-w-md w-full">
                <div class="mb-6"><label for="email"
                        class="block text-sm font-semibold leading-6 text-gray-900">用户名</label><input title="不能修改用户名"
                        disabled type="text" id="name"
                        class="mt-2 appearance-none text-slate-900 bg-gray-100 rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        :value="userData.userdata.user_display_name" required=""></div>

                <div class="mb-6"><label for="femail"
                        class="block text-sm font-semibold leading-6 text-gray-900">昵称</label><input disabled type="text"
                        id="femail"
                        class="bg-gray-100 mt-2 appearance-none text-slate-900 rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        :value="userData.userdata.user_nicename" required=""></div>

                <div class="mb-6"><label for="new_nickname"
                        class="block text-sm font-semibold leading-6 text-gray-900">新昵称</label><input type="email"
                        id="new_nickname" autocomplete="new_nickname"
                        class="mt-2 appearance-none text-slate-900 bg-white rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        v-model="form.nickname" required=""></div>
                <div class="mt-10 flex justify-end gap-x-6 content-end"><a
                        class="rounded-md py-2 px-4 text-sm font-semibold focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 bg-slate-900 text-white hover:bg-slate-700 hover:text-slate-100 active:bg-slate-800 active:text-slate-300 focus-visible:outline-slate-900"
                        href="javascript:;" @click="updateNickname">更新昵称</a></div>
                <div class="mb-6"><label for="semail"
                        class="block text-sm font-semibold leading-6 text-gray-900">邮箱</label><input type="email" id="semail"
                        disabled
                        class="bg-gray-100 mt-2 appearance-none text-slate-900 rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        :value="userData.userdata.user_email" required=""></div>
                <div class="mb-6"><label for="useremail"
                        class="block text-sm font-semibold leading-6 text-gray-900">新邮箱</label><input type="email"
                        id="useremail" autocomplete="useremail"
                        class="mt-2 appearance-none text-slate-900 bg-white rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        v-model="form.email" required=""></div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <!-- <label type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button> -->
                 
                    <a class="rounded-md py-2 px-4 text-sm font-semibold focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 bg-slate-900 text-white hover:bg-slate-700 hover:text-slate-100 active:bg-slate-800 active:text-slate-300 focus-visible:outline-slate-900"
                        href="javascript:;" @click="updateUserEmail">修改邮箱</a>
                </div>
                <div class="mb-6"><label for="old_password"
                        class="block text-sm font-semibold leading-6 text-gray-900">旧密码</label><input type="password"
                        id="old_password" autocomplete="old_password"
                        class="mt-2 appearance-none text-slate-900 bg-white rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        v-model="form.old_password" required=""></div>
                <div class="mb-6"><label for="new_password"
                        class="block text-sm font-semibold leading-6 text-gray-900">新密码</label><input type="password"
                        id="new_password" autocomplete="new-password"
                        class="mt-2 appearance-none text-slate-900 bg-white rounded-md block w-full px-3 h-10 shadow-sm sm:text-sm focus:outline-none placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 ring-1 ring-slate-200"
                        v-model="form.new_password" required=""></div>
                <div class="mt-6 flex justify-end gap-x-6 content-end"><a
                        class="rounded-md py-2 px-4 text-sm font-semibold focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 bg-slate-900 text-white hover:bg-slate-700 hover:text-slate-100 active:bg-slate-800 active:text-slate-300 focus-visible:outline-slate-900"
                        href="javascript:;" @click="setPossword">更新密码</a></div>
                <div class="mt-6 flex justify-end gap-x-6 content-end"><a
                        class="rounded-md py-2 px-4 text-sm font-semibold focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 bg-slate-900 text-white hover:bg-slate-700 hover:text-slate-100 active:bg-slate-800 active:text-slate-300 focus-visible:outline-slate-900"
                        href="javascript:;" @click="logout">退出登录</a></div>
            </form>
            <!-- <div class="bg-gray-200 p-4 w-48 rounded-md group">
                    <p>Hover over me</p>
                    <div
                        class="px-2 py-1 bg-white border border-gray-300 shadow-lg rounded-md absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <p>Here's some extra content!</p>
                    </div>
                </div> -->
        </div>

    </main>
</template>

<script setup>
  defineOptions({ 
    name: 'MyComponent',
    inheritAttrs: false
  })
import { reqSetPassword, reqSetUaserNickname,reqSetUsaerName } from '@/apis/user.js'
import { userStore } from '@/stores/user.js'
import { useUrlDiyStore } from '@/stores/urlDiyDrawer.js'
import { ref } from 'vue';
import { ElMessage } from 'element-plus'
const userData = userStore()
const useUrlDiyData = useUrlDiyStore()
const userinfo = userData.userdata
const form = ref({
    old_password: '',
    new_password: '',
    nickname: '',
    email: ''
})

// 修改密码
const setPossword = async () => {
    if (form.value.old_password == '' || form.value.new_password == '') {
        return ElMessage({
            type: 'error',
            message: "密码不能为空"
        })
    }
    // let res =await reqSetPassword(form.value.old_password,form.value.new_password);
    let res = await reqSetPassword(form.value);
    if (res.code == 200) {
        ElMessage({
            type: 'success',
            message: "修改成功请重新登陆"
        })
        userData.deleteUserInfo()
    }
}
// 退出登录
const logout = () => {
    ElMessage({
        type: 'success',
        message: "退出成功"
    })
    userData.deleteUserInfo()
    useUrlDiyData.getHomeSidebarWebsites()
}
// 更新昵称
const updateNickname = async () => {
    if (form.value.nickname == '') {
        return ElMessage({
            type: 'error',
            message: "新昵称不能为空"
        })
    }
    let res = await reqSetUaserNickname(form.value.nickname);
    if (res.code == 200) {
        ElMessage({
            type: 'success',
            message: "修改成功重新登陆"
        })
        userData.deleteUserInfo()
        useUrlDiyData.getHomeSidebarWebsites()
    }
}
// 更新邮箱
const updateUserEmail = async () => {
    if (form.value.email == '') {
        return ElMessage({
            type: 'error',
            message: "新邮箱不能为空"
        })
    }
    let res = await reqSetUsaerName({email:form.value.email});
    if (res.code == 200) {
        ElMessage({
            type: 'success',
            message: "修改邮箱成功"
        })
        userData.deleteUserInfo()
        useUrlDiyData.getHomeSidebarWebsites()
    }
}
</script>

<style lang="scss" scoped></style>