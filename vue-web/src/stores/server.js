import { ref, computed,reactive } from 'vue'
import { defineStore } from 'pinia'

export const useServerStore = defineStore('server', () => {
    const serveData = ref([
        {
            title: '美国宝塔服务器',
            describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
            ip: '192.168.1.1',
            account: 'sdasfddgnuyyb',
            pw: 'tryfehthfdfdf',
            src: 'tryfehthfdfdf',
    show:false
},
        {
            title: '香港宝塔服务器(示例)',
            describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
            ip: '192.168.1.1',
            account: 'sdasfddgnuyyb',
            pw: 'tryfehthfdfdf',
            src: 'tryfehthfdfdf',
    show:false
},

    ])
    // 删除当前项
    const deleteChange=(index)=>{
        serveData.value.splice(index,1)
    }
    // 修改当前项
    const editChange=(index,form)=>{
        serveData.value[index].title = form.title
        serveData.value[index].src = form.src
        serveData.value[index].pw = form.pw
        serveData.value[index].ip = form.ip
        serveData.value[index].describe = form.describe
        serveData.value[index].account = form.account
    }

  return { serveData,deleteChange,editChange}
},{
    persist: true,
})
