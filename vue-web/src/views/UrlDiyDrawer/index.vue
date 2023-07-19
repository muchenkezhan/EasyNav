<template>
    <el-drawer class="relative" v-model="drawer" title="I am the title" :with-header="false" direction="ltr"
        :size="drawerSize">
        <div class="">
            <el-collapse v-model="activeName" accordion>

                <!-- 分类循环 -->
                <draggable :list="useUrlDiyData.categoryList" ghost-class="ghost" chosen-class="chosenClass" animation="300"
                    @start="onStart" @end="onEnd" itemKey>
                    <template #item="{ element, index }">

                        <el-collapse-item :title="element.title" :name="index" :key="index"
                            @click="parentIndexFn(index, element.id)">
                            <div class="content bg-gray-50 p-3">
                                <!-- tag循环 -->
                                <el-alert v-show="element.child == 0" title="请添加网址" type="info" />
                                <draggable itemKey :list="element.child" ghost-class="ghost" chosen-class="chosenClass"
                                    animation="300" @start="onStart2" @end="onEnd2">
                                    <template #item="{ element, index }">
                                        <el-tag title="双击修改数据" :key="index" class="mx-1" closable
                                            @close="onClose(index, element)" @dblclick="handleDoubleClick(element)">
                                            {{ element.title }}
                                        </el-tag>

                                    </template>
                                </draggable>
                                <el-divider class="mt-3 mb-2"></el-divider>
                                <div style="transform: scale(0.8);" class="pt-3 md:flex justify-end md:flex-wrap">
                                    <el-button class="button-new-tag ml-1" size="small" @click="openSubURLModal" type="success">
                                        + 添加网址
                                    </el-button>
                                    <el-button class="button-new-tag ml-1" size="small" @click="deleteCurrentCategory"
                                        type="danger">
                                        <el-icon>
                                            <Delete />
                                        </el-icon> 删除分类
                                    </el-button>
                                    <el-button class="button-new-tag ml-1" size="small"
                                        @click.stop="editCurrentCategory(element)" type="info">
                                        <el-icon>
                                            <Delete />
                                        </el-icon> 修改分类
                                    </el-button>
                                </div>

                            </div>
                        </el-collapse-item>
                    </template>
                </draggable>

                <div @click="open_add_category"
                    class="p-3 bg-gray-200 mt-3 rounded hover:bg-gray-300 cursor-pointer flex items-center justify-center">
                    <el-icon>
                        <Plus />
                    </el-icon>
                </div>
                
            </el-collapse>


            <p class="mt-5 text-center text-xs leading-6 text-slate-500" v-if="!userData.userdata.token">登录即可同步数据.</p>
        </div>
        <div class="absolute bottom-2">
            <p class="mt-5 text-center text-xs leading-6 text-slate-500">{{ basicInformationData.basicInformationDate.copyright }}</p>
        </div>
        <!-- 添加tag弹窗 -->
        <el-dialog v-model="categoryTableVisible" :show-close="false">
            <el-form :model="form">
                <el-form-item label="网站名" :label-width="formLabelWidth">
                    <el-input v-model="form.title" autocomplete="off" />
                </el-form-item>
                <el-form-item label="地址" :label-width="formLabelWidth">
                    <el-input v-model="form.url" autocomplete="off" />
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="categoryTableVisible = false">取消</el-button>
                    <el-button type="primary" @click="addTag">
                        {{ tagTitle }}
                    </el-button>
                </span>
            </template>
        </el-dialog>
        <!-- 添加分类弹窗 -->
        <el-dialog v-model="categoryFormVisible" title="添加分类">
            <el-form :model="categoryForm">
                <el-form-item label="分类名:" label-width="80px">
                    <el-input v-model="categoryForm.title" autocomplete="off" />
                </el-form-item>

            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="categoryFormVisible = false">取消</el-button>
                    <el-button type="primary" @click.stop="addCategoryItem">
                        {{ isAddOrEditTitle }}
                    </el-button>
                </span>
            </template>
        </el-dialog>
    </el-drawer>
</template>

<script setup>
import { useWindowSize } from '@vueuse/core'
import { ref, onMounted, toRef, nextTick, reactive, watch } from 'vue';
import draggable from "vuedraggable";
import {userStore} from '@/stores/user.js'
// pinia
import { useUrlDiyStore } from '@/stores/urlDiyDrawer.js'
import { ElInput } from 'element-plus'
// 新建分类的hook
import { addCategoryHook } from './hooks/addCategoryHook.js'
import { updateCategorySort,updateCategoryWebsiteSort } from '@/apis/homeSite.js'
import {basicInformationStore} from '@/stores/basicInformation.js'
const basicInformationData = basicInformationStore()
const userData = userStore()
const drawer = ref(false)
const open = () => drawer.value = true
const close = () => drawer.value = false
const { open_add_category,
    categoryFormVisible,
    addCategoryItem,
    categoryForm,
    addTag,
    form,
    parentIndexFn,
    parentIndex,
    onClose,
    categoryId,
    categoryTableVisible,
    add_close,
    add_open,
    openSubURLModal,
    deleteCurrentCategory,
    handleDoubleClick,
    editCurrentCategory,
    is_editOraddUrlData,
    is_CatEditOraddUrlData
} = addCategoryHook();

const useUrlDiyData = useUrlDiyStore()
// 抽屉
// 默认展示
const activeName = ref('0')
// 添加框状态
const formLabelWidth = '60px'
/* 阻止浏览器在 vue-draggable组件时拖动 打开新窗口 */
document.body.ondrop = function (event) {
    event.preventDefault();
    event.stopPropagation();
};

// 拖拽功能
//拖拽开始的事件
const onStart = () => {
    console.log("开始拖拽");
};
const onStart2 = () => {
    console.log("开始拖拽");
};
//拖拽结束的事件
const onEnd = () => {
    // 更新排序标识
    useUrlDiyData.categoryList.forEach((item, index) => {
        item.sort_order = index + 1;
    });

    const newData = useUrlDiyData.categoryList.map((item) => {
        return {
            id: item.id,
            sort_order: item.sort_order
        };
    });

    setTimeout(() => {
        updateCategorySort(JSON.stringify(newData)).then(res => {
            console.log(res);
            useUrlDiyData.getHomeSidebarWebsites().then(res => {
                console.log(res);
            })

        })
    }, 200)
    console.log("结束拖拽");
};
const onEnd2 = () => {
    console.log('当前父级分类：',parentIndex.value);
    // 更新排序标识
    useUrlDiyData.categoryList[parentIndex.value].child.forEach((item, index) => {
        item.sort_order = index + 1;
    });
    // 整理排序成对象
    const newData = useUrlDiyData.categoryList[parentIndex.value].child.map((item) => {
        return {
            id: item.id,
            sort_order: item.sort_order
        };
    });
    // 请求接口更新数据
    setTimeout(() => {
        updateCategoryWebsiteSort(JSON.stringify(newData),categoryId.value).then(res => {
            console.log(res);
            useUrlDiyData.getHomeSidebarWebsites().then(res => {
                console.log(res);
            })

        })
    }, 200)


};
// 计算是添加还是修改
const tagTitle = ref('')
watch(is_editOraddUrlData, (newValue, oldValue) => {
  // 在值发生变化时执行相应的逻辑
  if (newValue === 1) {
    tagTitle.value = '添加'
  } else if (newValue === 2) {
    tagTitle.value = '修改'
  } else {
    tagTitle.value = '添加'
  }
});
// 计算是修改分类还是添加分类
const isAddOrEditTitle =ref('')
watch(is_CatEditOraddUrlData, (newValue, oldValue) => {
  // 在值发生变化时执行相应的逻辑
  console.log(newValue);
  if (newValue === 1) {
    isAddOrEditTitle.value = '添加'
  } else if (newValue === 2) {
    isAddOrEditTitle.value = '修改'
  } else {
    isAddOrEditTitle.value = '添加'
  }
});


onMounted(() => {
    useUrlDiyData.getHomeSidebarWebsites()
})

// 计算侧边栏宽度
const { width, height } = useWindowSize()
let drawerSize = ref('')
watch(width, (newValue, oldValue) => {
    if (newValue >= 1280) {
        drawerSize.value = '800px'
    } else if (newValue >= 1200) {
        drawerSize.value = '700px'
    } else if (newValue >= 992) {
        drawerSize.value = '500px'
    } else if (newValue >= 768) {
        drawerSize.value = '400px'
    } else {
        drawerSize.value = '270px'
    }

}, { immediate: true })


defineExpose({
    open,
    close
})

</script>
<script>
export default {
    name: '侧边栏组件',
}
</script>
<style lang="scss" scoped>
.content {
    span {
        @apply mb-2;
    }

    button {
        @apply mb-2;
    }
}
</style>