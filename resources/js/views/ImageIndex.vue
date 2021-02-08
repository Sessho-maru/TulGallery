<template>
    <div class="overflow-x-hidden">

        <div v-bind:key="each.data.image_id" v-for="each in currentPage" class="w-1/2 md:w-1/3 lg:w-1/6">
            <!-- <div v-if="each.data.format === 'webm'">
                <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, currentPageIndex: index } }">
                    <video class="block h-64 w-64 object-none object-cover hover:opacity-75">
                        <source v-bind:src="each.data.url" type="video/webm">
                    </video>
                </router-link>
            </div> // Not support Demo... -->
            <div>
                <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id } }">
                    <img class="block h-64 w-64 object-none object-cover hover:opacity-75" v-bind:src="each.data.thumbnail_url" alt="placeholder">
                </router-link>
            </div>
        </div>

        <div class="absolute bottom-0 mb-10">
            <button class="mr-2 px-3 py-1 hover:bg-white rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage('-'); consoleLogIndex();">Prev</button>
            <button class="ml-2 px-3 py-1 hover:bg-white rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage('+'); consoleLogIndex();">Next</button>
        </div>

    </div>
</template>

<script>
import { EVENT_BUS } from '../eventBus';

export default {

    name: "ImageIndex",
    
    data() {
        return {
            fetched: [],
            paginated: [],
            currentPage: []
        }
    },

    created()
    {
        EVENT_BUS.$on('NewTagAndReRender', () => {
            console.log("Search with: ", this.$globalParams.tagObjectArray);
            
            this.$globalParams.currentPageIndex = 0;
            if (this.$globalParams.postedBy !== undefined)
            {
                this.$globalParams.postedBy = undefined;
            }

            this.taggedBy();
        });
    },

    mounted() {
        
        console.log("Passed params from ImageShow Component: ", this.$route.params);

        if (this.$route.params.postedBy !== undefined)
        {
            this.$emit('flushSeletedTagNames');
            if (this.$route.params.pageIndexReset === true)
            {
                this.$globalParams.currentPageIndex = 0;
            }

            if (this.$globalParams.tagObjectArray.length > 0)
            {
                this.$globalParams.tagObjectArray = [];
            }

            this.$globalParams.postedBy = this.$route.params.postedBy;
            this.postedBy();
            return;
        }

        if (this.$route.params.tagged === true)
        {
            this.taggedBy();
            return;
        }

        axios.get('/api/imgs')
            .then( response => {
                this.fetched = response.data.data;
                this.pagenatedPosts(this.fetched);
            })
            .catch( errors => {
                console.log(errors);
                alert("Unable to Fetch Images");
            });
    },

    destroyed()
    {
        EVENT_BUS.$off();
    },

    methods: {

        taggedBy()
        {
            let tagIds = [];

            this.$globalParams.tagObjectArray.map( (each) => {
                tagIds.push(each.id);
            });

            axios.get('/api/tag', { params: { data: tagIds } })
                .then( response => {
                    this.fetched = response.data.data;
                    this.pagenatedPosts(this.fetched);
                })
                .catch( errors => {
                    console.log(errors);
                    alert("Unable to Fetch Images");
                });
        },

        postedBy()
        {
            axios.get('/api/imgs/user/' + this.$globalParams.postedBy)
                .then( response => {
                    this.fetched = response.data.data;
                    this.pagenatedPosts(this.fetched);
                })
                .catch( errors => {
                    console.log(errors);
                    alert("Unable to Fetch Images");
                });
        },

        pagenatedPosts(posts)
        {
            this.paginated = [];

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

            console.log("Pagenated content: ", this.paginated);
            console.log("current Page Index: ", this.$globalParams.currentPageIndex);
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

            this.currentPage = this.paginated[this.$globalParams.currentPageIndex];
        },

        consoleLogIndex()
        {
            console.log("current Page Index: ", this.$globalParams.currentPageIndex);
        }
    }
}
</script>

<style scoped>

</style>