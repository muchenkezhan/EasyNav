<template>
    <el-dialog v-model="dialogTableVisible" title="用户注册">
        <main class="mx-auto p-1">
            <el-form ref="ruleFormRef" :model="ruleForm" status-icon :rules="rules" label-width="70px"
                class="demo-ruleForm">
                <el-form-item label="昵称" prop="name">
                    <el-input v-model="ruleForm.name" type="text" autocomplete="off" />
                </el-form-item>
                <el-form-item prop="email" label="Email">
                    <el-input v-model="ruleForm.email" type="email" autocomplete="off" />
                </el-form-item>
                <el-form-item label="密码" prop="pass">
                    <el-input v-model="ruleForm.pass" type="password" autocomplete="off" />
                </el-form-item>
                <el-form-item label="重复密码" prop="checkPass">
                    <el-input v-model="ruleForm.checkPass" type="password" autocomplete="off" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm(ruleFormRef)">注册</el-button>
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
import { ElMessage } from 'element-plus';
const userDate = userStore();
const dialogTableVisible = ref(false)
const open = () => dialogTableVisible.value = true
const close = () => dialogTableVisible.value = false
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
        if (ruleForm.checkPass !== '') {
            if (!ruleFormRef.value) return
            ruleFormRef.value.validateField('checkPass', () => null)
        }
        callback()
    }
}
const validatePass2 = (rule: any, value: any, callback: any) => {
    if (value === '') {
        callback(new Error('重复密码不能为空'))
    } else if (value !== ruleForm.pass) {
        callback(new Error("两次输入密码不匹配"))
    } else {
        callback()
    }
}
const validateName = (rule: any, value: any, callback: any) => {
    if (value === '') {
        callback(new Error('用户名不能为空'))
    } else if(/[\u4E00-\u9FA5]/g.test(value)) {
        callback(new Error("不能输入中文"));
    }else{
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
    email: '',
    pass: '',
    checkPass: '',
})

const rules = reactive<FormRules<typeof ruleForm>>({
    name: [{ validator: validateName, trigger: 'blur' }],
    email: [{ validator: validateEmail, trigger: ['blur', 'change'], }, {
        type: 'email',
        message: '邮箱不合法',
        trigger: ['blur', 'change'],
    },],
    pass: [{ validator: validatePass, trigger: 'blur' }],

    checkPass: [{ validator: validatePass2, trigger: 'blur' }],

})

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.validate((valid) => {
        if (valid) {
            userDate.register(ruleForm).then(res=>{
                close()
                ElMessage({
                    type: 'success',
                    message: '注册成功'
                })
            })

        } else {
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
<style scoped>
::v-deep .el-form-item__content {
    @apply justify-center;
}
</style>
  