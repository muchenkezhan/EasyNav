import { ref, computed, reactive } from 'vue'
import { defineStore } from 'pinia'
import { ElMessage } from 'element-plus'
import { reqRegister, reqLogin } from "@/apis/user";
export const userStore = defineStore('user', () => {
    const userdata = ref({})
    // 注册
    const register =async (ruleForm) => {
        let res = await reqRegister({
            username: ruleForm.name,
            password: ruleForm.pass,
            email: ruleForm.email,
        })
        if(res.code == 200){
            ElMessage({
                type: 'message',
                message: "注册成功请登录"
            })
        }
    }
    // 登录
    const login = async (ruleForm) => {
        let res = await reqLogin({
            password: ruleForm.pass,
            username: ruleForm.name,
        })
        if (res.token) {
            userdata.value = res
        }

    }
    // 删除token
    const deleteUserInfo = ()=>{
        userdata.value = ''
        
    }
    // 判断用户登录了吗
    return { register, login, userdata,deleteUserInfo }
}, {
    persist: true,
})

