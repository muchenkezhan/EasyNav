import ajax from "@/apis/http.js";

// 获取首页网址列表
export function reqHomeWebsitL() {
    return ajax({
        url: 'v1/get-nav-home-data',
        method:'post',
    })
}
// 获取首页侧边栏网址分类列表
export function reqHomeSidebarWebsites() {
    return ajax({
        url: 'v1/get-sidebar-website?timestamp=' + Math.random()
    })
}

// 新建首页url
export function setHomeUrl(title,url) {
    return ajax({
        url: 'v1/add-nav-home-data',
        method:'post',
        data:{
            title:title,
            url:url,
        }
    })
}

// 修改首页url
export function editHomeUrl(title,url,id) {
    return ajax({
        url: 'v1/edit-nav-home-data',
        method:'post',
        data:{
            title:title,
            url:url,
            id:id,
        }
    })
}
// 删除首页url
export function deleteHomeUrl(id) {
    return ajax({
        url: 'v1/delete-nav-home-data',
        method:'post',
        data:{
            id:id,
        }
    })
}
// 侧边栏排序分类
export function updateCategorySort(data) {
    return ajax({
        url: 'v1/sort_sidebar-category',
        method:'post',
        data:{
            data:data
        }
    })
}

// 侧边栏添加分类
export function addSidebarWebsitesCategory(data) {
    return ajax({
        url: 'v1/add-sidebar-website-category',
        method:'post',
        data:{
            title:data,

        }
    })
}

// 编辑侧边栏添加分类
export function editSidebarWebsitesCategory(title,cat_id) {
    return ajax({
        url: 'v1/edit-sidebar-website-category',
        method:'post',
        data:{
            title:title,
            cat_id:cat_id,

        }
    })
}

// 添加分类下的网址
export function addSidebarWebsitesCategoryUrl(categoryId,form) {
    let {title,url}=form;
    return ajax({
        url: 'v1/add-sidebar-category-website-url',
        method:'post',
        data:{
            categores_id:categoryId,
            title:title,
            url:url,
            sort_order:0,
            icon:'',
            description:'',

        }
    })
}


// 删除网址分类
export function deleteSidebarWebsitesCategory(categoryId) {
    return ajax({
        url: 'v1/delete-sidebar-website-category',
        method:'post',
        data:{
            category_id:categoryId,
        }
    })
}

// 删除网址分类下的网址
export function deleteSidebarWebsitesCategoryUrl(categoryId,urlId) {
    return ajax({
        url: 'v1/delete-sidebar-category-website-url',
        method:'post',
        data:{
            category_id:categoryId,
            id:urlId,
        }
    })
}

// 更新网址分类排序
export function updateCategoryWebsiteSort(data,categoryId) {
    return ajax({
        url: 'v1/sort_sidebar-category-website',
        method:'post',
        data:{
            data:data,
            categoryId:categoryId
        }
    })
}

// 修改diy网址分类下的子网址
export function updateCategoryWebsiteData(title,url,categoryId,id) {
    return ajax({
        url: 'v1/edit-sidebar-category-website-url',
        method:'post',
        data:{
            title:title,
            url:url,
            categoryId:categoryId,
            id:id
        }
    })
}

// 获取基本配置数据，不用身份验证地方那种
export function getStringData() {
    return ajax({
        url: 'v1/home_data',
        method:'GET',
    })
}