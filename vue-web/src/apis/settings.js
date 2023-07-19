import ajax from "@/apis/http.js";

// 获取个性设置
export function reqGetSettingPersonalise(code) {
    return ajax({
        url: 'v1/setting-personalise',
        method:'post',
        data:{
            code:code
        }
    })
}

// 存储个性设置配置
export function reqSetSettingPersonalise(code,externalImage,serverImage) {
    return ajax({
        url: 'v1/set-setting-personalise',
        method:'post',
        data:{
            code,
            externalImage,
            serverImage
        }
    })
}

