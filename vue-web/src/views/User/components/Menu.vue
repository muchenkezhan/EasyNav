<template>
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">

                        <li>
                            <a @click="$router.push('/user')" href="javascript:;"
                                class="hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700">
                                账户设置
                            </a>
                        </li>

                        <li>
                            <a @click="$router.push('/user/personalise')" href="javascript:;"
                                class="hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700">
                                个性设置
                            </a>
                        </li>
                        <!-- <li>
                            <a @click="$router.push('/user/preferences')" href="#"
                                class="hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700">
                                偏好设置
                            </a>
                        </li> -->
                        <li>
                            <a @click="$router.push('/user/server')" href="javascript:;"
                                class="hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700">
                                <!-- <svg class="h-6 w-6 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
              </svg> -->
                                服务器列表
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped></style>