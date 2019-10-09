<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-if="loading">Loading...</div>
            <div v-else>
            
                <div class="flex justify-between">

                    <div v-if="mode == 'normal'" class="text-blue-400">
                        <a href="#" @click="$router.push('/imgs')">< Back</a>
                    </div>

                    <div v-if="mode == 'tag'">
                        <router-link :to="{ name: 'Tags', params: { t: tags } }">< Back</router-link>
                    </div>
                    
                    <div class="relative">
                        <div v-if="post.user_id == user_id || user_id == 3">
                            <router-link v-bind:to="'/imgs/' + post.image_id + '/edit'" class="px-4 py-2 rounded text-sm text-green-500 border border-green-500 text-sm font-bold mr-4">Edit</router-link>
                            <a href="#" class="px-4 py-2 rounded text-sm text-red-500 border border-red-500 text-sm font-bold" @click="modal = !modal">Delete</a>
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


                <div class="pt-8">
                    <div class="">
                        <a v-bind:href="post.url" target="_blank"><img v-bind:src="post.url" alt=""></a>
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
            tags: [],
            mode: ""
        }
    },

    mounted()
    {
        if (this.$route.params.mode == 'normal' || this.$route.params.mode == null)
        {
            this.mode = "normal";
        }
        else
        {
            this.tags = this.$route.params.t;
            this.mode = "tag";
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
    },

    methods: {
        destroy()
        {
            axios.delete('/api/imgs/' + this.$route.params.id + '?api_token=' + this.api_token)
                    .then( response => {
                        this.$router.push('/imgs');
                    })
                    .catch( errors => {
                        alert("Internal Error. Unable to delete Image");
                    });
        }
    }
}
</script>

<style scoped>

</style>