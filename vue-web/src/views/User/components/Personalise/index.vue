<template>
    <div>
            <div class="flex items-center justify-center pb-10" v-if="img_url_input" >
                <img :src="img_url_input" width="200" height="auto">
            </div>
        <div class="demo-collapse">
            <el-collapse v-model="activeName" accordion>
                <el-collapse-item title="背景图片" name="1">
                    <div class="bg-gray-100 p-3 rounded">
                        <div class="flex items-center p-5 justify-between">
                            <span class="pr-5">图片模式</span>
                            <el-select v-model="value" class="m-2" placeholder="Select">
                                <el-option v-for="item in options" :key="item.value" :label="item.label"
                                    :value="item.value" />
                            </el-select>

                        </div>
                        <!-- <el-divider /> -->

                        <div class="text-center" v-if="value == '1'">
                            <div class="flex max-w-md">
                                <el-input v-model="img_url_input" placeholder="填写图片外联" class="pr-6" />
                                <el-button type="primary" plain @click="set_bg_img_pina">保存</el-button>
                            </div>
                        </div>
                        <div class="" v-if="value == '2'">
                            <el-upload v-model:file-list="fileList" ref="uploadRef" class="upload-demo"
                                action="https://run.mocky.io/v3/9d059bf9-4660-45f2-925d-ce80ad6c4d15" :auto-upload="false">
                                <template #trigger>
                                    <el-button type="primary">select file</el-button>
                                </template>

                                <el-button class="ml-3" type="success" @click="submitUpload">
                                    upload to server
                                </el-button>

                                <template #tip>
                                    <div class="el-upload__tip">
                                        jpg/png files with a size less than 500kb
                                    </div>
                                </template>
                            </el-upload>
                        </div>


                    </div>
                </el-collapse-item>
                <!-- <el-collapse-item title="主题颜色" name="2">
                    <div class="p-3">
                        首页背景遮罩明暗度
                        <el-slider v-model="color_value" show-input />
                    </div>
                </el-collapse-item> -->

            </el-collapse>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { personalization } from '@/stores/personalization.js'
const personalizationPina = personalization()
// 图片在线链接
const img_url_input = ref(personalizationPina.bg_img)
const set_bg_img_pina = () => {
    if(!img_url_input.value){
       return ElMessage({
            message: '链接未填写.',
            type: 'error',
        })
    }
    personalizationPina.editSettingPersonalise(img_url_input.value)
}


const activeName = ref('1')
const value = ref('1')
const options = [
    {
        value: '1',
        label: '在线图片',
    },
    
]
// 
// 主题颜色
const color_value = ref(60)

// 上传图片

const fileList = ref([
    {
        name: 'food.jpeg',
        url: 'https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100',
    },
])

const uploadRef = ref()

const submitUpload = () => {
    //   uploadRef.value.submit()
    console.log(fileList.value[0]);
}



</script>

<style lang="scss" scoped></style>