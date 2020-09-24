<template>
    <div class="overflow-x-hidden">

        <div v-bind:key="each.data.image_id" v-for="each in currentPage" class="w-1/2 md:w-1/3 lg:w-1/6">
            <div v-if="each.data.format === 'webm'">
                <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, currentPageIndex: index } }">
                    <video class="block h-64 w-64 object-none object-cover hover:opacity-75">
                        <source v-bind:src="each.data.url" type="video/webm">
                    </video>
                </router-link>
            </div>
            <div v-else>
                <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, currentPageIndex: index } }">
                    <img class="block h-64 w-64 object-none object-cover hover:opacity-75" v-bind:src="each.data.thumbnail_url" alt="placeholder">
                </router-link>
            </div>
        </div>

        <div class="absolute bottom-0 mb-10">
            <button class="mr-2 px-3 py-1 rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage(index - 1); index--;">Prev</button>
            <button class="ml-2 px-3 py-1 rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage(index + 1); index++;">Next</button>
        </div>

    </div>
</template>

<script>
export default {

    name: "ImageIndex",
    
    data() {
        return {
            paginated: [],
            currentPage: "",
            index: 0
        }
    },

    mounted() {
        axios.get('/api/imgs')
            .then( response => {
                let posts = response.data.data;
                console.log(posts);
                let pageSize = Math.ceil(posts.length / 24);

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

                    if (j % (this.$maxSizePerEachItem - 1) === 0 && j !== 0)
                    {
                        i++;
                        j = 0;
                    }
                    else
                    {
                        j++;
                    }
                }

                if (this.$route.params.currentPageIndex != null)
                {
                    this.currentPage = this.paginated[this.$route.params.currentPageIndex];
                    this.index = this.$route.params.currentPageIndex;
                    console.log("current Page Index = " + this.index);
                }
                else
                {
                    this.changeCurrentPage(this.index);
                }
            })
            .catch( errors => {
                alert("Unable to Fetch Images");
            });
    },

    methods: {
        changeCurrentPage(index)
        {   
            if (index < 0)
            {
                this.index++;
                return;
            }

            if (index >= this.paginated.length)
            {
                this.index--;
                return;
            }

            console.log("current Page Index = " + index);
            this.currentPage = this.paginated[index];
        }
    }
}
</script>

<style scoped>

</style>