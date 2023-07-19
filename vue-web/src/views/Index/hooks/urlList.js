// 首页网址列表的代码
import { ref, reactive, onMounted } from 'vue'
import { useInexUrlListStore } from '@/stores/inexUrlList.js'
import { reqHomeWebsitL, setHomeUrl, editHomeUrl, deleteHomeUrl } from "@/apis/homeSite";
import { ElMessage } from 'element-plus'
import { userStore } from '@/stores/user.js'
export function urlListHook(opt = {}) {
    const useInexUrlListStoreData = useInexUrlListStore()
    const userData = userStore()
    // 当前点击哪个的下标
    const current = ref('')
    // 当前点击哪个网址的对象
    const indexItemUrl = ref('')
    // 打开新建
    const openAddUrl = () => {
        opt.open()
        current.value = ''
        form.title = ''
        form.url = ''
    }
    const form = reactive({
        title: '',
        url: '',
    })
    // 编辑
    const edit_url = (index, item) => {
        current.value = index
        indexItemUrl.value = item
        form.title = useInexUrlListStoreData.url_List[index].title
        form.url = useInexUrlListStoreData.url_List[index].url
        opt.open()
    }
    // 添加网址
    function trim(str) { //删除左右两端的空格
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }

    const add_url = async () => {
        if (!trim(form.title) || !trim(form.url)) {
            return ElMessage({
                type: 'success',
                message: '不能为空'
            })
        }
        // 保存当前修改的数据
        let res = await setHomeUrl(form.title, form.url)
        if (res.code == 200) {
            ElMessage({
                type: 'success',
                message: '新增网址成功'
            })
            useInexUrlListStoreData.getHomeUrlData()
        }
        opt.close()
    }
    // 请求最新首页网址数据
    useInexUrlListStoreData.getHomeUrlData()
    // 删除当前点击网址
    const deleteUrl = async (item) => {
        let res = await deleteHomeUrl(item.id)
        if (res.code == 200) {
            ElMessage({
                type: 'success',
                message: '删除成功'
            })
            useInexUrlListStoreData.getHomeUrlData()
        }
    }
    // 修改点击的网址
    const post_url = async () => {
        let res = await editHomeUrl(form.title, form.url, indexItemUrl.value.id)
        if (res.code == 200) {
            ElMessage({
                type: 'success',
                message: '修改成功'
            })
            useInexUrlListStoreData.getHomeUrlData()
        }
        opt.close()
    }
    //组件中用到的数据要返回出去
    return {
        current,
        openAddUrl,
        form,
        edit_url,
        add_url,
        deleteUrl,
        post_url
    }
}