<template>

    <div class="h-screen bg-white">
        <div class="flex">

            <div class="pl-6 bg-gray-200 w-48 h-screen border-r-2 border-gray-400">

                <nav class="pt-4">

                    <router-link to="/">
                           <svg class="fill-current text-blue-600 w-12 h-12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M3.9 4.2c-.5 0-.8.3-.8.8s.4.8.8.8c.5 0 .8-.4.8-.8.1-.5-.3-.8-.8-.8zM3.3 18.6c0 1.4-.2 2.1-1.5 2.1-.3 0-.7 0-.9-.1l-.3 1.1c.3.1.7.2 1.1.2 1.9 0 2.7-1.2 2.7-3.2V8.1H3.3v10.5zM9.4 17.5c1.4 0 2.3-.4 3-1.2.8-1 1.1-2.1 1.1-3.8 0-1.4-.2-2.7-1-3.5-.6-.7-1.5-1.1-2.9-1.1s-2.3.4-3 1.2c-.8 1-1.1 2.2-1.1 3.8 0 1.5.2 2.6 1 3.5.6.7 1.5 1.1 2.9 1.1zM7.5 9.7c.3-.4.9-.8 2-.8 1 0 1.6.3 1.9.7.5.6.7 1.7.7 2.9s-.2 2.4-.7 3.1c-.3.4-.9.8-2 .8-1 0-1.6-.3-1.9-.7-.5-.6-.7-1.6-.7-2.9 0-1.2.2-2.4.7-3.1zM15 14.4c0 2.1.4 3.1 2.5 3.1.6 0 1.3-.1 1.8-.2l-.1-1c-.5.1-1 .2-1.5.2-1.4 0-1.5-.6-1.5-2.1v-5h3V8.3h-3v-3l-1.2.2v2.7h-1.8v1.1H15v5.1zM6 18h17v1H6z"/>
                            </svg> 
                    </router-link>

                    <p class="pt-12 text-gray-500 uppercase font-bold text-xs">Create</p>
                    <router-link to="/imgs/create" class="flex items-center py-2 hover:text-blue-300 text-sm">
                        <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                            <path d="M23.3 11.9c0 .9-.6 1.4-1.4 1.4h-8.5v8.5c0 .9-.6 1.4-1.4 1.4s-1.4-.6-1.4-1.4v-8.5H2c-.9 0-1.4-.6-1.4-1.4 0-.9.6-1.4 1.4-1.4h8.5V1.9c0-.9.6-1.4 1.4-1.4s1.4.6 1.4 1.4v8.5h8.5c.9 0 1.5.6 1.5 1.5z"/>.
                        </svg>

                        <div class="pl-3 tracking-wise">Add New</div>
                    </router-link>


                    <p class="pt-12 text-gray-500 uppercase font-bold text-xs">Posts</p>
                    <router-link to="/" class="flex items-center py-2 hover:text-blue-300 text-sm">
                        <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                            <path d="M23.3 11.9c0 .9-.6 1.4-1.4 1.4h-8.5v8.5c0 .9-.6 1.4-1.4 1.4s-1.4-.6-1.4-1.4v-8.5H2c-.9 0-1.4-.6-1.4-1.4 0-.9.6-1.4 1.4-1.4h8.5V1.9c0-.9.6-1.4 1.4-1.4s1.4.6 1.4 1.4v8.5h8.5c.9 0 1.5.6 1.5 1.5z"/>.
                        </svg>

                        <div class="pl-3 tracking-wise">Posts</div>
                    </router-link>

                    <router-link to="/" class="flex items-center py-2 hover:text-blue-300 text-sm">
                        <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                            <path d="M23.3 11.9c0 .9-.6 1.4-1.4 1.4h-8.5v8.5c0 .9-.6 1.4-1.4 1.4s-1.4-.6-1.4-1.4v-8.5H2c-.9 0-1.4-.6-1.4-1.4 0-.9.6-1.4 1.4-1.4h8.5V1.9c0-.9.6-1.4 1.4-1.4s1.4.6 1.4 1.4v8.5h8.5c.9 0 1.5.6 1.5 1.5z"/>.
                        </svg>

                        <div class="pl-3 tracking-wise">Search with Tags</div>
                    </router-link>


                    <p class="pt-12 text-gray-500 uppercase font-bold text-xs">Settings</p>
                    <router-link to="/" class="flex items-center py-2 hover:text-blue-300 text-sm">
                        <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                            <path d="M23.3 11.9c0 .9-.6 1.4-1.4 1.4h-8.5v8.5c0 .9-.6 1.4-1.4 1.4s-1.4-.6-1.4-1.4v-8.5H2c-.9 0-1.4-.6-1.4-1.4 0-.9.6-1.4 1.4-1.4h8.5V1.9c0-.9.6-1.4 1.4-1.4s1.4.6 1.4 1.4v8.5h8.5c.9 0 1.5.6 1.5 1.5z"/>.
                        </svg>

                        <div class="pl-3 tracking-wise">User</div>
                    </router-link>

                    <router-link to="/" class="flex items-center py-2 hover:text-blue-300 text-sm">
                        <svg viewBox="0 0 24 24" class="fill-current text-blue-600 w-5 h-5">
                            <path d="M23.3 11.9c0 .9-.6 1.4-1.4 1.4h-8.5v8.5c0 .9-.6 1.4-1.4 1.4s-1.4-.6-1.4-1.4v-8.5H2c-.9 0-1.4-.6-1.4-1.4 0-.9.6-1.4 1.4-1.4h8.5V1.9c0-.9.6-1.4 1.4-1.4s1.4.6 1.4 1.4v8.5h8.5c.9 0 1.5.6 1.5 1.5z"/>.
                        </svg>

                        <div class="pl-3 tracking-wise">Logout</div>
                    </router-link>

                </nav>

            </div> <!-- left section -->


            <div class="flex flex-col flex-1 h-screen">

                <div class="h-16 px-6 border-b border-gray-400 flex items-center justify-between">
                    <div>
                        Images
                    </div>

                    <div class="text-blue-600 font-bold">
                        `user_name`
                    </div>
                </div> <!-- top section -->

                <router-view class="flex flex-1 flex-wrap p-6">

                </router-view> <!-- bottom section -->
                
            </div> <!-- right section -->

        </div>
    </div>
</template>

<script>
export default {
    name: "App"
}
</script>

<style scoped>

</style>