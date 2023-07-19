import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import {getStringData} from '@/apis/homeSite.js'
export const basicInformationStore = defineStore('basicInformation', () => {
  const basicInformationDate = ref({})
  const getbasicInformationDate = async()=>{
      let res =await getStringData()
      if(res.code == 200){
        basicInformationDate.value = res.data
      }
  }

  getbasicInformationDate()
  return {getbasicInformationDate,basicInformationDate }
}, {
    persist: true,
  })
