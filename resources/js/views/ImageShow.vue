<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-if="loading">Loading...</div>
            <div v-else>
            
                <div class="flex justify-between">
                    <div v-if="isTaged">
                        <router-link :to="{ name: 'Tags', params: { currentPageIndex: index } }">< Back</router-link>
                    </div>
                    <div v-else>
                        <router-link :to="{ name: 'Index', params: { currentPageIndex: index } }">< Back</router-link>
                    </div>
                        
                    <div class="relative">
                        <div>
                            <div class="inline-block" v-if="post.user_id != user_id">
                                <div v-if="post.reported_count < this.$maxReportedCount">
                                    <a v-on:click.prevent="report" class="px-4 py-2 rounded text-sm text-red-500 border border-red-500 text-sm font-bold cursor-pointer">Report ({{ post.reported_count }})</a>    
                                </div>
                                <div v-else>
                                    <a v-on:click.prevent="modal = !modal" class="px-4 py-2 rounded text-sm text-red-500 border border-red-500 text-sm font-bold cursor-pointer">Delete</a>
                                </div>
                            </div>
                            
                            <div class="inline-block" v-if="post.user_id == user_id || user_id == this.$adminId">
                                <router-link v-bind:to="'/imgs/' + post.image_id + '/edit'" class="px-4 py-2 rounded text-sm text-green-500 border border-green-500 text-sm font-bold mr-4">Edit</router-link>
                                <a v-on:click.prevent="modal = !modal" class="px-4 py-2 rounded text-sm text-red-500 border border-red-500 text-sm font-bold cursor-pointer">Delete</a>
                            </div>
                        </div>

                        <div v-if="modal" class="absolute bg-blue-900 text-white rounded-lg z-20 p-8 w-64 right-0 mt-2 mr-6">
                            <p>Are you sure you want to delete this Image?</p>

                            <div class="flex items-center mt-6 justify-end">
                                <button class="text-white pr-4" @click="modal = !modal">Cancel</button>
                                <button class="px-4 py-2 bg-red-500 rounded text-sm font-bold text-white" @click="destroy">Delete</button>
                            </div>
                        </div>
                    </div>

                    <div v-if="modal" class="bg-black opacity-25 absolute right-0 left-0 top-0 bottom-0 z-10" @click="modal = !modal"></div>
                </div>

                <div v-if="post.format === 'webm'">
                    <video class="" controls loop>
                        <source v-bind:src="post.url" type="video/webm">
                    </video>
                </div>
                <div v-else>
                    <div class="pt-8">
                        <img v-bind:src="post.url" alt="">
                    </div>
                </div>

                

                <div class="">
                    <div class="border-b border-gray-500">
                        <p class="pt-6 text-gray-600 font-bold uppercase text-sm">Description</p>
                        <p class="px-2 py-4" v-html="post.description"></p>
                    </div>

                    <p class="pt-6 text-gray-600 font-bold uppercase text-sm">Tags</p>
                    <div class="flex flex-row px-2 py-4 border-b border-gray-500">
                        <div v-bind:key="tag" v-for="tag in post.tags">
                            <span class="inline-block bg-gray-300 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-4">{{ tag }}</span>
                        </div>
                    </div>

                    <p class="pt-6 pb-2 text-gray-600 font-bold uppercase text-sm">Uploader</p>
                    <router-link :to="{ path: '/imgs/index/' + post.user_id }">{{ post.user_name }}</router-link>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "ImageShow",

    props: [
        'api_token',
        'user_id',
    ],

    data()
    {
        return {
            post: null,
            modal: false,
            loading: true,
            isTaged: false,
            index: 0,
        }
    },

    mounted()
    {
        if (this.$route.params.currentPageIndex != null)
        {
            console.log(this.$route.params.currentPageIndex);
            this.index = this.$route.params.currentPageIndex;
        }
        
        axios.get('/api/imgs/' + this.$route.params.id)
                .then( response => {
                    this.post = response.data.data;
                    this.loading = false;
                })
                .catch( errors => {
                    if (errors.response.status === 404 || errors.response.status === 403)
                    {
                        this.$router.push('/imgs');
                    }
                });
            
        if (this.$route.params.isTaged)
        {
            this.isTaged = true;
        }
    },

    methods: {
        report()
        {
            axios.get('/api/imgs/' + this.$route.params.id + '/report')
                .then( response => {
                    this.post.reported_count = response.data;
                })
                .catch( error => {
                    console.log(error);
                });
        },

        destroy()
        {
            axios.delete('/api/imgs/' + this.$route.params.id + '?api_token=' + this.api_token)
                    .then( response => {
                        this.$router.push('/imgs');
                    })
                    .catch( errors => {
                        console.log(errors);
                        alert("Internal Error. Unable to delete Image");
                    });
        }
    }
}
</script>

<style scoped>

</style>