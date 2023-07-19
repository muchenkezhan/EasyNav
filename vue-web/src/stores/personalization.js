import { ref,watch } from 'vue'
import { defineStore } from 'pinia'
import { reqGetSettingPersonalise, reqSetSettingPersonalise } from '@/apis/settings.js'
export const personalization = defineStore('personalization', () => {
  // bg-img
  const bg_img = ref('')
  const personalisesData = ref({})
  // 遮罩
  const homeBackgroundMask = ref('50')
  // 修改背景图片地址
  const set_bg_img = (img) => {
    bg_img.value = img
  }
  // 获取个性设置的数据
  const getSettingPersonalise = async () => {
    let res = await reqGetSettingPersonalise('personality')
    if (res.code === 200) {
      personalisesData.value = res.data.data
      bg_img.value= res.data.data.data[0].externalImage
    }
  }
  getSettingPersonalise()
    // 修改背景图片地址
  const editSettingPersonalise = async(weburl) => {
    let res = await reqSetSettingPersonalise('personality', weburl, '')
    if(res.code == 200){
      getSettingPersonalise()
    }
  }
  // 设置遮罩亮度

  return {
    bg_img,
    set_bg_img,
    editSettingPersonalise,
    personalisesData
  }
}, {
  persist: true,
})

// [{
//     code: '1',
//     child: {
//       type: 1,
//       externalImage: 'External Image',
//       serverImage: 'Server Image'
//     }
//   },
//   {
//     code: '2',
//     child: {}
//   }]