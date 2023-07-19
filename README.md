# EasyNav 开源导航项目

wordpress + vue3  技术栈，前后端分离主题项目

 This template was developed in Vue 3 Wordpress. 

![image-20230719171101192](C:\Users\Qzdy\AppData\Roaming\Typora\typora-user-images\image-20230719171101192.png)

# 目录说明

### vue-web（目录）：

​		**vue前端项目**

### wordpress-server（目录）：

​		**后端api服务**

# 环境要求

## 服务器端环境要求

1.wordpress环境必须为8.0，其他版本没有测试

2.wordpress服务器端的文件为主题文件，vue-web目录的主题下载之后请确保目录的名称为“EasyNav ”

3.wordpress必须设置伪静态之后，在后设置永久固定连接

4.注意：必须把wordpress端主题设置的默认数据填写上

## 前端环境：

静态页面即可，不做要求



# vue配置说明

必须安装：nodejs

vue-web（目录）的项目下载本地电脑进行初始设置

## api对接说明：

项目下载之后，打开vite.config.js文件，把底部的`target: 'https://xxxx.xx/wp-json/',`中的`https://xxxx.xx`改为自己的网址；

## 下载依赖

```sh
npm install
```

### 运行测试（可不做）

```sh
npm run dev
```

### 项目打包

  打包完成之后在目录下的dist目录会出现前端项目，直接传到服务器即可

```sh
npm run build
```

### 