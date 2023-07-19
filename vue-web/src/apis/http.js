// 封装axios
import axios from 'axios'
import { ElMessage } from 'element-plus';
import { userStore } from '@/stores/user.js'
// 利用axios.create方法创建axios实例，可以设置基础路径、超时的时间设置
const request = axios.create({
    baseURL: '/jwt-auth',  //请求基础路径设置
    timeout: 5000,  //超时的时间设置，超出5秒请求是失败
    withCredentials: true
})

// 请求拦截器
request.interceptors.request.use((config) => {
    config.headers['Content-Type'] = 'application/json';
    // 得到实例
    const useStore = userStore()
    // 2.获取token
    if (useStore.userdata.token) {
        // 根据后端要求拼接token
        config.headers.Authorization = `Bearer  ${useStore.userdata.token}`
    }
    return config;
})

// 响应拦截器
request.interceptors.response.use((response) => {
    // .data 是简化一层数据
    return response.data;
}, (error) => {
    // 得到实例
    const useStore = userStore()
    // 处理http完了错误
    // 请求失败error.response.status的值是错误码
    let status = error.response.status;
    let code = error.response.data.code;
    console.log(error.response);

    //    循环判断错误
    switch (status) {
        case 404:
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: error.message
            })
            break;
        case 501 | 502 | 503 | 504 | 505:
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: '服务器状态错误'
            })
            break;
        case 401:
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: error.response.data.message
            })
            break;
        case 400 | 403:
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: error.response.data.message
            })
            break;
        case 500:
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: error.response.data.message
            })
            break;
        default:
            break;
    }
    switch (code) {
        case "jwt_auth_invalid_token":
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: "身份过期请重新登陆"
            })
            useStore.deleteUserInfo()
            break;
        case 'tokenLoseEfficacy':
            // 提示错误信息
            ElMessage({
                type: 'error',
                message: "身份过期请重新登陆"
            })
            useStore.deleteUserInfo()
            break;
    }
    return Promise.reject(new Error(error.response.data.message))
})


// 对外暴露axios
export default request;