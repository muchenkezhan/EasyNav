<template>
    <div class="conter">
        <!-- <div  class="body-back bg-blue-50"></div> -->
        <div v-if="bgImgUrl" class="body-back bg-gray-500" :style="{ backgroundImage: `url(${bgImgUrl})` }"></div>
        <div class="main">
            <div class=" flex items-center justify-center">
                <div class="logo ">
                    <img :src="basicInformationData.basicInformationDate.logo" />
                </div>
            </div>
            <div class="px-6 py-20 sm:py-20 lg:px-8">
                <div class="mx-auto max-w-4xl relative">
                    <div class="search_box">
                        <el-input v-model="keyword" placeholder="搜索网页" clearable @keydown.enter.native="to_search()"
                            @focus="inputFocus" @blur="inputBlur" @input="handleInputChange">
                            <template #suffix>
                                <el-icon @click="to_search" class="cursor-pointer">
                                    <Search />
                                </el-icon>
                            </template>
                            <template #prepend>
                                <el-select v-model="select" placeholder="百度" style="max-width: 110px;">
                                    <el-option v-for="(item, index) in engineList" :label="item.name" :value="item.value" />
                                </el-select>
                            </template>
                        </el-input>
                    </div>
                    <!-- hidden -->
                    <div v-show="isKeywordArrNotEmpty" class="search_list max-w-2xl">
                        <div class="h-20 mt-2" style="width:100%;">
                            <ul class="absolute z-10 mt-1 w-full  rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                role="listbox" id="headlessui-combobox-options-30" data-headlessui-state="open"
                                aria-labelledby="headlessui-combobox-label-1" @keydown.enter.native.stop="handleEnterKey">
                                <li v-for="(item, index) in keywordArr" :key="index"
                                    :class="{ 'bg-gray-200': currentIndex === index }" @mouseenter="currentIndex = index"
                                    @mouseleave="currentIndex = -1" @click.stop="setKeyWordTitle(item)"
                                    class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-gray-200 ">
                                    <div class="flex items-center pic-box"><svg t="1689746207279" class="icon"
                                            viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            p-id="1666" width="16" height="16">
                                            <path
                                                d="M797.525333 752.266667c62.069333-72.736 97.28-165.002667 97.28-262.186667C894.816 266.528 713.621333 85.333333 490.08 85.333333 266.538667 85.333333 85.333333 266.538667 85.333333 490.069333 85.333333 713.610667 266.538667 894.826667 490.069333 894.826667a404.693333 404.693333 0 0 0 118.208-17.546667 32 32 0 0 0-18.666666-61.216 340.693333 340.693333 0 0 1-99.541334 14.762667C301.888 830.816 149.333333 678.261333 149.333333 490.069333 149.333333 301.888 301.888 149.333333 490.069333 149.333333 678.261333 149.333333 830.826667 301.888 830.826667 490.069333c0 89.28-35.381333 173.696-97.141334 237.322667a36.992 36.992 0 0 0 0.384 51.925333l149.973334 149.973334a32 32 0 0 0 45.258666-45.248L797.525333 752.266667z"
                                                fill="#000000" p-id="1667"></path>
                                        </svg><span class="ml-3 truncate">{{ item }}</span></div>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="content mt-10">
                        <ul
                            class="grid grid-cols-3 gap-6 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-8 justify-items-center gap-x-px">
                            <li class="text-center pt-2 pl-1 pr-1 flex items-center flex-col item relative"
                                :class="bgImgUrl ? 'text-white hover:text-gray-200' : 'text-gray-800  hover:text-gray-200'"
                                v-for="(item, index) in useInexUrlListStoreData.url_List" @click="goToUrl(item.url)">
                                <div class="control icon-only absolute">
                                    <div class="flex">
                                        <div>
                                            <el-popconfirm title="确认删除?" @confirm="deleteUrl(item)" @cancel="cancelEvent">
                                                <template #reference>
                                                    <span class="icon text-gray-100" @click.stop="">
                                                        <el-icon size="8">
                                                            <Delete />
                                                        </el-icon>
                                                    </span>
                                                </template>
                                            </el-popconfirm>
                                        </div>
                                        <span class="icon text-gray-100" @click.stop="edit_url(index, item)">
                                            <el-icon size="8">
                                                <MoreFilled />
                                            </el-icon>
                                        </span>
                                    </div>
                                </div>
                                <div class="w-12">
                                    <div
                                        class="col-span-1 divide-y divide-gray-200 rounded-lg backdrop-blur shadow bg-gray-500 bg-opacity-50">
                                        <div class="flex w-full items-center justify-between p-3">
                                            <div class="w-6 h-6">
                                                <img :src="`/jwt-auth/v1/get?url=${item.url}`" width="48" height="48">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs mt-2 truncate tag-item p-1 lg:p-3">{{ item.title }}</p>
                            </li>
                            <li
                                class="text-center pt-2 pl-1 pr-1 flex items-center justify-center flex-col end_add_Url relative">
                                <div class="w-12">
                                    <div
                                        class="add_icon col-span-1 divide-y divide-gray-200 rounded-lg backdrop-blur shadow  bg-opacity-50">
                                        <div class="flex w-full items-center justify-between p-3" @click="openAddUrl">
                                            <div class="w-6 h-6">
                                                <el-icon>
                                                    <Plus />
                                                </el-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- More people... -->
                        </ul>
                    </div>
                </div>
                <!-- 编辑 -->
                <el-dialog v-model="dialogFormVisible" title="修改网址">
                    <el-form :model="form">
                        <el-form-item label="名称" :label-width="formLabelWidth">
                            <el-input v-model="form.title" autocomplete="off" />
                        </el-form-item>
                        <el-form-item label="网址" :label-width="formLabelWidth">
                            <el-input v-model="form.url" autocomplete="off" />
                        </el-form-item>
                    </el-form>
                    <template #footer>
                        <span class="dialog-footer">
                            <el-button @click="dialogFormVisible = false">取消</el-button>
                            <el-button v-if="current || current == '0'" type="primary" @click="post_url()">
                                修改
                            </el-button>
                            <el-button v-if="!current && current != '0'" type="primary" @click="add_url()">
                                添加
                            </el-button>
                        </span>
                    </template>
                </el-dialog>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
// 仓库图片
import { personalization } from '@/stores/personalization.js'
import { userStore } from '@/stores/user.js'
import { basicInformationStore } from '@/stores/basicInformation.js'
const personalizationPina = personalization()
const userStoreData = userStore()

import { Search } from '@element-plus/icons-vue'
//hook  搜索功能代码
// hook 导航代码
import { urlListHook } from './hooks/urlList.js'
import { seachHook } from './hooks/seach.js'
import { useInexUrlListStore } from '@/stores/inexUrlList.js'
// 获取logo
const basicInformationData = basicInformationStore()
const useInexUrlListStoreData = useInexUrlListStore()
// 图片在线链接
const bgImgUrl = userStoreData.userdata.token ? personalizationPina.bg_img : basicInformationData.basicInformationDate.bg_img;

// 编辑框打开关闭
const dialogFormVisible = ref(false)
// 打开
const open = () => dialogFormVisible.value = true
const close = () => dialogFormVisible.value = false
const opacity = ref('0.5')
const {
    formLabelWidth,
    keyword,
    engineList,
    select,
    to_search,
    fetchSuggestions,
    keywordArr
} = seachHook({ open, close })


const {
    current,
    openAddUrl,
    form,
    edit_url,
    add_url,
    deleteUrl,
    post_url
} = urlListHook({ open, close })


// 新窗口打开跳转网址
const goToUrl = (url) => {
    window.open(url);
}
// 删除当前网址

const cancelEvent = () => {
}

const inputList = ref(1)
// 输入框获取焦点
const inputFocus = () => {
}
const inputBlur = () => {
    setTimeout(function () {
        inputList.value = 1
        keywordArr.value = ''
    }, 300)
}
// input值改变时触发
const handleInputChange = () => {
    if (keyword.value) {
        inputList.value = 2
    }
    fetchSuggestions()
}
//  下面都是百度搜索下标
// 计算属性：判断 keywordArr 数组长度是否大于等于1，返回 true 或 false
const isKeywordArrNotEmpty = ref(false);

// 监听 keywordArr 数组的变化
watch(keywordArr, (newValue) => {
    isKeywordArrNotEmpty.value = newValue.length >= 1;
});

// 定义当前选中的 <li> 索引
const currentIndex = ref(-1);

// 定义用于存储选中的关键字的变量

// 监听 keywordArr 数组的变化
watch(keywordArr, (newValue) => {
    currentIndex.value = -1; // 数组变化时重置 currentIndex
});

// 监听键盘事件
onMounted(() => {
    document.addEventListener('keydown', handleKeyDown);
});

// 处理键盘事件
const handleKeyDown = (event) => {
    // 当按下向上箭头时
    if (event.key === 'ArrowUp') {
        event.preventDefault(); // 阻止默认上移事件

        // 更新 currentIndex
        if (currentIndex.value > 0) {
            currentIndex.value--;
        }
    }
    // 当按下向下箭头时
    else if (event.key === 'ArrowDown') {
        event.preventDefault(); // 阻止默认下移事件

        // 更新 currentIndex
        if (currentIndex.value < keywordArr.value.length - 1) {
            currentIndex.value++;
        }
    }
    // 当按下回车键时
    else if (event.key === 'Enter') {
        event.preventDefault(); // 阻止默认提交表单事件
        if (isKeywordArrNotEmpty) {
            // 获取选中的关键字
            if (currentIndex.value >= 0 && currentIndex.value < keywordArr.value.length) {
                keyword.value = keywordArr.value[currentIndex.value];
            }
            to_search()
        }

    }
};
const setKeyWordTitle = (item) => {
    console.log('点击了');
    keyword.value = item
}

</script>

<style lang="scss" scoped>
::v-deep .el-input-group__prepend {
    @apply rounded-s-full;
}


::v-deep .el-input__inner {
    padding: 10px;
}

// 去掉搜索引擎边框
::v-deep .search_box .el-input-group__prepend * {
    box-shadow: 0px 0 0 0 #ffffff00 !important;
    @apply rounded-r-full;

    // 
    .search_box .el-input-group__prepend .is-focus {
        box-shadow: 0px 0 0 0 #ffffff00 !important;
        @apply rounded-r-full;
    }


    // 
    .el-input__wrapper {
        box-shadow: 0px 0 0 0 #ffffff00 !important;

        &:hover {
            box-shadow: 0px 0 0 0 #ffffff00 !important;
        }
    }
}

// 鼠标经过
::v-deep .el-input-group--prepend .el-input-group__prepend .el-select:hover .el-input__wrapper {
    z-index: 1;
    box-shadow: 0px 0 0 0 #ffffff00 !important;
}

// 获取焦点
::v-deep .el-input-group--prepend .el-input-group__prepend .el-select .el-input .el-input__wrapper {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    box-shadow: 0px 0 0 0 #ffffff00 !important;
}

::v-deep .el-input-group--prepend .el-input-group__prepend .el-select .el-input.is-focus .el-input__wrapper {
    box-shadow: 0px 0 0 0 #ffffff00 !important;
}

// ---------------------
::v-deep .search_box {

    .el-input__wrapper {
        @apply rounded-r-full p-1;

        // 搜索图标
        & .el-input__suffix {
            font-size: 25px;
            @apply p-2;
        }
    }

}

.end_add_Url {
    .add_icon {
        @apply bg-gray-500;

        &:hover {
            @apply bg-gray-600 cursor-pointer;
        }
    }


}

.item {
    width: 100px;

    &:hover {
        @apply rounded-lg cursor-pointer bg-gray-600 backdrop-blur;
    }


    .control {
        @apply top-0 right-0;

    }
}

.control {
    display: none;
    padding: 5px;
    z-index: 99;

    .icon {
        width: 15px;
        height: 15px;
        @apply rounded-sm flex items-center justify-center;

        &:hover {
            @apply bg-gray-400;
        }
    }
}

.item:hover .control {
    display: block !important;
}

.tag-item {
    max-width: 100px;
}

// 黑色遮罩
.body-back {
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: block;
    position: fixed;
    margin: 0px;
    padding: 0px;
    border: 0px none;
    outline: currentcolor none 0px;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.body-back::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.main {
    margin-top: 10%;

    .logo {
        max-width: 300px;
    }
}


::v-deep header {
    svg {
        color: aliceblue;
    }
}
</style>