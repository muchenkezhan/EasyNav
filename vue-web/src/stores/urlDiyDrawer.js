import { ref, computed, reactive } from 'vue'
import { defineStore } from 'pinia'
import { ElMessage } from 'element-plus'
import { reqHomeSidebarWebsites, addSidebarWebsitesCategory, addSidebarWebsitesCategoryUrl } from "@/apis/homeSite";
export const useUrlDiyStore = defineStore('urlDiy', () => {
    const count = ref(0)
    // 网址分类
    // const categoryList = ref([{
    //     title: "媒体视频",
    //     child: [
    //         {
    //             title: "腾讯视频",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "bilibili",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "爱奇艺",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "youtube",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //     ]
    // },
    // {
    //     title: "Ui设计",
    //     child: [
    //         {
    //             title: "土豆视频",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "bilibili",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "爱奇艺",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "youtube",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //     ]
    // },
    // {
    //     title: "Ui设计",
    //     child: [
    //         {
    //             title: "腾讯视频",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "bilibili",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "爱奇艺",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //         {
    //             title: "youtube",
    //             value: "google",
    //             href: "https://www.google.com/search?q=",
    //             icon: "./img/engineLogo/google.ico",
    //         },
    //     ]
    // },
    // ],)
    /*const categoryList = ref(
        [{
            title: "媒体视频",
            child: [
                {
                    title: "腾讯视频",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "bilibili",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "爱奇艺",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "youtube",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
            ]
        },
        {
            title: "Ui设计",
            child: [
                {
                    title: "土豆视频",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "bilibili",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "爱奇艺",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "youtube",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
            ]
        },
        {
            title: "Ui设计",
            child: [
                {
                    title: "腾讯视频",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "bilibili",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "爱奇艺",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
                {
                    title: "youtube",
                    value: "google",
                    href: "https://www.google.com/search?q=",
                    icon: "./img/engineLogo/google.ico",
                },
            ]
        },
        ],
    )*/
    const categoryList = ref([])
    // 获取api网址分类
    const getHomeSidebarWebsites = async () => {
        let res = await reqHomeSidebarWebsites()
        if (res.code == 200 && res.data != 'noData') {
            categoryList.value = res.data.sort(function (a, b) {
                if (a.sort_order == 0 && b.sort_order == 0) {
                    return 0;
                } else if (a.sort_order == 0) {
                    return 1;
                } else if (b.sort_order == 0) {
                    return -1;
                } else {
                    return a.sort_order - b.sort_order;
                }
            }).map(function (obj) {
                if(obj.child){
                    obj.child.sort(function (a, b) {
                        if (a.sort_order == 0 && b.sort_order == 0) {
                            return 0;
                        } else if (a.sort_order == 0) {
                            return 1;
                        } else if (b.sort_order == 0) {
                            return -1;
                        } else {
                            return a.sort_order - b.sort_order;
                        }
                    });
                }
                return obj;
               
            });
        }else if(res.code == 200 && res.data == 'noData'){
            categoryList.value = []
            
        }
    }
    // 修改当前网址数据
    const setWebsiteList = (data) => {
        categoryList.value = data.sort(function (a, b) {
            if (a.sort_order == 0 && b.sort_order == 0) {
                return 0;
            } else if (a.sort_order == 0) {
                return 1;
            } else if (b.sort_order == 0) {
                return -1;
            } else {
                return a.sort_order - b.sort_order;
            }
        });

    }
    // 添加分类
    const addCategoryItem = async (title) => {
        let res = await addSidebarWebsitesCategory(title)
        if (res.code == 200) {
            getHomeSidebarWebsites()
        }
    }

    // 删除当前分类
    const deleteCategory = (parentIndex) => {
        if (categoryList.value[parentIndex].child.length == 0) {
            categoryList.value.splice(parentIndex, 1);
        } else {
            ElMessage({
                message: '请确保当前分类下没有网址.',
                type: 'warning',
            })
        }
    }
    return { categoryList, addCategoryItem, deleteCategory, getHomeSidebarWebsites, setWebsiteList }
}, {
    persist: true,
})

