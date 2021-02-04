<template>
    <div class="overflow-x-hidden">

        <div v-bind:key="each.data.image_id" v-for="each in currentPage" class="w-1/2 md:w-1/3 lg:w-1/6">
            <!-- <div v-if="each.data.format === 'webm'">
                <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, currentPageIndex: index } }">
                    <video class="block h-64 w-64 object-none object-cover hover:opacity-75">
                        <source v-bind:src="each.data.url" type="video/webm">
                    </video>
                </router-link>
            </div> // developing... -->
            <div>
                <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id } }">
                    <img class="block h-64 w-64 object-none object-cover hover:opacity-75" v-bind:src="each.data.thumbnail_url" alt="placeholder">
                </router-link>
            </div>
        </div>

        <div class="absolute bottom-0 mb-10">
            <button class="mr-2 px-3 py-1 rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage('-')">Prev</button>
            <button class="ml-2 px-3 py-1 rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage('+')">Next</button>
        </div>

    </div>
</template>

<script>
export default {

    name: "ImageIndex",
    
    data() {
        return {
            fetched: [],
            paginated: [],
            currentPage: [],
            index: 0,
        }
    },

    mounted() {

        console.log("this.$globalParams: ", this.$globalParams);
        
        // with Params
        if (this.$route.params.indexWithParam !== undefined)
        {
            if (this.$route.params.indexWithParam.postedBy !== undefined)
            {
                this.$globalParams.postedBy = this.$route.params.indexWithParam.postedBy;
                
                console.log("All posts posted by user: ", this.$globalParams.postedBy);
                axios.get('/api/imgs/user/' + this.$globalParams.postedBy)
                    .then( response => {
                        this.fetched = response.data.data;
                        this.pagenatedPosts(this.fetched);
                    })
                    .catch( errors => {
                        console.log(errors);
                        alert("Unable to Fetch Images");
                    });
            }
        }
        else
        {
            axios.get('/api/imgs')
                .then( response => {
                    this.fetched = response.data.data;
                    this.pagenatedPosts(this.fetched);
                })
                .catch( errors => {
                    console.log(errors);
                    alert("Unable to Fetch Images");
                });
        }
    },

    methods: {
        pagenatedPosts(posts)
        {
            console.log("Received image posts", posts);
            let pageSize = Math.ceil(posts.length / this.$itemNumPerPage);

            for (let i = 0; i < pageSize; i++)
            {
                this.paginated[i] = new Array();
            }

            let total = posts.length - 1;
            let i = 0;
            let j = 0;

            for (total; total >= 0; total--)
            {
                this.paginated[i][j] = posts[total];

                if (j % (this.$itemNumPerPage - 1) === 0 && j !== 0)
                {
                    i++;
                    j = 0;
                }
                else
                {
                    j++;
                }
            } // split entire image posts per $maxSizePerEachItem(24) newest first order

            this.changeCurrentPage();

            console.log("Pagenated content", this.paginated);
            console.log("============================");
        },

        changeCurrentPage(char = undefined)
        {   
            if (char !== undefined)
            {
                if (char === '+')
                {
                    if (this.$globalParams.currentPageIndex < (this.paginated.length) - 1)
                    {
                        this.$globalParams.currentPageIndex += 1;
                    }
                }

                if (char === '-')
                {
                    if (this.$globalParams.currentPageIndex > 0)
                    {
                        this.$globalParams.currentPageIndex -= 1;
                    }
                }
            }

            console.log("current Page Index: " + this.$globalParams.currentPageIndex);
            this.currentPage = this.paginated[this.$globalParams.currentPageIndex];
        }
    }
}
</script>

<style scoped>

</style>