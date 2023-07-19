import ajax from "@/apis/http.js";


// 用户注册
export function reqRegister(data) {
    return ajax({
        url: '/v1/register',
        method: 'post',
        data
    })
}

// 用户登录
export function reqLogin(data) {
    return ajax({
        url: '/v1/token',
        method: 'post',
        data
    })
}

// 修改昵称
export function reqSetUaserNickname(name) {
    return ajax({
        url: '/v1/update-nickname',
        method:'post',
        data:{
            nickname:name,
        }
    })
}

// 修改密码
export function reqSetPassword(data) {
    return ajax({
        url: '/v1/change-password',
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
          },
        data
        
    })
}

// 修改邮箱
export function reqSetUsaerName(data) {
    console.log(data);
    return ajax({
        url: '/v1/update_email',
        method: 'post',
        data
    })
}