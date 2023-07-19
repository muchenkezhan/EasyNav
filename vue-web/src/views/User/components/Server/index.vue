<template>
    <div>
        <el-alert title="方便管理宝塔服务器，数据存储本地，不涉及服务器" class="mb-3" type="success" />
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 text-sm leading-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(item, index) in useServerData.serveData" class="" :key="index">
                <div class="bg-gray-50 p-3 rounded-md shadow-md hover:drop-shadow-lg ">
                    <div class="flex justify-between pb-1">
                        <div class="" style="max-width: 190px;">
                            <p class="truncate">{{ item.title }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ item.describe }}</p>
                        </div>
                        <div class="icon-box">
                            <el-icon class="cursor-pointer" @click="openUrl(item.src)">
                                <Position />
                            </el-icon>
                            <el-icon class="cursor-pointer" @click="editChange(item, index)">
                                <Edit />
                            </el-icon>
                            <el-popconfirm title="是否删除当前项?" @confirm="confirmEvent(index)" @cancel="cancelEvent">
                                <template #reference>
                                    <el-icon class="cursor-pointer">
                                        <Delete />
                                    </el-icon>
                                </template>
                            </el-popconfirm>
                        </div>
                    </div>
                    <div class="p-1 flex justify-between">
                        <div class="text-xs leading-6" v-if="!item.show">
                            <div class="flex items-center" >
                                <p class="pr-1" v-if="item.ip">IP:{{ maskIp(item.ip) }}</p>
                                <p class="pr-1" v-else>IP:暂无</p>
                                
                            </div>
                            <div class="flex items-center">
                                <p class="pr-1" v-if="item.account">账:{{ maskString(item.account) }}</p>
                                <p class="pr-1" v-else>账:暂无</p>
                            </div>
                            <div class="flex items-center">
                                <p class="pr-1" v-if="item.pw">密:{{ maskPw(item.pw) }}</p>
                                <p class="pr-1" v-else>密:暂无</p>
                            </div>
                        </div>
                        <div class="text-xs leading-6" v-else>
                            <div class="flex items-center" >
                                <p class="pr-1" v-if="item.ip">IP:{{ item.ip }}</p>
                                <p class="pr-1" v-else>IP:暂无</p>
                                <!-- <el-icon size="14" class="cursor-pointer">
                                    <DocumentCopy />
                                </el-icon> -->
                            </div>
                            <div class="flex items-center">
                                <p class="pr-1" v-if="item.account">账:{{ item.account }}</p>
                                <p class="pr-1" v-else>账:暂无</p>
                            </div>
                            <div class="flex items-center">
                                <p class="pr-1" v-if="item.pw">密:{{ item.pw }}</p>
                                <p class="pr-1" v-else>密:暂无</p>
                               
                            </div>
                        </div>
                        <div class="flex items-end">
                            <el-icon @click="viewData(item)">
                                <View />
                            </el-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <div @click="open_add_serve_mimicry"
                    class="bg-gray-50 p-6 rounded-md shadow-md hover:drop-shadow-lg cursor-pointer">
                    <div class="flex justify-center">
                        <el-icon>
                            <Plus />
                        </el-icon>
                    </div>
                </div>
            </div>
        </div>
        <el-dialog v-model="dialogFormVisible" :show-close="false" width="500">
            <el-form :model="form">
                <el-form-item label="标题:" :label-width="formLabelWidth">
                    <el-input v-model="form.title" autocomplete="off" placeholder="例：香港服务器" />
                </el-form-item>
                <el-form-item label="描述:" :label-width="formLabelWidth">
                    <el-input v-model="form.describe" autocomplete="off" placeholder="例：这个是博客的服务器" />
                </el-form-item>
                <el-form-item label="I P:" :label-width="formLabelWidth">
                    <el-input v-model="form.ip" autocomplete="off" placeholder="例：192.168.1.2" />
                </el-form-item>
                <el-form-item label="链接:" :label-width="formLabelWidth">
                    <el-input v-model="form.src" autocomplete="off" placeholder="请填写完整链接(包括后缀)" />
                </el-form-item>
                <el-form-item label="账号:" :label-width="formLabelWidth">
                    <el-input v-model="form.account" autocomplete="off"  placeholder="可为空" />
                </el-form-item>
                <el-form-item label="密码:" :label-width="formLabelWidth">
                    <el-input v-model="form.pw" autocomplete="off" placeholder="可为空" />
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="dialogFormVisible = false" size="small">取消</el-button>
                    <el-button type="primary" @click="set_serve_item" size="small">
                        {{ isEditorAddTitle }}
                    </el-button>
                </span>
            </template>
        </el-dialog>

    </div>
</template>
<script setup>
import { reactive, ref, watch } from 'vue'
import { useServerStore } from '@/stores/server.js'
const useServerData = useServerStore()
const dialogFormVisible = ref(false)
const formLabelWidth = '50px'
const open = () => dialogFormVisible.value = true
const close = () => dialogFormVisible.value = false
const form = reactive({
    title: '',
    describe: '',
    ip: '',
    account: '',
    pw: '',
    src: '',
    show:false
})
// 存储当前编辑的下标
const changeItemValue = ref('')
// 当前是编辑还是新建
const isEditorAddValue = ref('')
// const serveData = reactive([
//     {
//         title: '美国宝塔服务器',
//         describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
//         ip: '192.168.1.1',
//         account: 'sdasfddgnuyyb',
//         pw: 'tryfehthfdfdf',
//     },
//     {
//         title: '香港宝塔服务器',
//         describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
//         ip: '192.168.1.1',
//         account: 'sdasfddgnuyyb',
//         pw: 'tryfehthfdfdf',
//     },
//     {
//         title: '美国宝塔服务器',
//         describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
//         ip: '192.168.1.1',
//         account: 'sdasfddgnuyyb',
//         pw: 'tryfehthfdfdf',
//     },
//     {
//         title: '香港宝塔服务器',
//         describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
//         ip: '192.168.1.1',
//         account: 'sdasfddgnuyyb',
//         pw: 'tryfehthfdfdf',
//     },
//     {
//         title: '美国宝塔服务器',
//         describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
//         ip: '192.168.1.1',
//         account: 'sdasfddgnuyyb',
//         pw: 'tryfehthfdfdf',
//     },
//     {
//         title: '美国宝塔服务器',
//         describe: '备注服务器描述备注服务器描述备注服务器描述备注服务器描述',
//         ip: '192.168.1.1',
//         account: 'sdasfddgnuyyb',
//         pw: 'tryfehthfdfdf',
//     },
// ])
// 点击保存
// 添加服务器数据
const set_serve_item = () => {
    if (isEditorAddValue.value === 1) {
        useServerData.serveData.push({
            title: form.title,
            describe: form.describe,
            ip: form.ip,
            account: form.account,
            pw: form.pw,
            src: form.src,
        },)
    } else {
        useServerData.editChange(changeItemValue.value, form)
    }
    close()
}
// 编辑服务器数据
const editChange = (item, index) => {
    console.log(index);
    changeItemValue.value = index
    isEditorAddValue.value = 2
    form.title = item.title;
    form.describe = item.describe;
    form.ip = item.ip;
    form.account = item.account;
    form.pw = item.pw;
    form.src = item.src;
    open()
}
// 打开添加服务器对话框
const open_add_serve_mimicry = () => {
    isEditorAddValue.value = 1
    open()
}
// 加密
function maskString(str) {
    if (str.length <= 3) {
        return str; // 字符串长度小于等于 3，不需要替换
    } else {
        return str.substring(0, 3) + '***'; // 使用 substring() 方法获取前三位字符，并拼接上 ***
    }
}
const maskIp = (str) => {
    if (str.length <= 6) {
        return str; // 字符串长度小于等于 6，不需要替换
    } else {
        var front = str.substring(0, 3); // 获取前三个字符
        var end = str.slice(-3); // 获取后三个字符
        return front + '***' + end; // 拼接前三个字符、*** 和后三个字符
    }
}
const maskPw = (str) => {
    if (str.length <= 6) {
        return str; // 字符串长度小于等于 6，不需要替换
    } else {
        return '******'; // 拼接前三个字符、*** 和后三个字符
    }
}
// 查看数据
const isView=ref(false)
const viewData=(item)=>{
    item.show = item.show ? false : true
}
// 打开网址
const openUrl = (url) => {
    if (url) {
        window.open(url);
    } else {
        ElMessage({
            message: '链接未填写.',
            type: 'success',
        })
    }
}
// 计算是添加还是修改
const isEditorAddTitle = ref('')
watch(isEditorAddValue, (newValue, oldValue) => {
    // 在值发生变化时执行相应的逻辑
    if (newValue === 1) {
        isEditorAddTitle.value = '添加'
    } else if (newValue === 2) {
        isEditorAddTitle.value = '修改'
    } else {
        isEditorAddTitle.value = '添加'
    }
});
// 删除当前项
const confirmEvent = (index) => {
    useServerData.deleteChange(index)
}
const cancelEvent = () => {
    console.log('cancel!')
}
// 点击复制
</script>

<style lang="scss" >
.el-dialog__body {
    padding-top: 0;
    padding-bottom: 0;
}

.icon-box {
    min-width: 60px;

    @apply flex justify-end space-x-1;

    .cursor-pointer {
        &:hover {
            @apply text-blue-600;
        }
    }
}
</style>