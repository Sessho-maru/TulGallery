<template>
    <div class="overflow-x-hidden">

        <div v-for="each in currentPage" class="mb-4 px-2 w-1/2 md:w-1/3 lg:w-1/6">
            <router-link :to="{ name: 'ImageShow', params: { id: each.data.image_id, isTaged: true, currentPageIndex: index } }">
                <img class="block h-64 w-64 object-none object-cover hover:opacity-75" v-bind:src="each.data.thumbnail_url" alt="placeholder">
            </router-link>
        </div>

        <div class="absolute bottom-0 mb-10">
            <button class="mr-2 px-3 py-1 rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage(index - 1); index--;">Prev</button>
            <button class="ml-2 px-3 py-1 rounded text-sm text-blue-500 border border-blue-500 text-sm font-bold" @click="changeCurrentPage(index + 1); index++;">Next</button>
        </div>

    </div>
</template>

<script>
export default {

    name: 'ImageIndexWithTags',

    data() {
        return {
            paginated: [],
            currentPage: "",
            index: 0
        }
    },

    mounted() {
        console.log(this.$tag_ids);

        axios.get('/api/tag', { params: { data: this.$tag_ids } })
            .then( response => {
                let posts = response.data.data;
                console.log(posts);
                let pageSize = Math.ceil(posts.length / this.$maxSizePerEachItem);

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
                console.log(errors);
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