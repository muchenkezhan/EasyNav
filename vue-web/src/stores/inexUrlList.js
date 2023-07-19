import { ref, computed, reactive } from 'vue'
import { defineStore } from 'pinia'
import { reqHomeWebsitL, setHomeUrl } from "@/apis/homeSite";

export const useInexUrlListStore = defineStore('inexUrlList', () => {
  // 测试接口函数
  const url_List = ref([])
  const getHomeUrlData = async () => {
    let res = await reqHomeWebsitL()
    if (res.code == 200) {
      url_List.value = res.data
    }
  }
  return {
    url_List,
    getHomeUrlData
  }
}, {
  persist: true,
})
