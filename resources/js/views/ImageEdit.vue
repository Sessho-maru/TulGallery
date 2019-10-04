<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-if="loading">Loading...</div>
            <div v-else>

                <div class="text-blue-400">
                        <a href="#" @click="$router.back()">< Back</a>
                </div>

                <div class="pt-8">
                    <div class="">
                        <img class="" v-bind:src="post.url" alt="">
                    </div>
                </div>

                <form @submit.prevent="submitPost">
                    <div class="py-8">
                        <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                        <div class="pt-5">
                            <ckeditor id="description" type="classic" v-model="post.description"></ckeditor>
                        </div>
                    </div>

                    <div class="pb-2">
                        <label for="tag" class="text-blue-500 pt-2 uppercase text-xs font-bold">Tags</label>
                        <input id="tag" type="text" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" v-model="post.tags" @input="clearError('error_tag')">
                    </div>
                    <p id="error_tag" class="text-red-600 text-sm font-bold"></p>
                
                    <div class="flex justify-end pt-8">
                        <button class="bg-blue-500 py-2 px-4 text-white rounded hover:bg-blue-400">Save</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "ImageEdit",

    props: [
        'api_token'
    ],

    data()
    {
        return {
            post: "",
            loading: true
        }
    },

     mounted()
    {
        axios.get('/api/imgs/' + this.$route.params.id + '?api_token=' + this.api_token)
            .then( response => {
                this.post = response.data.data;
                this.post.tags = this.post.tags.toString();
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

        submitPost()
        {

            if (this.post.tags === "")
            {
                document.getElementById('error_tag').innerHTML = "Require at least 1 Tag";
                return;
            }

            let tags = this.post.tags.split(",");
            if (this.hasDuplicates(tags) == true)
            {
                document.getElementById('error_tag').innerHTML = "Inserted Tags aren't unique";
                return;
            }

            this.post.tags = this.post.tags.concat(",");
            axios.patch('/api/imgs/' + this.$route.params.id, {...this.post, api_token: this.api_token, flag: "post_update"})
                    .then( response => {
                        this.$router.push(response.data.links.self);
                    })
                    .catch( errors => {
                        document.getElementById('error_tag').innerHTML = errors.response.data.msg;
                    });
        },

        hasDuplicates(array) {
            return (new Set(array)).size !== array.length;
        },

        clearError(field)
        {
            document.getElementById(field).innerHTML = "";
        }
    }
}
</script>

<style scoped>

</style>