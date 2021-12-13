<template>
    <div class="overflow-x-hidden">
        <div class="flex flex-1 flex-col">

            <div v-if="loading">Loading...</div>
            <div v-else>

                <div class="text-blue-400">
                    <a href="#" @click="$router.back()">< Back</a>
                </div>

                <div class="pt-8">
                    <img class="" v-bind:src="objPost.url" alt="">
                </div>

                <form @submit.prevent="submitPost">
                    <div class="py-8">
                        <label for="description" class="text-blue-500 pt-2 uppercase text-xs font-bold">Description</label>
                        <div class="pt-5">
                            <ckeditor id="description" type="classic" v-model="objPost.description"></ckeditor>
                        </div>
                    </div>

                    <div class="pb-2">
                        <label for="tag" class="text-blue-500 pt-2 uppercase text-xs font-bold">Tags</label>
                        <input id="tag" type="text" placeholder="All the tag inputs must be devied by ','" class="pt-5 w-full border-b pb-2 focus:outline-none focus:border-blue-400" v-model="objPost.tags" @input="clearError('error_tag')">
                    </div>
                    <p id="error_tag" class="text-red-600 text-sm font-bold"></p>
                
                    <div class="flex justify-end pt-8">
                        <button id="submtiButton" class="bg-blue-500 py-2 px-4 text-white rounded hover:bg-blue-400">Save</button>
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

    data() {
        return {
            post: {},
            loading: true,
            vaildTags: []
        }
    },

    mounted() {
        axios.get('/api/imgs/' + this.$route.params.id)
            .then( response => {
                this.objPost = response.data.data;
                this.objPost.tags = this.objPost.tags.toString();
                this.loading = false;
            })
            .catch( errors => {
                if (errors.response.status === 404 || errors.response.status === 403)
                {
                    this.$router.push('/imgs');
                }
            });
        
        this.fetchAllTag();
    },

    methods: {
        fetchAllTag()
        {
            axios.get('/api/tags')
                .then((res) => {
                    this.vaildTags = res.data.map((each) => {
                        return each.tag_name;
                    });
                })
                .catch((err) => {
                    console.log(err);
                })
        },

        isTagDuplicated(array) 
        {
            return (new Set(array)).size !== array.length;
        },

        isTagsValid(tags)
        {
            for (let each of tags)
            {
                if (this.vaildTags.indexOf(each) === -1)
                {
                    return false;
                }
            }
            return true;
        },

        submitPost()
        {
            if (this.objPost.tags === "")
            {
                this.displayError('tag', "Requires 1 Tag at least");
                return;
            }

            let tags = this.objPost.tags.split(",");
            if (this.isTagDuplicated(tags))
            {
                this.displayError('tag', "Inserted Tags aren't unique");
                return;
            }

            if (!this.isTagsValid(tags))
            {
                this.displayError('tag', "Tag names inserted incorrectly");
                return;
            }
            console.log('Tag validation passed');

            document.getElementById("submtiButton").disabled = true;

            axios.patch('/api/imgs/' + this.$route.params.id, {...this.objPost, api_token: this.api_token})
                    .then((res) => {
                        this.$router.push(res.data.links.self);
                    })
                    .catch((err) => {
                        // TODO: return proper error message
                        console.log(err);
                    });
        },


        displayError(target, msg, turnOnBtn = false)
        {
            document.getElementById(`error_${target}`).innerHTML = msg;
            if(turnOnBtn)
            {
                document.getElementById("submitButton").disabled = false;
            }
        },

        clearError(target)
        {
            document.getElementById(target).innerHTML = "";
        },
    }
}
</script>

<style scoped>

</style>