// 搜索功能的代码
import { ref, reactive } from 'vue'
import axios from 'axios';

export function seachHook(opt = {}) {
    const formLabelWidth = '40px'
    // 搜索关键词
    const keyword = ref('')
    // 搜索引擎
    const select = ref('google')
    // 搜索引擎列表数据
    const engineList = reactive([{
        "name": "谷歌",
        "value": "google",
        "href": "https://www.google.com/search?q=",
        "sugurl": "https://suggestqueries.google.com/complete/search?client=youtube&q=#content#&jsonp=window.google.ac.h",
        "icon": "./img/engineLogo/google.ico",
        "show": true,
        "select": ""
    }, {
        "name": "必应",
        "value": "bing",
        "href": "https://cn.bing.com/search?q=",
        "sugurl": "https://api.bing.com/qsonhs.aspx?type=cb&q=#content#&cb=window.bing.sug",
        "icon": "./img/engineLogo/bing.ico",
        "show": true,
        "select": ""
    }, {
        "name": "百度",
        "value": "baidu",
        "href": "https://www.baidu.com/s?wd=",
        "sugurl": "https://suggestion.baidu.com/su?wd=#content#&cb=window.baidu.sug",
        "icon": "./img/engineLogo/baidu.svg",
        "show": true,
        "select": "selected"
    }, {
        "name": "搜狗",
        "value": "sougou",
        "href": "https://www.sogou.com/web?query=",
        "sugurl": "https://www.sogou.com/suggnew/ajajjson?type=web&key=#content#",
        "icon": "./img/engineLogo/sougou.ico",
        "show": true,
        "select": ""
    }, {
        "name": "好搜",
        "value": "好搜",
        "href": "https://www.so.com/s?ie=utf-8&fr=hao_360so&src=home_hao_360so&q=",
        "sugurl": "https://sug.so.360.cn/suggest?encodein=utf-8&encodeout=utf-8&format=json&word=#content#&callback=window.so.sug",
        "icon": "./img/engineLogo/so360.ico",
        "show": true,
        "select": ""
    }, {
        "name": "Magi",
        "value": "magi",
        "href": "https://magi.com/search?q=",
        "sugurl": "https://magi.com/suggest?size=8&q=#content#&callback=window.magi.sug",
        "icon": "./img/engineLogo/magi.png",
        "show": true,
        "select": ""
    }, {
        "name": "夸克",
        "value": "quark",
        "href": "https://quark.sm.cn/s?q=",
        "sugurl": "https://quark.sm.cn/api/quark_sug?q=#content#&callback=window.quark.sug",
        "icon": "./img/engineLogo/quark.ico",
        "show": true,
        "select": ""
    }])



    // 枚举网址列表
    const SitesData = {}
    // 当前属性
    engineList.forEach(engine => {
        SitesData[engine.value] = engine.href
    })
    // 点击搜索
    const to_search = () => {
        // console.log('开始搜索了');
        if (!keyword.value) return;
        window.location.href = `${SitesData[select.value]}${keyword.value}`
    }
    // 下面都是百度下拉提示词功能
    const keywordArr = ref([])
    const fetchSuggestions = () => {
        // 创建一个 script 标签
        const script = document.createElement('script');
        // 定义 JSONP 回调函数名
        const callbackName = 'handleSuggestions';
        // 将回调函数绑定到全局作用域
        window[callbackName] = (data) => {
          // 在这里处理返回的提示词数据
          const suggestionsData = data.s;
          console.log(suggestionsData);
          keywordArr.value = suggestionsData
          // 移除创建的 script 标签
          document.head.removeChild(script);
        };
        // 设置 script 标签的 src 属性，触发 JSONP 请求
        script.src = `http://suggestion.baidu.com/su?wd=${keyword.value}&cb=${callbackName}`;
        // 将 script 标签添加到文档头部
        document.head.appendChild(script);
      };

    //组件中用到的数据要返回出去
    return {
        formLabelWidth,
        keyword,
        engineList,
        select,
        to_search,
        fetchSuggestions,
        keywordArr
    }
}