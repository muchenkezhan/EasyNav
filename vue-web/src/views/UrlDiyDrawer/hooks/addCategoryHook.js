// 搜索功能的代码
import { ref, reactive } from 'vue'
import { useUrlDiyStore } from '@/stores/urlDiyDrawer.js'
import { ElMessage } from 'element-plus'
import {userStore} from '@/stores/user.js'
import { addSidebarWebsitesCategoryUrl,editSidebarWebsitesCategory,reqHomeSidebarWebsites, addSidebarWebsitesCategory, deleteSidebarWebsitesCategory, deleteSidebarWebsitesCategoryUrl, updateCategoryWebsiteData } from "@/apis/homeSite";
export function addCategoryHook(opt = {}) {
    // 实例化获取仓库的方法等
    const useUrlDiyData = useUrlDiyStore()
    const userData = userStore()
    // 拟态框状态
    const categoryFormVisible = ref(false)
    const categoryTableVisible = ref(false)
    const open = () => categoryFormVisible.value = true
    const close = () => categoryFormVisible.value = false
    const add_open = () => categoryTableVisible.value = true
    const add_close = () => categoryTableVisible.value = false
    // 当前父级索引
    const parentIndex = ref('')
    // 当前点击的分类Id
    const categoryId = ref('')
    // 存储当前双击之后的网址对象】
    const urlChangeData =ref({})
    // 存储当前修改的分类对象
    const catrgoryChangeData = ref({})

    // 存储是添加还是修改状态 1添加 2修改
    const is_editOraddUrlData = ref('')
        // 计算是修改分类还是添加分类  1添加 2修改
    const is_CatEditOraddUrlData = ref('')
    const parentIndexFn = (index, cat_element) => {
        parentIndex.value = index
        categoryId.value = cat_element
    }

    // 收集当前添加tag的数据
    const form = reactive({
        title: '',
        url: '',
    })
    // 收集新建分类名称
    const categoryForm = reactive({
        title: ''
    })
    // 点击打开添加分类的拟态框
    const open_add_category = () => {
        is_CatEditOraddUrlData.value = 1
        open()
    }
    // 获取侧边栏分类网址数据
    const getHomeSidebarWebsites=async()=>{
       let res=await  reqHomeSidebarWebsites()
        if (res.code == 200) {
            useUrlDiyData.setWebsiteList(res.data)
        }
    }
    // 分类拟态框的确认按钮
    const addCategoryItem = () => {
        if (!categoryForm.title) { return };
        if(is_CatEditOraddUrlData.value == 1){
            addSidebarWebsitesCategory(categoryForm.title).then(res => {
                if (res.code == 200) {
                    ElMessage({
                        type: 'success',
                        message: '添加分类成功'
                    })
                    getHomeSidebarWebsites()
                }
            })
        }else{
            editSidebarWebsitesCategory(categoryForm.title,catrgoryChangeData.value.id).then(res => {
                if (res.code == 200) {
                    ElMessage({
                        type: 'success',
                        message: '修改分类标题成功'
                    })
                    getHomeSidebarWebsites()
                }
            })
        }
        close()
    }
    // 打开修改网址的窗口
    const openSubURLModal = () => {
        is_editOraddUrlData.value = 1
        add_open()
    }
    // 添加tag的按钮已经打开了窗口
    const addTag = async() => {
        // 根据标识判断是添加还是修改
        if (is_editOraddUrlData.value === 1) {
            let res = await addSidebarWebsitesCategoryUrl(categoryId.value,form)
            if (res.code == 200) {
                ElMessage({
                    type: 'success',
                    message: '添加网址成功'
                })
                getHomeSidebarWebsites()
            }
        } else {
            console.log(urlChangeData.value);
            if (urlChangeData.value.id) {
                updateCategoryWebsiteData(form.title, form.url, categoryId.value,urlChangeData.value.id).then(res => {
                    if (res.code == 200) {
                        ElMessage({
                            type: 'success',
                            message: '修改成功'
                        })
                        useUrlDiyData.getHomeSidebarWebsites()
                    }
                })
            }else{
               if(userData.userdata.token){
                ElMessage({
                    type: 'warning',
                    message: '此时添加的数据如修改，需先刷新之后'
                })
               }else{
                ElMessage({
                    type: 'warning',
                    message: '请先登录！'
                })
               }
            }
            // 修改子tag的属性
        }
        form.title = ''
        form.url = ''
        add_close()
    }
    // 点击关闭当前tag
    const onClose = async (childIndex, element) => {
        // 找到当前标签所在的分类
        if (element.id) {
            let res = await deleteSidebarWebsitesCategoryUrl(categoryId.value, element.id)
            if (res.code == 200) {
                useUrlDiyData.categoryList[parentIndex.value].child.splice(childIndex, 1);
                ElMessage({
                    type: 'success',
                    message: '删除网址成功'
                })
            }
        } else {
            if(userData.userdata.token){
                ElMessage({
                    type: 'warning',
                    message: '当前新建的网址，刷新之后可删除'
                })
               }else{
                ElMessage({
                    type: 'warning',
                    message: '请先登录！'
                })
               }

            // useUrlDiyData.categoryList[parentIndex.value].child.splice(childIndex, 1);
        }
    }
    // 点击删除当前分类
    const deleteCurrentCategory = async () => {
        console.log(parentIndex.value);
        if (!useUrlDiyData.categoryList[parentIndex.value].child.length == 0) {
            return ElMessage({
                type: 'error',
                message: '请确保当前分类下没有网址'
            })
        }
        let res = await deleteSidebarWebsitesCategory(categoryId.value)
        if (res.code == 200) {
            ElMessage({
                type: 'success',
                message: '删除该分类成功'
            })
            useUrlDiyData.deleteCategory(parentIndex.value)
        }
    }
    // 双击修改tag数据
    const handleDoubleClick = (element) => {
        urlChangeData.value = element
        console.log(element);
        is_editOraddUrlData.value = 2
        // 处理双击事件的逻辑
        form.title = element.title
        form.url = element.url
        add_open()
    }
    // 修改分类名称
    const editCurrentCategory = (element) => {
        catrgoryChangeData.value = element
        is_CatEditOraddUrlData.value = 2
        categoryForm.title = element.title
        open()
    }
    //组件中用到的数据要返回出去
    return {
        open_add_category,
        categoryFormVisible,
        addCategoryItem,
        categoryForm,
        categoryTableVisible,
        addTag,
        form,
        add_open,
        parentIndexFn,
        parentIndex,
        categoryId,
        onClose,
        deleteCurrentCategory,
        handleDoubleClick,
        editCurrentCategory,
        is_editOraddUrlData,
        openSubURLModal,
        is_CatEditOraddUrlData
    }
}