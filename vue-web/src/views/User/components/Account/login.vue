<template>
    <el-dialog v-model="dialogTableVisible" title="用户登录">
        <main class="mx-auto p-1">
        <!-- {{ userDate.userdata }} -->
        <el-form ref="ruleFormRef" :model="ruleForm" status-icon :rules="rules" label-width="40px" class="demo-ruleForm">
            <el-form-item prop="name" label="name">
                <el-input v-model="ruleForm.name" type="text" autocomplete="off" />
            </el-form-item>
            <el-form-item label="密码" prop="pass">
                <el-input v-model="ruleForm.pass" type="password" autocomplete="off" />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="submitForm(ruleFormRef)">登录</el-button>
                <el-button @click="resetForm(ruleFormRef)">重置</el-button>
            </el-form-item>
        </el-form>
    </main>
    </el-dialog>

</template>
  
<script lang="ts" setup>
import { reactive, ref } from 'vue'
import type { FormInstance, FormRules } from 'element-plus'
import { userStore } from '@/stores/user.js'
import { useUrlDiyStore } from '@/stores/urlDiyDrawer.js'
const useUrlDiyData = useUrlDiyStore()
import { ElMessage } from 'element-plus';
const dialogTableVisible = ref(false)
const open =()=>dialogTableVisible.value = true
const close =()=>dialogTableVisible.value = false
const userDate = userStore();
const ruleFormRef = ref<FormInstance>()

const checkAge = (rule: any, value: any, callback: any) => {
    if (!value) {
        return callback(new Error('Please input the age'))
    }
    setTimeout(() => {
        if (!Number.isInteger(value)) {
            callback(new Error('Please input digits'))
        } else {
            if (value < 18) {
                callback(new Error('Age must be greater than 18'))
            } else {
                callback()
            }
        }
    }, 1000)
}

const validatePass = (rule: any, value: any, callback: any) => {
    if (value === '') {
        callback(new Error('密码不能为空'))
    } else {
        callback()
    }
}

const validateEmail = (rule: any, value: any, callback: any) => {
    if (value === '') {
        callback(new Error('邮箱不能为空'))
    } else {
        callback()
    }
}
const ruleForm = reactive({
    name: '',
    pass: '',
})

const rules = reactive<FormRules<typeof ruleForm>>({
    name: [{ validator: validateEmail, trigger: ['blur', 'change'], }],
    pass: [{ validator: validatePass, trigger: 'blur' }],

})

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.validate((valid) => {
        if (valid) {
            console.log('符合标准!')
            userDate.login(ruleForm).then(res => {
                console.log(res);
                ElMessage({
                    type: 'success',
                    message: '登录成功'
                })
                close()
                // 获取最新侧边栏网址
                useUrlDiyData.getHomeSidebarWebsites()
            })

        } else {
            console.log('不符合标准!')
            return false
        }
    })
}

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
}
defineExpose({
    open,
    close
})
</script>
  